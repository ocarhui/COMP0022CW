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
            max-width: 300px;
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
    </style>
</head>
<body>

<div class="navbar">
    <header>
        <h1>Movie Search</h1>
    </header>
    <div class="menu">
        <a href="index.php">Home</a>
        <a href="search.php"><u>Search</u></a>
        <a href="q3.php">Q3</a>
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
    <form method="get">
        <input type="text" id="search" name="search" placeholder="Enter movie title...">
        <input type="submit" value="Search">
    </form>
</div>

<div class="results">
    <?php
    // Your PHP script for fetching and displaying search results
    if (isset($_GET['search'])) {
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
                echo "<td><a href='movie_details.php?id=" . $row["movieID"] . "'><b>" . $title . "</b></a></td>\n"; 
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

function searchMovies($mysqli, $searchTerm) {
    // Escape the search term to prevent SQL Injection
    $searchTerm = $mysqli->real_escape_string($searchTerm);

    // Base SQL query
    $sql = "SELECT m.*, ";
    $sql .= "GROUP_CONCAT(DISTINCT c.name SEPARATOR ', ') AS crew, ";
    $sql .= "GROUP_CONCAT(DISTINCT co.countryName SEPARATOR ', ') AS countries ";
    $sql .= "FROM movies m ";
    $sql .= "LEFT JOIN movie_genre mg ON m.movieID = mg.movieID ";
    $sql .= "LEFT JOIN genre g ON mg.genreID = g.genreID ";
    $sql .= "LEFT JOIN movie_crew mc ON m.movieID = mc.movieID ";
    $sql .= "LEFT JOIN crew c ON mc.crewID = c.crewID ";
    $sql .= "LEFT JOIN movie_countries ct ON m.movieID = ct.movieID ";
    $sql .= "LEFT JOIN production_countries co ON ct.countryID = co.countryID ";
    $sql .= "WHERE m.title LIKE '$searchTerm' OR ";
    $sql .= "c.name LIKE '$searchTerm' OR ";
    $sql .= "g.genreName LIKE '$searchTerm' OR ";
    $sql .= "co.countryName LIKE '$searchTerm' OR ";
    $sql .= "m.release_year LIKE '$searchTerm' ";
    $sql .= "GROUP BY m.movieID";

    // Execute the query
    $result = $mysqli->query($sql);

    return $result ;
}
?>

