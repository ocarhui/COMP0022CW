<?php 
session_start();

// require 'database.php'; 


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
            font-size: 20px;
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
            font-size: 16px;
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
            font-size: 26px;
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
        select {
            width: 2000px; /* Set a width that fits your content */
            padding: 5px; /* Add some padding for visual comfort */
            margin-right: 10px; /* Add some space between the dropdown and the next element */
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
        <a href="q6a.php">Personality Traits & Rating Correlation</a>
        <a href="q6b.php"><u>Personality Traits & Genres Correlation</u></a>
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
    <form id="rating" method="post">
        <b> Correlation between </b>

        <label for="personalityTraits"><b>Personality Traits:</b></label>
        <select id="personalityTraits" name="personalityTraits" style="width: 100%; max-width: 150px;">
        <option value="agreeableness">Agreeableness</option>
        <option value="emotional_stability">Emotional Stability</option>
        <option value="conscientiousness">Conscientiousness</option>
        <option value="extraversion">Extraversion</option>
        </select>
        
        <select id="greaterSmaller" name="greaterSmaller" style="width: 100%; max-width: 30px;">
        <option value=">=">>=</option>
        <option value="<="><=</option>
        
        </select>
        
        <select id="personalityTraitsValue" name="personalityTraitsValue" style="width: 100%; max-width: 20px;">
        <option value="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
        <option value="4">4</option>
        <option value="5">5</option>
        <option value="6">6</option>
        <option value="7">7</option>
        </select>

        <b> and </b>
                
        <label for="genre"><b>Genre:</b></label>
        <select id="genre" name="genre" style="width: 100%; max-width: 100px;">
        <option value="Animation">Animation</option>
        <option value="Fantasy">Fantasy</option>
        <option value="Children">Children</option>
        <option value="Documentary">Documentary</option>
        <option value="Mystery">Mystery</option>
        <option value="IMAX">IMAX</option>
        <option value="Western">Western</option>
        <option value="Horror">Horror</option>
        <option value="Comedy">Comedy</option>
        <option value="Adventure">Adventure</option>
        <option value="Thriller">Thriller</option>
        <option value="Film-Noir">Film-Noir</option>
        <option value="Action">Action</option>
        <option value="Drama">Drama</option>
        <option value="Romance">Romance</option>
        <option value="(no genres listed)">No Genres</option>
        <option value="Musical">Musical</option>
        <option value="War">War</option>
        <option value="Crime">Crime</option>
        <option value="Sci-Fi">Sci-Fi</option>
        </select>

        <label for="genre_rating_count"><b>with rating count >=</b></label>
        <select id="genre_rating_count" name="genre_rating_count" style="width: 100%; max-width: 45px;">
        <option value="5">5</option>
        <option value="10">10</option>
        <option value="15">15</option>
        <option value="20">20</option>
        <option value="25">25</option>
        <option value="30">30</option>
        <option value="50">50</option>
        <option value="100">100</option>
        <option value ="200">200</option>
        <option value="500">500</option>
        </select>
                
        <input type="submit" value="Submit">

    </form>
</div>

<div class="results">
    

    <?php
    // Your PHP script for fetching and displaying search results
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['genre'])) {

        if (!empty($_POST['personalityTraits']) && !empty($_POST['genre'])) {

            $personalityTrait = $_POST['personalityTraits'];
            $greaterSmaller = $_POST['greaterSmaller'];
            $personalityTraitsValue = $_POST['personalityTraitsValue'];
            $genre = $_POST['genre'];
            $genreRatingCount = $_POST['genre_rating_count'];
            
            require 'setup_database.php';              
            $result = getGenresSQL($mysqli, $personalityTrait, $greaterSmaller, $personalityTraitsValue, $genre, $genreRatingCount);
            $mysqli->close();
    
            while ($row = $result->fetch_assoc()) {
                $personalityTraitScore[] = $row[$personalityTrait];
                $ratingScore[] = $row['average_rating'];
                $ratingCount[] = $row['rating_count'];
            }
    
            if (empty($ratingCount) || empty($ratingScore)) {
                echo "No correlation found";
            } else {
                $correlation = weightedPearsonCorrelation($personalityTraitScore, $ratingScore, $ratingCount);
                echo "<b>Correlation:</b> " . $correlation . "<br>";
            }
        }

    }

        
    ?>
</div>

</body>
</html>

<?php

function getGenresSQL ($mysqli, $personalityTrait, $greaterSmaller, $personalityTraitsValue, $genre, $genreRatingCount){
    $personalityTrait = "p." . $personalityTrait;
    $sql = "SELECT p.rating_userID, $personalityTrait, AVG(r.rating) AS average_rating, COUNT(r.rating) AS rating_count ";
    $sql .= "FROM ratings r INNER JOIN movie_genre mg ON r.movieID = mg.movieID ";
    $sql .= "INNER JOIN genre g ON mg.genreID = g.genreID ";
    $sql .= "INNER JOIN personality p ON r.rating_userID = p.rating_userID ";
    $sql .= "WHERE g.genreName = ? "; 
    if ($greaterSmaller == ">=") {
        $sql .= "AND $personalityTrait >= ? ";
    } elseif ($greaterSmaller == "<=") {
        $sql .= "AND $personalityTrait <= ? ";
    }
    $sql .= "GROUP BY r.rating_userID, $personalityTrait ";
    $sql .= "HAVING COUNT(r.rating) > ? ";
    $sql .= "ORDER BY r.rating_userID, ";
    $sql .= "AVG(r.rating) DESC, ";
    $sql .= "COUNT(r.rating) DESC;";


    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("sii", $genre, $personalityTraitsValue, $genreRatingCount);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    return $result;
}


function weightedPearsonCorrelation($x, $y, $weights) {
    $weightedX = [];
    $weightedY = [];

    foreach ($weights as $index => $weight) {
        for ($i = 0; $i < $weight; $i++) { // Repeat data points based on weight
            $weightedX[] = $x[$index];
            $weightedY[] = $y[$index];
        }
    }

    // Continue with the standard Pearson calculation using the weighted arrays
    $length = count($weightedX);
    $mean1 = array_sum($weightedX) / $length;
    $mean2 = array_sum($weightedY) / $length;

    $a = 0;
    $b = 0;
    $axb = 0;
    $a2 = 0;
    $b2 = 0;

    for($i = 0; $i < $length; $i++) {
        $a = $weightedX[$i] - $mean1;
        $b = $weightedY[$i] - $mean2;
        $axb += $a * $b;
        $a2 += pow($a, 2);
        $b2 += pow($b, 2);
    }

    $sqrtA2 = sqrt($a2);
    $sqrtB2 = sqrt($b2);

    if($sqrtA2 == 0 || $sqrtB2 == 0) {
        return 0; // This means no variation and hence no correlation
    }

    $r = $axb / ($sqrtA2 * $sqrtB2);

    return $r;
}
?>

