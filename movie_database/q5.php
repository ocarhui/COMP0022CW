<?php 
session_start();

require 'setup_database.php';
require 'database.php'; 


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
        select {
            padding: 10px;
            width: 50%;
            max-width: 70px;
            border: 1px solid #ddd;
            border-radius: 4px;
            -webkit-appearance: none; /* Remove default arrow */
            -moz-appearance: none;
            appearance: none;
            background-color: white; /* Reset background color */
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
        <a href="q4.php">Q4</a>
        <a href="q5.php"><u>Q5</u></a>
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
        <h3>
        Sample
        <input type="number" id="one_sample_percentage" name="one_sample_percentage" placeholder="30" value="<?php echo isset($_POST['one_sample_percentage']) ? $_POST['one_sample_percentage'] : ''; ?>">
        % of ratings for movie 
        <input type="text" id="movie" name="movie" placeholder="Movie ID..." value="<?php echo isset($_POST['movie']) ? $_POST['movie'] : ''; ?>" required>
        <input type="submit" name="MovieID" value="Submit">
        </h3>
    </form>
    <form method="post">
        <h3>
        Sample
        <input type="number" id="top_sample_percentage" name="top_sample_percentage" placeholder="30" value="<?php echo isset($_POST['top_sample_percentage']) ? $_POST['top_sample_percentage'] : ''; ?>">
        % of ratings for top 
        <select id="number_selection" name="number_selection" required>
            <option value="" disabled selected>Select...</option>
            <?php
            for ($i = 1; $i <= 10; $i++) {
                $selected = '';
                if (isset($_POST['number_selection']) && $_POST['number_selection'] == $i) {
                    $selected = 'selected';
                }
                echo "<option value=\"$i\" $selected>$i</option>";
            }
            ?>
        </select>
        most rated movies
        <input type="submit" name="TopMovies" value="Submit">
        </h3>
    </form>
</div>

<div class="results">
    <?php
    // Reactions in Movies
    if (isset($_POST['MovieID'])) {
        // Assume $mysqli is already connected
        $movie = $_POST['movie'];
        $one_sample_percentage = isset($_POST['one_sample_percentage']) && is_numeric($_POST['one_sample_percentage']) ? (int)$_POST['one_sample_percentage'] : 30;
        $result = oneMoviePrediction($mysqli, $movie, $one_sample_percentage);
        if (mysqli_num_rows($result) === 0) {
            echo "<h3>No Result</h3>";
        } elseif ($result) {
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
            echo "<tr> <th>Predicting Rating</th> <th>Overall Rating</th> <th>Accuracy of Prediction</th> </tr>";

            // Table contents
            $row = $result->fetch_assoc();
            $sample_number = $row['sample_number'];
            $sample_average = isset($row['sample_average']) ? $row['sample_average'] : 0;
            $total_number = $row['total_number'];
            $total_average = $row['total_average'];
            $accuracy = (1 - abs($sample_average - $total_average) / $total_average) * 100;

            echo "<tr>";
            echo "<td>" . round($sample_average, 2) . " (" . $sample_number . ")</td>";
            echo "<td>" . round($total_average, 2) . " (" . $total_number. ")</td>";
            echo "<td>" . round($accuracy, 2) . "%</td>";
            echo "</tr>";
            echo "</table>";
        } else {
            echo "Query failed: " . $mysqli->error;
        }
    }

    if (isset($_POST['TopMovies'])) {
        // Assume $mysqli is already connected
        $number_selection = $_POST['number_selection'];
        $top_sample_percentage = isset($_POST['top_sample_percentage']) && is_numeric($_POST['top_sample_percentage']) ? (int)$_POST['top_sample_percentage'] : 30;
        $result = topMoviePrediction($mysqli, $top_sample_percentage, $number_selection);
        if (mysqli_num_rows($result) === 0) {
            echo "<h3>No Result</h3>";
        } elseif ($result) {
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
            echo "<tr> <th>Movie ID</th> <th>Predicting Rating</th> <th>Overall Rating</th> <th>Accuracy of Prediction</th> </tr>";

            // Table contents
            while ($row = $result->fetch_assoc()) {
                $movie = $row['movieID'];
                $sample_number = $row['sample_number'];
                $sample_average = isset($row['sample_average']) ? $row['sample_average'] : 0;
                $total_number = $row['total_number'];
                $total_average = $row['total_average'];
                $accuracy = (1 - abs($sample_average - $total_average) / $total_average) * 100;

                echo "<tr>";
                echo "<td>" . $movie . "</td>";
                echo "<td>" . round($sample_average, 2) . " (" . $sample_number . ")</td>";
                echo "<td>" . round($total_average, 2) . " (" . $total_number. ")</td>";
                echo "<td>" . round($accuracy, 2) . "%</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "Query failed: " . $mysqli->error;
        }

    }

    ?>
</div>

</body>
</html>

<?php

function oneMoviePrediction($mysqli, $movie, $one_sample_percentage) {
    // Escape the search term to prevent SQL Injection
    $movie = $mysqli->real_escape_string($movie);
    $one_sample_percentage = $mysqli->real_escape_string($one_sample_percentage);

    // Base SQL query
    $sql = 
    // "SELECT 
    //     COUNT(*) AS total_number_of_ratings,
    //     AVG(rating) AS total_average_rating,
    //     SUM(rn) AS sample_number_of_ratings,
    //     AVG(CASE WHEN rn = 1 THEN rating END) AS sample_average_rating
    // FROM (
    //     SELECT 
    //         movieID,
    //         rating,
    //         IF(RAND() <= $one_sample_percentage, 1, 0) AS rn
    //     FROM ratings_original
    //     WHERE movieID = $movie
    // ) AS filtered_ratings
    // GROUP BY movieID;";
    "WITH MovieRatingCounts AS (
        SELECT
            movieID,
            COUNT(*) AS rating_count
        FROM
            ratings_original
        WHERE
            movieID = $movie
        GROUP BY
            movieID
    ), RankedRows AS (
        SELECT
            r.movieID,
            r.rating_userID,
            r.rating,
            rc.rating_count,
            NTILE(100) OVER (PARTITION BY r.movieID ORDER BY rc.rating_count DESC) AS bucket
        FROM
            ratings_original r
        JOIN (
            SELECT
                rating_userID,
                COUNT(movieID) AS rating_count
            FROM
                ratings_original
            GROUP BY
                rating_userID
        ) rc ON r.rating_userID = rc.rating_userID
        JOIN
            MovieRatingCounts mrc ON r.movieID = mrc.movieID
    )
    SELECT
        rr.movieID,
        COUNT(rr.rating) AS sample_number,
        mrc.rating_count AS total_number,
        AVG(rr.rating) AS sample_average,
        (SELECT AVG(rating) FROM ratings_original WHERE movieID = rr.movieID) AS total_average
    FROM
        RankedRows rr
    JOIN
        MovieRatingCounts mrc ON rr.movieID = mrc.movieID
    WHERE
        rr.bucket <= $one_sample_percentage
    GROUP BY
        rr.movieID,
        mrc.rating_count;";

    // Execute the query
    $result = $mysqli->query($sql);

    return $result ;
}

function topMoviePrediction($mysqli, $top_sample_percentage, $number_selection) {
    // Escape the search term to prevent SQL Injection
    $top_sample_percentage = $mysqli->real_escape_string($top_sample_percentage);
    $number_selection = $mysqli->real_escape_string($number_selection);

    // Base SQL query
    $sql = 
    // "SELECT 
    //     r.movieID,
    //     COUNT(*) AS total_number_of_ratings,
    //     AVG(r.rating) AS total_average_rating,
    //     SUM(rn) AS sample_number_of_ratings,
    //     AVG(CASE WHEN rn = 1 THEN r.rating END) AS sample_average_rating
    // FROM (
    //     SELECT 
    //         movieID,
    //         rating,
    //         IF(RAND() <= $top_sample_percentage, 1, 0) AS rn
    //     FROM ratings_original
    // ) AS r
    // JOIN (
    //     SELECT movieID
    //     FROM ratings_original
    //     GROUP BY movieID
    //     ORDER BY COUNT(*) DESC
    //     LIMIT $number_selection
    // ) AS top_movies
    // ON r.movieID = top_movies.movieID
    // GROUP BY r.movieID;";
    "WITH MovieRatingCounts AS (
        SELECT
            movieID,
            COUNT(*) AS rating_count
        FROM
            ratings_original
        GROUP BY
            movieID
        ORDER BY
            rating_count DESC
        LIMIT $number_selection
    ), RankedRows AS (
        SELECT
            r.movieID,
            r.rating_userID,
            r.rating,
            rc.rating_count,
            NTILE(100) OVER (PARTITION BY r.movieID ORDER BY rc.rating_count DESC) AS bucket
        FROM
            ratings_original r
        JOIN (
            SELECT
                rating_userID,
                COUNT(movieID) AS rating_count
            FROM
                ratings_original
            GROUP BY
                rating_userID
        ) rc ON r.rating_userID = rc.rating_userID
        JOIN
            MovieRatingCounts mrc ON r.movieID = mrc.movieID
    )
    
    SELECT
        rr.movieID,
        COUNT(rr.rating) AS sample_number,
        mrc.rating_count AS total_number,
        AVG(rr.rating) AS sample_average,
        (SELECT AVG(rating) FROM ratings_original WHERE movieID = rr.movieID) AS total_average
    FROM
        RankedRows rr
    JOIN
        MovieRatingCounts mrc ON rr.movieID = mrc.movieID
    WHERE
        rr.bucket <= $top_sample_percentage
    GROUP BY
        rr.movieID,
        mrc.rating_count
    ORDER BY
        total_number DESC;";

    // Execute the query
    $result = $mysqli->query($sql);

    return $result ;
}


?>

