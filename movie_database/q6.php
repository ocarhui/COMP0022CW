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

        .toggle-switch {
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 10px;
        }
        .toggle-switch input[type="radio"] {
            display: none;
        }
        .toggle-switch label {
            cursor: pointer;
            padding: 10px 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background-color: #f4f4f4;
            margin: 0 5px;
            transition: background-color 0.2s ease-in-out;
        }
        .toggle-switch label:hover {
            background-color: #e2e2e2;
        }
        .toggle-switch input[type="radio"]:checked + label {
            -webkit-transition: .4s;
            background-color: #007bff;
            color: white;
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
        <a href="q6.php"><u>Q6</u></a>
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
    <form id = "myform" method="post">
        <div class="toggle-switch">
            <input id="personality-rating" class="toggle" name="toggle" value="rating" type="radio" <?php echo (isset($_POST['toggle']) && $_POST['toggle'] == 'rating') ? 'checked' : ''; ?>>
            <label for="personality-rating"><b>Personality Traits & Rating</b></label>
                
            <input id="personality-genres" class="toggle" name="toggle" value="genre" type="radio" <?php echo (isset($_POST['toggle']) && $_POST['toggle'] == 'genre') ? 'checked' : ''; ?>>
            <label for="personality-genres"><b>Personality Traits & Genres</b></label>
        </div>
        <input type="submit" value="Submit">
    </form>
</div>

<div class="results">

    <?php
    // Your PHP script for fetching and displaying search results
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['toggle'])) {

        $selectedOption = $_POST['toggle'];
        
        if ($selectedOption == "rating") {
            
        } 
        
        if ($selectedOption == "genre") {
            echo "Genre selected";
        }
        // Assume $mysqli is already connected
        $search = $_GET['search'];
        $search = "%" . $search . "%";
        $result = searchMovies($mysqli, $search);
        //$query = "SELECT * FROM movies WHERE title LIKE $search";
        //$result = $mysqli->query($query);

        if ($result) {
            // Start the table and optionally add a border for visibility
            echo "<p>" . mysqli_num_rows($result) . " Results</p>" ;
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
            echo "<tr> <th> </th> <th>Title</th> <th>Year</th> <th>Original Language</th> <th>Runtime</th> <th>Overview</th> <th>TMDB Popularity</th> <th>IMDB Rating</th> </tr>";
            while ($row = $result->fetch_assoc()) {
                $title = htmlspecialchars($row['title']);
                $year = htmlspecialchars($row['release_year']);
                if ($row['original_language'] !== null) {
                    $original_language = htmlspecialchars($row['original_language']);
                } else {
                    $original_language = null;
                }
                if ($row['runtime'] !== null) {
                    $runtime = htmlspecialchars($row['runtime']);
                } else {
                    $runtime = null;
                }
                $overview = htmlspecialchars($row['overview']);
                $overview = strlen($overview) > 200 ? substr($overview, 0, 200) . "..." : $overview;
                $poster_URL = htmlspecialchars($row['poster_URL']);
                if ($row['box_office'] !== null) {
                    $box_office = htmlspecialchars($row['box_office']);
                } else {
                    $box_office = null;
                }
                if ($row['budget'] !== null) {
                    $budget = htmlspecialchars($row['budget']);
                } else {
                    $budget = null;
                }
                if ($row['tmdb_popularity'] !== null) {
                    $tmdb_popularity = htmlspecialchars($row['tmdb_popularity']);
                } else {
                    $tmdb_popularity = null;
                }
                if ($row['imdb_rating'] !== null) {
                    $imdb_rating = htmlspecialchars($row['imdb_rating']);
                } else {
                    $imdb_rating = null;
                }
                echo "<tr>"; // Start a new row for each record
                echo "<td>" . "<img src='$poster_URL' alt='poster' width='150' height='225'>" . "</td>" ;
                echo "<td><a href='movie_details.php?id=" . $row["movieID"] . "' target='_blank'><b>" . $title . "</b></a></td>\n"; 
                echo "<td>" . $year . "</td>";
                if ($original_language !== null) {
                    echo "<td>" . $original_language . "</td>";
                } else {
                    echo "<td> N/A </td>";
                }
                if ($runtime !== null) {
                    echo "<td>" . $runtime . "</td>";
                } else {
                    echo "<td> N/A </td>";
                }
                echo "<td>" . $overview . "</td>";
                if ($tmdb_popularity !== null) {
                    echo "<td>" . $tmdb_popularity . "</td>";
                } else {
                    echo "<td> N/A </td>";
                }
                if ($imdb_rating !== null) {
                    echo "<td>" . $imdb_rating . "</td>";
                } else {
                    echo "<td> N/A </td>";
                }
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

function getRatingSQL (){
    $sql = "SELECT p.rating_userID, p.agreeableness, p.emotional_stability, p.conscientiousness, p.extraversion, AVG(r.rating)
            FROM personality p
            JOIN ratings r
            WHERE p.rating_userID = r.rating_userID
            GROUP BY p.rating_userID;";
}

function pearsonCorrelation($xs, $ys) {
    $n = count($xs);
    $meanX = array_sum($xs) / $n;
    $meanY = array_sum($ys) / $n;

    $numerator = 0;
    $denominatorX = 0;
    $denominatorY = 0;

    for ($i = 0; $i < $n; $i++) {
        $numerator += ($xs[$i] - $meanX) * ($ys[$i] - $meanY);
        $denominatorX += pow($xs[$i] - $meanX, 2);
        $denominatorY += pow($ys[$i] - $meanY, 2);
    }

    if ($denominatorX == 0 || $denominatorY == 0) {
        return 0;
    }

    $denominator = sqrt($denominatorX) * sqrt($denominatorY);

    return $numerator / $denominator;
}
?>

