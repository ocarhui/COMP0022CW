<?php 
session_start();

require 'setup_database.php';
require 'database.php'; 

// Define all available columns
$all_columns = [
    'poster_URL' => 'Poster',
    'MovieID' => 'Movie ID',
    'Title' => 'Title',
    'release_year' => 'Year',
    'original_language' => 'Original Language',
    'runtime' => 'Runtime',
    'box_office' => 'Box Office',
    'budget' => 'Budget',
    'tmdb_popularity' => 'TMDB Popularity',
    'imdb_rating' => 'IMDB Rating',
    'imdb_rating_votes' => 'IMDB Rating Votes',
    'country' => 'Country',
    'genre' => 'Genre'
];

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

        /* Add these styles */
        button[type="button"] {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-right: 10px; /* Adjust margin as needed */
        }

        button[type="button"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="navbar">
    <header>
        <h1>Movie Search</h1>
    </header>
    <div class="menu">
        <a href="index.php"><u>Home</u></a>
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

<div class="search-container">
    <!-- Form for selecting columns -->
    <form method="post">
        <?php
        // Display checkboxes for each movie attribute
        foreach ($all_columns as $key => $value) {
            $checked = isset($_POST['selected_columns']) && in_array($key, $_POST['selected_columns']) ? 'checked' : '';
            echo "<input type='checkbox' name='selected_columns[]' value='$key' $checked> $value";
        }
        ?>
        <br>
        <br>
        <button type="button" onclick="selectAll()">Select All</button>
        <button type="button" onclick="unselectAll()">Unselect All</button>
        <input type="submit" name="submit" value="Submit">
    </form>

    <?php
    // Initialize selected columns array
    $selected_columns = [];

    // Handle form submission
    if (isset($_POST['submit'])) {
        if(isset($_POST['selected_columns']) && is_array($_POST['selected_columns'])) {
            $selected_columns = $_POST['selected_columns'];
        }
    }
    
    // Build the SELECT part of the SQL query
    if (isset($_POST['selected_columns'])) {
        // Not empty - concatenate the selected columns with "m." prefix
        $select_part = "m." . implode(", m.", $selected_columns);
    } else {
        // Empty - null
        $select_part = "null";
    }

    // Set country and genre
    $select_join = "";
    // country
    $select_part = str_replace("m.country", "GROUP_CONCAT(DISTINCT pc.countryName SEPARATOR ', ') AS country", $select_part);
    if(in_array("country", $selected_columns)){
        $select_join .= "LEFT JOIN movie_countries mc ON m.movieID = mc.movieID LEFT JOIN production_countries pc ON mc.countryID = pc.countryID ";
    }
    // genre
    $select_part = str_replace("m.genre", "GROUP_CONCAT(DISTINCT genre.genreName SEPARATOR ', ') AS genre", $select_part);
    if(in_array("genre", $selected_columns)){
        $select_join .= "LEFT JOIN movie_genre ge ON m.movieID = ge.movieID LEFT JOIN genre ON ge.genreID = genre.genreID ";
    }
    // Construct the SQL query
    // $sql = "SELECT $select_part FROM movies m $select_join  WHERE m.MovieID <= 1000 Group By m.MovieID";
    $sql = "SELECT $select_part FROM movies m $select_join Group By m.MovieID";

    // Execute the SQL query
    // echo "$sql";
    $result = $mysqli->query($sql);

    ?>
</div>

<div class="results">
    <?php
    // Your PHP script for fetching and displaying filter results
    if ($select_part === 'null' || empty($selected_columns)) {
        echo "<h3>Please select film information to display</h3>";
    } elseif ($result && $result->num_rows > 0) {
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
        echo "<tr>";
        foreach ($selected_columns as $column) {
            echo "<th class='$column'>$all_columns[$column]</th>";
        }
        echo "</tr>";

        // Table contents
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            foreach ($selected_columns as $column) {
                if ($row[$column] == null){
                    // information not exist
                    echo "<td>N/A</td>";
                } elseif ($column === 'poster_URL'){
                    if ($row[$column] == 'nan') {
                        // poster not exist
                        echo "<td>No Poster Found</td>";
                    } else{
                        // show poster
                        echo "<td>" . "<img src='$row[$column]' alt='poster' width='150' height='225'>" . "</td>" ;
                    }
                } else {
                    // show exist information
                    echo "<td>".$row[$column]."</td>";
                }
            }
            echo "</tr>";
        }
        echo "</table>";

    } else {
        echo "Query failed: " . $mysqli->error;
    }
    ?>
</div>

<script>
    function selectAll() {
        var checkboxes = document.getElementsByName('selected_columns[]');
        checkboxes.forEach(function(checkbox) {
            checkbox.checked = true;
        });
    }

    function unselectAll() {
        var checkboxes = document.getElementsByName('selected_columns[]');
        checkboxes.forEach(function(checkbox) {
            checkbox.checked = false;
        });
    }
</script>

</body>
</html>