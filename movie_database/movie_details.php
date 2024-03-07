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
$actors = [];

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
                     AND mc.occupationID = 1
                     OR mc.occupationID = 4" ;
    
    $result = $mysqli->query($query_actors);

    while ($row = $result->fetch_assoc()) {
        $actors[] = $row;
    }
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
        .container { width: 90%; margin: auto;;
            margin-top: 20px;
        }
        .movie-title { font-size: 30px; margin: 20px 0; }
        .movie-details { margin-bottom: 20px; }
        .movie-genres { font-style: italic; }
        .movie-actors { font-weight: bold; }
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
            <button class="back-button" onclick="history.back()" style="cursor: pointer;">< Return</button>
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
                    if (!empty($actors)){
                        
                        foreach ($actors as $actor) {
                            echo "<span class='actor'>" . htmlspecialchars($actor['Name']) . "</span> ";
                        }
                        
                        echo "</div>";
                    }

                    ?>
                </div>
                <!-- Add more movie details here -->
            </div>
        <?php else: ?>
            <div>Movie details not found.</div>
        <?php endif; ?>
    </div>
</body>
</html>