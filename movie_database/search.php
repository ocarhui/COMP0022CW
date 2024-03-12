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
            max-width: 300px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        select {
            padding: 10px;
            width: 50%;
            max-width: 110px;
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

<div class="search-container">
    <form method="get">
        <input type="text" id="search" name="search" placeholder="Enter movie title..." value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>" required>
        <select id="number_selection" name="number_selection" required>
            <option value="" disabled>Select Label...</option>
            <option value="fuzzy"<?php if(isset($_GET['number_selection']) && $_GET['number_selection'] == 'fuzzy') echo ' selected'; ?>>Fuzzy Search</option>
            <option value="title"<?php if(isset($_GET['number_selection']) && $_GET['number_selection'] == 'title') echo ' selected'; ?>>Title</option>
            <option value="director"<?php if(isset($_GET['number_selection']) && $_GET['number_selection'] == 'director') echo ' selected'; ?>>Director</option>
            <option value="actor"<?php if(isset($_GET['number_selection']) && $_GET['number_selection'] == 'actor') echo ' selected'; ?>>Actor</option>
            <option value="genre"<?php if(isset($_GET['number_selection']) && $_GET['number_selection'] == 'genre') echo ' selected'; ?>>Genre</option>
            <option value="country"<?php if(isset($_GET['number_selection']) && $_GET['number_selection'] == 'country') echo ' selected'; ?>>Country</option>
            <option value="year"<?php if(isset($_GET['number_selection']) && $_GET['number_selection'] == 'year') echo ' selected'; ?>>Release Year</option>
            <option value="company"<?php if(isset($_GET['number_selection']) && $_GET['number_selection'] == 'company') echo ' selected'; ?>>Company</option>
        </select>
        <input type="submit" value="Search">
    </form>
</div>

<div class="results">
    <?php
    // Your PHP script for fetching and displaying search results
    if (isset($_GET['search'])) {
        // Assume $mysqli is already connected
        require 'setup_database.php';
        $search = $_GET['search'];
        $search = "%" . $search . "%";
        $label = $_GET['number_selection'];
        $result = searchMovies($mysqli, $label, $search);
        $mysqli->close();

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

function searchMovies($mysqli, $label, $searchTerm) {
    switch ($label) {
        case "fuzzy":
          return search_fuzzy($mysqli, $searchTerm);
          break;
        case "title":
          return search_title($mysqli, $searchTerm);
          break;
        case "director":
          return search_director($mysqli, $searchTerm);
          break;
        case "actor":
          return search_actor($mysqli, $searchTerm);
          break;
        case "genre":
          return search_genre($mysqli, $searchTerm);
          break;
        case "country":
          return search_country($mysqli, $searchTerm);
          break;
        case "year":
          return search_year($mysqli, $searchTerm);
          break;
        case "company":
          return search_company($mysqli, $searchTerm);
          break;
      }
      
}

function search_fuzzy($mysqli, $searchTerm) {
    $sql = "SELECT m.*, ";
    $sql .= "GROUP_CONCAT(DISTINCT c.name SEPARATOR ', ') AS crew, ";
    $sql .= "GROUP_CONCAT(DISTINCT co.countryName SEPARATOR ', ') AS countries ";
    //$sql .= "GROUP_CONCAT(DISTINCT cp.companyName SEPARATOR ', ') AS companies ";
    $sql .= "FROM movies m ";
    $sql .= "LEFT JOIN movie_genre mg ON m.movieID = mg.movieID ";
    $sql .= "LEFT JOIN genre g ON mg.genreID = g.genreID ";
    $sql .= "LEFT JOIN movie_crew mc ON m.movieID = mc.movieID ";
    $sql .= "LEFT JOIN crew c ON mc.crewID = c.crewID ";
    $sql .= "LEFT JOIN movie_countries ct ON m.movieID = ct.movieID ";
    $sql .= "LEFT JOIN production_countries co ON ct.countryID = co.countryID ";
    $sql .= "LEFT JOIN movie_production_companies mpc ON m.movieID = mpc.movieID ";
    $sql .= "LEFT JOIN production_companies cp ON mpc.companyID = cp.companyID ";
    $sql .= "WHERE m.title LIKE ? OR ";
    $sql .= "c.name LIKE ? OR ";
    $sql .= "g.genreName LIKE ? OR ";
    $sql .= "co.countryName LIKE ? OR ";
    $sql .= "m.release_year LIKE ? OR ";
    $sql .= "cp.companyName LIKE ? ";
    $sql .= "GROUP BY m.movieID";

    $stmt = $mysqli->prepare($sql);
    $searchTerm = '%' . $searchTerm . '%';
    $stmt->bind_param("ssssss", $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    return $result;
}

function search_title($mysqli, $searchTerm) {
    $sql = "SELECT m.* ";
    $sql .= "FROM movies m ";
    $sql .= "WHERE m.title LIKE ?";
    $sql .= " GROUP BY m.movieID";

    $stmt = $mysqli->prepare($sql);
    $searchTerm = '%' . $searchTerm . '%';
    $stmt->bind_param("s", $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result;
}

function search_director($mysqli, $searchTerm) {
    $sql = "SELECT m.* ";
    $sql .= "FROM movies m ";
    $sql .= "LEFT JOIN movie_crew mc ON m.movieID = mc.movieID ";
    $sql .= "LEFT JOIN crew c ON mc.crewID = c.crewID ";
    $sql .= "WHERE c.name LIKE ? AND mc.occupationID = 2";
    $sql .= " GROUP BY m.movieID";

    $stmt = $mysqli->prepare($sql);
    $searchTerm = '%' . $searchTerm . '%';
    $stmt->bind_param("s", $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result;
}

function search_actor($mysqli, $searchTerm) {
    $sql = "SELECT m.* ";
    $sql .= "FROM movies m ";
    $sql .= "LEFT JOIN movie_crew mc ON m.movieID = mc.movieID ";
    $sql .= "LEFT JOIN crew c ON mc.crewID = c.crewID ";
    $sql .= "WHERE c.name LIKE ? AND mc.occupationID = 1";
    $sql .= " GROUP BY m.movieID";

    $stmt = $mysqli->prepare($sql);
    $searchTerm = '%' . $searchTerm . '%';
    $stmt->bind_param("s", $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result;
}

function search_genre($mysqli, $searchTerm) {
    $sql = "SELECT m.* ";
    $sql .= "FROM movies m ";
    $sql .= "LEFT JOIN movie_genre mg ON m.movieID = mg.movieID ";
    $sql .= "LEFT JOIN genre g ON mg.genreID = g.genreID ";
    $sql .= "WHERE g.genreName LIKE ?";
    $sql .= " GROUP BY m.movieID";

    $stmt = $mysqli->prepare($sql);
    $searchTerm = '%' . $searchTerm . '%';
    $stmt->bind_param("s", $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result;
}

function search_country($mysqli, $searchTerm) {
    $sql = "SELECT m.* ";
    $sql .= "FROM movies m ";
    $sql .= "LEFT JOIN movie_countries ct ON m.movieID = ct.movieID ";
    $sql .= "LEFT JOIN production_countries co ON ct.countryID = co.countryID ";
    $sql .= "WHERE co.countryName LIKE ?";
    $sql .= " GROUP BY m.movieID";

    $stmt = $mysqli->prepare($sql);
    $searchTerm = '%' . $searchTerm . '%';
    $stmt->bind_param("s", $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result;
}

function search_year($mysqli, $searchTerm) {
    $sql = "SELECT m.* ";
    $sql .= "FROM movies m ";
    $sql .= "WHERE m.release_year LIKE ?";
    $sql .= " GROUP BY m.movieID";

    $stmt = $mysqli->prepare($sql);
    $searchTerm = '%' . $searchTerm . '%';
    $stmt->bind_param("s", $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result;
}

function search_company($mysqli, $searchTerm) {
    $sql = "SELECT m.* ";
    $sql .= "FROM movies m ";
    $sql .= "LEFT JOIN movie_production_companies mpc ON m.movieID = mpc.movieID ";
    $sql .= "LEFT JOIN production_companies cp ON mpc.companyID = cp.companyID ";
    $sql .= "WHERE cp.companyName LIKE ?";
    $sql .= " GROUP BY m.movieID";

    $stmt = $mysqli->prepare($sql);
    $searchTerm = '%' . $searchTerm . '%';
    $stmt->bind_param("s", $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result;
}
?>

