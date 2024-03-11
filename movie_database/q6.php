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
            $agreeablenessScores = [];
            $emotionalStabilityScores = [];
            $conscientiousnessScores = [];
            $extraversionScores = [];
            $averageRatings = [];

            $result = getHighRatingSQL($mysqli);

            while ($row = $result->fetch_assoc()) {
                $agreeablenessScores[] = $row['agreeableness'];
                $emotionalStabilityScores[] = $row['emotional_stability'];
                $conscientiousnessScores[] = $row['conscientiousness'];
                $extraversionScores[] = $row['extraversion'];
                $averageRatings[] = $row['rating'];
            }
            
            $agreeablenessCorrelation = pearsonCorrelation($agreeablenessScores, $averageRatings);
            $emotionalStabilityScoresCorrelation = pearsonCorrelation($emotionalStabilityScores, $averageRatings);
            $conscientiousnessScoresCorrelation = pearsonCorrelation($conscientiousnessScores, $averageRatings);
            $extraversionScoresCorrelation = pearsonCorrelation($extraversionScores, $averageRatings);
            echo "<b>Agreeableness correlation:</b> " . $agreeablenessCorrelation . "<br>";
            echo "<b>Emotional Stability correlation:</b> " . $emotionalStabilityScoresCorrelation . "<br>";
            echo "<b>Conscientiousness correlation:</b> " . $conscientiousnessScoresCorrelation . "<br>";
            echo "<b>Extraversion correlation:</b> " . $extraversionScoresCorrelation . "<br>";

            
        } 
        
        if ($selectedOption == "genre") {
            echo "Genre selected";
        }
    }

        
    ?>
</div>

</body>
</html>

<?php

function getHighRatingSQL ($mysqli){
    $sql = "SELECT p.rating_userID, p.agreeableness, p.emotional_stability, p.conscientiousness, p.extraversion, AVG(r.rating) as rating
            FROM personality p
            JOIN ratings r
            WHERE p.rating_userID = r.rating_userID
            AND (rating >= 4 OR rating <= 2)
            GROUP BY p.rating_userID;";
            

    $result = $mysqli->query($sql);

    return $result;
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

