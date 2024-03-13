<?php
// Start the PHP session to maintain state and track user details
session_start();

require 'setup_database.php';

function sanitize($input) {
    global $mysqli;
    return mysqli_real_escape_string($mysqli, htmlspecialchars(strip_tags($input)));
}

// Retrieve movie_id from the URL query string
$movie_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$movie_details = [];
$genres = [];
$actors = null;
$directors = null;
$companies = null;
$countries = null;
$initial_ratings = null;
$tags = null;

// Fetch movie details if a valid movie_id is provided
if ($movie_id > 0) {
    $query_movies = "SELECT m.*
                     FROM movies m
                     WHERE m.movieID = ?";
    $stmt = $mysqli->prepare($query_movies);
    $stmt->bind_param("i", $movie_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $movie_details = $row;
    }

    $query_genres = "SELECT g.genreName
                     FROM genre g
                     JOIN movie_genre mg ON g.genreID = mg.genreID
                     WHERE mg.movieID = ?";
    $stmt = $mysqli->prepare($query_genres);
    $stmt->bind_param("i", $movie_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $genres[] = $row['genreName'];
    }

    $query_actors = "SELECT c.name, mc.characters
                     FROM crew c
                     LEFT JOIN movie_crew mc ON c.crewID = mc.crewID
                     WHERE mc.movieID = ? AND (mc.occupationID = 1 OR mc.occupationID = 4)";
    $stmt = $mysqli->prepare($query_actors);
    $stmt->bind_param("i", $movie_id);
    $stmt->execute();
    $actors = $stmt->get_result();

    $query_director = "SELECT c.name, mc.characters
                       FROM crew c
                       LEFT JOIN movie_crew mc ON c.crewID = mc.crewID
                       WHERE mc.movieID = ? AND mc.occupationID = 2";
    $stmt = $mysqli->prepare($query_director);
    $stmt->bind_param("i", $movie_id);
    $stmt->execute();
    $directors = $stmt->get_result();

    $query_companies = "SELECT cp.companyName
                        FROM production_companies cp
                        LEFT JOIN movie_production_companies mpc ON cp.companyID = mpc.companyID
                        WHERE mpc.movieID = ?";
    $stmt = $mysqli->prepare($query_companies);
    $stmt->bind_param("i", $movie_id);
    $stmt->execute();
    $companies = $stmt->get_result();

    $query_countries = "SELECT pc.countryName
                        FROM production_countries pc
                        LEFT JOIN movie_countries mc ON pc.countryID = mc.countryID
                        WHERE mc.movieID = ?";
    $stmt = $mysqli->prepare($query_countries);
    $stmt->bind_param("i", $movie_id);
    $stmt->execute();
    $countries = $stmt->get_result();

    $query_ratings = "SELECT rt.rating
                      FROM ratings rt
                      WHERE rt.movieID = ?";
    $stmt = $mysqli->prepare($query_ratings);
    $stmt->bind_param("i", $movie_id);
    $stmt->execute();
    $initial_ratings = $stmt->get_result();

    $query_tags = "SELECT t.tag
                   FROM tags t
                   LEFT JOIN movie_tags tg ON t.tagID = tg.tagID
                   WHERE tg.movieID = ?";
    $stmt = $mysqli->prepare($query_tags);
    $stmt->bind_param("i", $movie_id);
    $stmt->execute();
    $tags = $stmt->get_result();

    $mysqli->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .navbar {
            background-color: #333;
            color: white;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .navbar header h1 {
            margin: 0;
        }
        .menu a {
            color: white;
            text-decoration: none;
            padding: 10px;
            align-items: center;
        }
        .user-account form {
            display: inline;
        }
        .user-account input[type="submit"] {
            margin-left: 10px;
        }
        .container { width: 90%; margin: auto;
            margin-top: 20px;
        }
        .movie-title { font-size: 30px; margin: 20px 0; }
        .movie-details { margin-bottom: 20px; }
        .movie-genres { font-style: italic; }
        .back-button {
            padding: 10px 20px;
            margin: 10px 0;
            background-color: #f2f2f2;
            color: #333;
            text-decoration: none;
            border: 1px solid #ddd;
            border-radius: 5px;
            cursor: pointer;
        }
        .back-button:hover {
            background-color: #e8e8e8;
        } 
        input[type="submit"] {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }


    </style>
</head>
<body>
    <?php
        function displayHistory() {
            if(isset($_SERVER['HTTP_REFERER'])) {
                $historyArray = explode(';', $_SERVER['HTTP_REFERER']);
                if(empty($historyArray)) {
                    die("To access the movie detail page, Please use Home Page or Search Page");
                }
            } else {
                die("To access the movie detail page, Please use Home Page or Search Page");
            }
        }
        displayHistory();
    ?>
    <div class="navbar">
        <header>
            <h1>Movie Search</h1>
        </header>
        <div class="menu">
            <a href="index.php">Home</a>
            <a href="search.php">Search</a>
            <a href="q3.php">Q3</a>
            <a href="q4.php">Q4</a>
            <a href="q5.php">Q5</a>
            <a href="q6a.php">Personality Traits & Rating Correlation</a>
            <a href="q6b.php">Personality Traits & Genres Correlation</a>
        </div>
        <div class="user-account">
            <?php if (isset($_SESSION['username'])) : ?>
                <span>Welcome, <?php echo $_SESSION['username']; ?></span>
                <form method="post" action="logout.php">
                    <input type="submit" value="Log out">
                </form>
            <?php else : ?>
                <form method="post" action="login.php">
                    <input type="submit" value="Login">
                </form>
                <form method="post" action="register.php">
                    <input type="submit" value="Register">
                </form>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="container">
        <?php if ($movie_id > 0 && !empty($movie_details)): ?>
            <div class="movie-poster">
                <img src="<?php echo htmlspecialchars($movie_details['poster_URL']); ?>"; alt='poster'; width='300' height='450'>
            <div class="movie-details">
                <div class="movie-title"><b><?php echo htmlspecialchars($movie_details['title']); ?></b></div>

                <div><b>Year:</b> <?php echo htmlspecialchars($movie_details['release_year']); ?> | 
                    <b>Runtime:</b> <?php echo htmlspecialchars($movie_details['runtime']); ?> minutes |
                    <b>IMDB Rating:</b> <?php echo htmlspecialchars($movie_details['imdb_rating']); ?> |
                    <b>IMDB Votes:</b> <?php echo htmlspecialchars($movie_details['imdb_rating_votes']); ?> |
                    <b>TMDB Popularity:</b> <?php echo htmlspecialchars($movie_details['tmdb_popularity']); ?> |
                    <b> Average Rating:</b> <?php
                        $total = 0;
                        $numRating = 0;
                        while ($currentRow = $initial_ratings->fetch_assoc()) {
                            $total = $total + $currentRow['rating'];
                            $numRating = $numRating + 1;
                        }
                        $avgRating = $total / $numRating;
                        echo htmlspecialchars($avgRating);
                        echo " | <b>Number of Ratings:</b> " . htmlspecialchars($numRating);


                    ?>

                </div>

                <div class="movie-genres"><b>Genres: </b>
                    <?php 
                    if (!empty($genres)){
                        
                        foreach ($genres as $genre) {
                            echo "<span class='genre'>" . htmlspecialchars($genre) . "</span> ";
                        }
                        
                    }

                    ?>
                </div>


                <div class="movie-actors"><b>Actors: </b>
                    <?php 
                    $previousRow = null; // Initialize to null; will hold the row for processing in the loop

                    // Fetch the first row
                    $currentRow = $actors->fetch_assoc();
                    
                    // Continue if there is at least one row
                    while ($currentRow) {
                        // Fetch the next row to check if the current row is the last
                        $nextRow = $actors->fetch_assoc();
                    
                        if ($previousRow !== null) {
                            // Process the previous row here, because you skipped it in the previous iteration
                            // Note: On the first iteration, this will be skipped
                            echo "<span class='actor'><b>" . htmlspecialchars($previousRow['name']) . "</b> ( Character: " . $previousRow['characters'] . ") | </span> ";
                        }
                    
                        // If $nextRow is false, then $currentRow is the last row
                        if (!$nextRow) {
                            // Process the last row
                            echo "<span class='actor'><b>" . htmlspecialchars($currentRow['name']) . "</b> ( Character: " . $currentRow['characters'] . ") </span> ";
                            // Optionally, do something special because it's the last row
                            break; // Exit the loop
                        }
                    
                        // Set up for the next iteration
                        $previousRow = $currentRow; // Move $currentRow to $previousRow for processing in the next iteration
                        $currentRow = $nextRow; // Move $nextRow to $currentRow for checking in the next iteration
                    
                    }


                    ?>
                </div>
                
                <div class="movie-directors"><b>Director(s): </b>
                    <?php 
                    $previousRow = null; // Initialize to null; will hold the row for processing in the loop

                    // Fetch the first row
                    $currentRow = $directors->fetch_assoc();
                    
                    // Continue if there is at least one row
                    while ($currentRow) {
                        // Fetch the next row to check if the current row is the last
                        $nextRow = $directors->fetch_assoc();
                    
                        if ($previousRow !== null) {
                            // Process the previous row here, because you skipped it in the previous iteration
                            // Note: On the first iteration, this will be skipped
                            echo "<span class='director'>" . htmlspecialchars($previousRow['name']) . "| </span> ";
                        }
                    
                        // If $nextRow is false, then $currentRow is the last row
                        if (!$nextRow) {
                            // Process the last row
                            echo "<span class='director'>" . htmlspecialchars($currentRow['name']) . " </span> ";
                            // Optionally, do something special because it's the last row
                            break; // Exit the loop
                        }
                    
                        // Set up for the next iteration
                        $previousRow = $currentRow; // Move $currentRow to $previousRow for processing in the next iteration
                        $currentRow = $nextRow; // Move $nextRow to $currentRow for checking in the next iteration
                    
                    }

                    ?>
                </div>

                <div class="movie-companies"><b>Production Companies: </b>
                    <?php 
                    $previousRow = null; // Initialize to null; will hold the row for processing in the loop

                    // Fetch the first row
                    $currentRow = $companies->fetch_assoc();
                    
                    // Continue if there is at least one row
                    while ($currentRow) {
                        // Fetch the next row to check if the current row is the last
                        $nextRow = $companies->fetch_assoc();
                    
                        if ($previousRow !== null) {
                            // Process the previous row here, because you skipped it in the previous iteration
                            // Note: On the first iteration, this will be skipped
                            echo "<span class='company'>" . htmlspecialchars($previousRow['companyName']) . "| </span> ";
                        }
                    
                        // If $nextRow is false, then $currentRow is the last row
                        if (!$nextRow) {
                            // Process the last row
                            echo "<span class='company'>" . htmlspecialchars($currentRow['companyName']) . " </span> ";
                            // Optionally, do something special because it's the last row
                            break; // Exit the loop
                        }
                    
                        // Set up for the next iteration
                        $previousRow = $currentRow; // Move $currentRow to $previousRow for processing in the next iteration
                        $currentRow = $nextRow; // Move $nextRow to $currentRow for checking in the next iteration
                    
                    }

                    ?>
                </div>

                <div class="movie-countries"><b>Production Countries: </b>
                    <?php 
                    $previousRow = null; // Initialize to null; will hold the row for processing in the loop

                    // Fetch the first row
                    $currentRow = $countries->fetch_assoc();
                    
                    // Continue if there is at least one row
                    while ($currentRow) {
                        // Fetch the next row to check if the current row is the last
                        $nextRow = $countries->fetch_assoc();
                    
                        if ($previousRow !== null) {
                            // Process the previous row here, because you skipped it in the previous iteration
                            // Note: On the first iteration, this will be skipped
                            echo "<span class='country'>" . htmlspecialchars($previousRow['countryName']) . "| </span> ";
                        }
                    
                        // If $nextRow is false, then $currentRow is the last row
                        if (!$nextRow) {
                            // Process the last row
                            echo "<span class='country'>" . htmlspecialchars($currentRow['countryName']) . " </span> ";
                            // Optionally, do something special because it's the last row
                            break; // Exit the loop
                        }
                    
                        // Set up for the next iteration
                        $previousRow = $currentRow; // Move $currentRow to $previousRow for processing in the next iteration
                        $currentRow = $nextRow; // Move $nextRow to $currentRow for checking in the next iteration
                    
                    }

                    ?>
                </div>

                <div class = "movie-overview"><b>Overview:</b> 
                    <?php echo htmlspecialchars($movie_details['overview']); ?>
                </div>

                <div class = "movie-money"><b>Budget:</b> 
                    <?php echo htmlspecialchars('$'.$movie_details['budget']); ?> | <b>Box Office:</b> <?php echo htmlspecialchars('$'.$movie_details['box_office']); ?> 
                </div>

                <div class="movie-tags"><b>Tag(s): </b>
                    <?php 
                    $previousRow = null; // Initialize to null; will hold the row for processing in the loop

                    // Fetch the first row
                    $currentRow = $tags->fetch_assoc();
                    
                    // Continue if there is at least one row
                    while ($currentRow) {
                        // Fetch the next row to check if the current row is the last
                        $nextRow = $tags->fetch_assoc();
                    
                        if ($previousRow !== null) {
                            // Process the previous row here, because you skipped it in the previous iteration
                            // Note: On the first iteration, this will be skipped
                            echo "<span class='director'>" . htmlspecialchars($previousRow['tag']) . "| </span> ";
                        }
                    
                        // If $nextRow is false, then $currentRow is the last row
                        if (!$nextRow) {
                            // Process the last row
                            echo "<span class='director'>" . htmlspecialchars($currentRow['tag']) . " </span> ";
                            // Optionally, do something special because it's the last row
                            break; // Exit the loop
                        }
                    
                        // Set up for the next iteration
                        $previousRow = $currentRow; // Move $currentRow to $previousRow for processing in the next iteration
                        $currentRow = $nextRow; // Move $nextRow to $currentRow for checking in the next iteration
                    
                    }

                    ?>
                </div>

            </div>
        <?php else: ?>
            <div>Movie details not found.</div>
        <?php endif; ?>
    </div>
</body>
</html>