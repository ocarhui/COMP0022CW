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
        <a href="q6a.php"><u>Personality Traits & Rating Correlation</u></a>
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
                
        <label for="rating"><b>Rating:</b></label>
        <select id="rating" name="rating" style="width: 100%; max-width: 150px;">
        <option value="high">High</option>
        <option value="low">Low</option>
        </select>
                
        <input type="submit" value="Submit">

    </form>
</div>

<div class="results">
    

    <?php
    // Your PHP script for fetching and displaying search results
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['rating'])) {

        if (!empty($_POST['personalityTraits']) && !empty($_POST['rating'])) {

            $personalityTrait = $_POST['personalityTraits'];
            $rating = null;
            $greaterSmaller = $_POST['greaterSmaller'];
            $personalityTraitsValue = $_POST['personalityTraitsValue'];
                        
            if ($_POST['rating'] == "high") {
                $rating = 4;
            } 
            if ($_POST['rating'] == "low"){
                $rating = 2;
            }

            require 'setup_database.php';           
            $result = getRatingSQL($mysqli, $personalityTrait, $greaterSmaller, $personalityTraitsValue, $rating);
            $mysqli->close();
    
            while ($row = $result->fetch_assoc()) {
                $personalityTraitScore[] = $row[$personalityTrait];
                $ratingScore[] = $row['rating'];
            }
            
            if (empty($personalityTraitScore) || empty($ratingScore)) {
                echo "No correlation found";
            } else {
                $correlation = pearsonCorrelation($personalityTraitScore, $ratingScore);
                echo "<b>Correlation:</b> " . $correlation . "<br>";
            }
            
        }

    }

        
    ?>
</div>

</body>
</html>

<?php

function getRatingSQL ($mysqli, $personalityTrait, $greaterSmaller, $personalityTraitsValue, $rating){
    $personalityTrait = "p." . $personalityTrait;

    $sql = "SELECT p.rating_userID, $personalityTrait, AVG(r.rating) as rating " ;
    $sql .= "FROM personality p ";
    $sql .= "JOIN ratings r ";
    $sql .= "WHERE p.rating_userID = r.rating_userID ";
    if ($rating == 4) {
        $sql .= "AND rating >= 4 ";
    } elseif ($rating == 2) {
        $sql .= "AND rating <= 2 ";
    }
    
    if ($greaterSmaller == ">=") {
        $sql .= "AND $personalityTrait >= ? ";
    } elseif ($greaterSmaller == "<=") {
        $sql .= "AND $personalityTrait <= ? ";
    }
    $sql .= "GROUP BY p.rating_userID;";

    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $personalityTraitsValue);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

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

