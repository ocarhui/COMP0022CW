<?php 
session_start();

// require 'setup_database.php';


// Fetch distinct countries from the database
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie Database</title>
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
        .search-container {
            padding: 20px;
            text-align: center;
        }
        input[type="text"] {
            padding: 10px;
            width: 50%;
            max-width: 70px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        input[type="number"] {
            padding: 10px;
            width: 50%;
            max-width: 70px;
            border: 1px solid #ddd;
            border-radius: 4px;
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
        .results {
            padding: 20px;
            text-align: center;
        }
        

        .low {
        background-color: #008000; /* Choose your desired color */
        height: 20px;
        }
        .mid {
        background-color: #ffd700; /* Choose your desired color */
        height: 20px;
        }
        .high {
        background-color: #ff6347; /* Choose your desired color */
        height: 20px;
        }
        .relative-high {
            background-color: #ff9f4d; /* Color between high (#ff6347) and mid (#ffd700) */
            height: 20px;
            mix-blend-mode: multiply;
        }
        .relative-low {
            background-color: #80bf7e; /* Color between mid (#ffd700) and low (#008000) */
            height: 20px;
            mix-blend-mode: multiply;
        }
        .rating-bar {
            display: flex;
            height: 20px; /* Set your desired height */
            border: 1px solid #000; /* Add border for clarity */
        }

        .rating-bar div {
            height: 100%;
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
        <a href="q4.php"><u>Q4</u></a>
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

<div class="search-container">
    <form method="post">
        <h3>Reactions to movie
        <input type="text" id="movie" name="movie" placeholder="Movie ID..." value="<?php echo isset($_POST['movie']) ? $_POST['movie'] : ''; ?>" required>
         from viewers who tend to give ratings between 
        <!-- <input type="text" id="upper" name="upper" placeholder="0" value="<?php echo isset($_POST['upper']) ? $_POST['upper'] : ''; ?>"> -->
        <input type="number" step="0.01" id="upper" name="upper" placeholder="0" value="<?php echo isset($_POST['upper']) ? $_POST['upper'] : ''; ?>">
         and 
        <!-- <input type="text" id="lower" name="lower" placeholder="0" value="<?php echo isset($_POST['lower']) ? $_POST['lower'] : ''; ?>"> -->
        <input type="number" step="0.01" id="lower" name="lower" placeholder="0" value="<?php echo isset($_POST['lower']) ? $_POST['lower'] : ''; ?>">
        <input type="submit" name="MovieID" value="Submit">
        </h3>
    </form>
    <form method="post">
        <h3>Reactions to genre
        <input type="text" id="target_genre" name="target_genre" placeholder="Genre..." value="<?php echo isset($_POST['target_genre']) ? $_POST['target_genre'] : ''; ?>" required>
        from viewers who tend to give ratings for movies in genre
        <input type="text" id="origin_genre" name="origin_genre" placeholder="Genre..." value="<?php echo isset($_POST['origin_genre']) ? $_POST['origin_genre'] : ''; ?>" required>
        between 
        <!-- <input type="text" id="genre_upper" name="genre_upper" placeholder="0" value="<?php echo isset($_POST['genre_upper']) ? $_POST['genre_upper'] : ''; ?>"> -->
        <input type="number" step="0.01" id="genre_upper" name="genre_upper" placeholder="0" value="<?php echo isset($_POST['genre_upper']) ? $_POST['genre_upper'] : ''; ?>">
         and 
        <!-- <input type="text" id="genre_lower" name="genre_lower" placeholder="0" value="<?php echo isset($_POST['genre_lower']) ? $_POST['genre_lower'] : ''; ?>"> -->
        <input type="number" step="0.01" id="genre_lower" name="genre_lower" placeholder="0" value="<?php echo isset($_POST['genre_lower']) ? $_POST['genre_lower'] : ''; ?>">
        <input type="submit" name="GenreID" value="Submit">
        </h3>
    </form>
</div>

<div class="results">
    <?php
    // Reactions in Movies
    if (isset($_POST['MovieID'])) {
        // Assume $mysqli is already connected
        require 'setup_database.php';
        $movie = $_POST['movie'];
        $upper = !empty($_POST['upper']) ? $_POST['upper'] : 0;
        $lower = !empty($_POST['lower']) ? $_POST['lower'] : 0;
        if ($upper < $lower) {
            $temp = $upper;
            $upper = $lower;
            $lower = $temp;
        }
        $result = movieReaction($mysqli, $movie, $upper, $lower);
        $mysqli->close();

        if (mysqli_num_rows($result) === 0) {
            echo "<h3>No Result</h3>";
        } elseif ($result) {
            // Start the table and optionally add a border for visibility
            // echo "<p>" . mysqli_num_rows($result) . " Results</p>" ;
            echo "<style>\n";
            echo "body { font-family: Arial, sans-serif; }\n";
            echo ".container {
                width: 100%;
                padding: 20px;
                box-sizing: border-box;
            }\n";
            echo "table { width: 100%; border-collapse: collapse; table-layout: fixed; }\n";
            echo "th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }\n";
            echo "th { background-color: #0096FF; color: white; }\n";
            echo "tr:nth-child(even) { background-color: #f2f2f2 }\n";
            echo "tr:hover { background-color: #ddd; }\n";
            echo "a { color: #333; text-decoration: none; }\n";
            echo "a:hover { text-decoration: underline; }\n";
            echo "</style>\n";

            echo "<table>"; 
            echo "<table border='0'>";

            // Table headers
            echo "<tr> <th>Movie</th> <th>Number of Viewers</th> <th>Average Rating</th> <th>High Rating Viewer Number</th> <th>Moderate Rating Viewer Number</th> <th>Low Rating Viewer Number</th> </tr>";

            // Table contents
            $row = $result->fetch_assoc();
            $title = $_POST['movie'];
            $movieID = $row['movieID'];
            $count = $row['total_rating'];
            $average = $row['average_rating'];
            $high = $row['high'];
            $low = $row['low'];
            $mid = $count - $high - $low;

            echo "<tr>";
            echo "<td><a href='movie_details.php?id=" . $movieID . "' target='_blank'><b>" . $title . "</b></a></td>";
            echo "<td>" . $count . "</td><td>" . round($average, 2) . "</td>";
            echo '<td><div class="high" style="width: ' . $high/$count*100 . '%;"></div>' . round($high*100/$count, 2) . '% ('. $high .')</td>';
            echo '<td><div class="mid" style="width: ' . $mid/$count*100 . '%;"></div>' . round($mid*100/$count, 2) . '% ('. $mid .')</td>';
            echo '<td><div class="low" style="width: ' . $low/$count*100 . '%;"></div>' . round($low*100/$count, 2) . '% ('. $low .')</td>';
            echo "</tr>";
            echo "</table>";
            $result->free();
            
        } else {
            echo "Query failed: " . $mysqli->error;
        }
    }

    // Reactions in Genres
    if (isset($_POST['GenreID'])) {
        // Assume $mysqli is already connected
        require 'setup_database.php';
        $target_genre = $_POST['target_genre'];
        $origin_genre = $_POST['origin_genre'];
        $genre_upper = !empty($_POST['genre_upper']) ? $_POST['genre_upper'] : 0;
        $genre_lower = !empty($_POST['genre_lower']) ? $_POST['genre_lower'] : 0;
        if ($genre_upper < $genre_lower) {
            $temp = $genre_upper;
            $genre_upper = $genre_lower;
            $genre_lower = $temp;
        }
        $result = genreReaction($mysqli, $target_genre, $origin_genre, $genre_upper, $genre_lower);
        $mysqli->close();
        
        if (mysqli_num_rows($result) === 0) {
            echo "<h3>No Result</h3>";
        } elseif ($result) {
            // Start the table and optionally add a border for visibility
            // echo "<p>" . mysqli_num_rows($result) . " Results</p>" ;
            echo "<style>\n";
            echo "body { font-family: Arial, sans-serif; }\n";
            echo ".container {
                width: 100%;
                padding: 20px;
                box-sizing: border-box;
            }\n";
            echo "table { width: 100%; border-collapse: collapse; table-layout: fixed; }\n";
            echo "th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }\n";
            echo "th { background-color: #0096FF; color: white; }\n";
            echo "tr:nth-child(even) { background-color: #f2f2f2 }\n";
            echo "tr:hover { background-color: #ddd; }\n";
            echo "a { color: #333; text-decoration: none; }\n";
            echo "a:hover { text-decoration: underline; }\n";
            echo "</style>\n";

            echo "<table>"; 
            echo "<table border='0'>";

            // Table headers
            echo "<tr> <th>Number of Viewers</th> <th>Number of Ratings</th> <th>Average Rating</th> <th>High Rating Viewer Number</th> <th>Moderate Rating Viewer Number</th> <th>Low Rating Viewer Number</th> </tr>";

            $row = $result->fetch_assoc();
            $total_viewers = $row['total_viewers'];
            $total_ratings = $row['total_ratings'];
            $average_rating = $row['average_rating'];
            $high = $row['high'];
            $low = $row['low'];
            $mid = $total_viewers - $high - $low;

            echo "<tr>";
            echo "<td>" . $total_viewers . "</td><td>" . $total_ratings . "</td><td>" . round($average_rating, 2) . "</td>";
            echo '<td><div class="high" style="width: ' . $high/$total_viewers*100 . '%;"></div>' . round($high*100/$total_viewers, 2) . '% ('. $high .')</td>';
            echo '<td><div class="mid" style="width: ' . $mid/$total_viewers*100 . '%;"></div>' . round($mid*100/$total_viewers, 2) . '% ('. $mid .')</td>';
            echo '<td><div class="low" style="width: ' . $low/$total_viewers*100 . '%;"></div>' . round($low*100/$total_viewers, 2) . '% ('. $low .')</td>';
            echo "</tr>";
            echo "</table>";
            $result->free();
            
        } else {
            echo "Query failed: " . $mysqli->error;
        }
    }

    ?>
</div>

</body>
</html>

<?php

function movieReaction($mysqli, $movie, $upper, $lower) {
    $sql = 
    "SELECT
        movieID,
        COUNT(*) AS total_rating,
        AVG(rating_for_chosen_movie) AS average_rating,
        SUM(CASE WHEN rating_for_chosen_movie >= 3.5 THEN 1 ELSE 0 END) AS high,
        SUM(CASE WHEN rating_for_chosen_movie < 2.5 THEN 1 ELSE 0 END) AS low
    FROM (
        SELECT 
            t1.rating_userID,
            (
                SELECT t2.rating 
                FROM ratings t2 
                WHERE t2.rating_userID = t1.rating_userID AND t2.movieID = (SELECT movieID FROM movies WHERE title = ?)
                LIMIT 1
            ) AS rating_for_chosen_movie,
            (SELECT movieID FROM movies WHERE title = ?) AS movieID
        FROM 
            ratings t1
        GROUP BY 
            t1.rating_userID
        HAVING 
            AVG(t1.rating) <= ? AND AVG(t1.rating) >= ?
            AND rating_for_chosen_movie IS NOT NULL
    ) AS subquery
    GROUP BY movieID;";

    // Prepare the statement
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("ssdd", $movie, $movie, $upper, $lower);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result;
    } else {
        // Handle error if preparation fails
        // return false;
        die("Preparation failed: " . $mysqli->error);
    }
}

function genreReaction($mysqli, $target_genre, $origin_genre, $genre_upper, $genre_lower) {
    $sql = 
    "WITH OriginGenreRatings AS (
        SELECT 
            r.rating_userID
        FROM 
             ratings r
        JOIN 
            movie_genre mg ON r.movieID = mg.movieID
        JOIN 
            genre g ON mg.genreID = g.genreID
        WHERE 
            g.genreName = ?
        GROUP BY 
            r.rating_userID
        HAVING 
            AVG(r.rating) <= ? AND AVG(r.rating) >= ?
    )
    SELECT 
        COUNT(*) AS total_viewers,
        SUM(a.rating_genre_count) AS total_ratings,
        COUNT(CASE WHEN a.avg_rating_genre >= 3.5 THEN 1 END) AS high,
        COUNT(CASE WHEN a.avg_rating_genre < 2.5 THEN 1 END) AS low,
        (SUM(a.avg_rating_genre * a.rating_genre_count) / SUM(a.rating_genre_count)) AS average_rating
    FROM 
        OriginGenreRatings c
    JOIN 
        (SELECT 
            r.rating_userID,
             AVG(r.rating) AS avg_rating_genre,
            COUNT(r.rating) AS rating_genre_count
        FROM 
            ratings r
        JOIN 
            movie_genre mg ON r.movieID = mg.movieID
        JOIN 
            genre g ON mg.genreID = g.genreID
        WHERE 
            g.genreName = ?
        AND
            r.rating_userID IN (SELECT rating_userID FROM OriginGenreRatings)
        GROUP BY 
            r.rating_userID) a
    ON 
        c.rating_userID = a.rating_userID;";

    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("sdds", $origin_genre, $genre_upper, $genre_lower, $target_genre);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    return $result;
}



?>
