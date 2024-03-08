<?php
// Start the PHP session to maintain state and track user details
session_start();

require 'setup_database.php';
require 'database.php'; 


// Retrieve movie_id from the URL query string
$movie_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Initialize an array to store the movie details
$movie_details = [];
$genres = [];
$actors = null;
$directors = null;

// Fetch movie details if a valid movie_id is provided
if ($movie_id > 0) {

    $query_movies = "SELECT m.*
                     FROM movies m
                     WHERE m.movieID = $movie_id";


    $result = $mysqli->query($query_movies);
    

    if ($row = $result->fetch_assoc()) {
            $movie_details = $row;
    }

    $query_genres = "SELECT g.genreName
                     FROM genre g
                     JOIN movie_genre mg ON g.genreID = mg.genreID
                     WHERE mg.movieID = $movie_id";
    
    $result = $mysqli->query($query_genres);

    while ($row = $result->fetch_assoc()) {
        $genres[] = $row['genreName'];
    }

    $query_actors = "SELECT c.name, mc.characters
                     FROM crew c
                     LEFT JOIN movie_crew mc ON c.crewID = mc.crewID
                     WHERE mc.movieID = $movie_id
                     AND (mc.occupationID = 1
                     OR mc.occupationID = 4)" ;
    
    $actors = $mysqli->query($query_actors);

    $query_director = "SELECT c.name, mc.characters
                     FROM crew c
                     LEFT JOIN movie_crew mc ON c.crewID = mc.crewID
                     WHERE mc.movieID = $movie_id
                     AND mc.occupationID = 2" ;
    
    $directors = $mysqli->query($query_director);
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
        .movie-overview { margin-left: 75px; margin-right: 75px} 
        .movie-money { margin-left: 75px; margin-right: 75px}
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

    </style>
</head>
<body>
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
            <!-- <button class="back-button" onclick="history.back()" style="cursor: pointer;">< Return</button> -->
            <!-- <button id="backButton" class="back-button" onclick="history.back()" style="cursor: pointer; display: none;">< Return</button> -->

            <div class="movie-poster">
                <img src="<?php echo htmlspecialchars($movie_details['poster_URL']); ?>"; alt='poster'; width='300' height='450'>
            <div class="movie-details">
                <div class="movie-title"><b><?php echo htmlspecialchars($movie_details['title']); ?></b></div>

                <div><b>Year:</b> <?php echo htmlspecialchars($movie_details['release_year']); ?></div>

                <div class="movie-genres"><b>Genres: </b>
                    <?php 
                    if (!empty($genres)){
                        
                        foreach ($genres as $genre) {
                            echo "<span class='genre'>" . htmlspecialchars($genre) . "</span> ";
                        }
                        
                        echo "</div>";
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
                            echo "<span class='actor'><b>" . htmlspecialchars($previousRow['name']) . "</b> ( Character: " . $previousRow['characters'] . "), </span> ";
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

                    echo "</div>";

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
                            echo "<span class='director'>" . htmlspecialchars($previousRow['name']) . ", </span> ";
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

                    echo "</div>";

                    ?>
                </div>

                <div class = "movie-overview"><b>Overview:</b> <?php echo htmlspecialchars($movie_details['overview']); ?></div>

                <div class = "movie-money"><b>Budget:</b> <?php echo htmlspecialchars($movie_details['budget']); ?> <b>Box Office:</b> <?php echo htmlspecialchars($movie_details['box_office']); ?> </div>
            </div>
        <?php else: ?>
            <div>Movie details not found.</div>
        <?php endif; ?>
    </div>
</body>

<!-- <script>
    // Check if there's a previous page in the browser's history and it's not the specified URL
    if (document.referrer !== "" && document.referrer !== "http://localhost:4000/q5.php") {
        // If there's a previous page and it's not the specified URL, show the back button
        document.getElementById("backButton").style.display = "inline-block";
    }
</script> -->

</html>