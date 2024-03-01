<?php 
require 'database.php'; 

session_start();

// Fetch distinct countries from the database
?>

<!doctype html>
<html>

<!-- Title and Styles -->
<head>
    <meta charset="utf-8"/>
    
    <!-- <link rel="stylesheet" href="css/style.css"> -->
    <style>
        body {
            font-size: 18px;
            text-align: left;
            margin-top: 60px;
            /* padding-top: 100px; */
        }

        #selection select,
        #selection input[type="submit"] {
            width: 200px;
            padding: 5px;
            font-size: 16px;
        }
    </style>
    
    <!-- <link rel="stylesheet" href="css/navigation_bar.css"> -->
    <style>
        .navbar {
            display: flex;
            justify-content: space-between; /* Align items to the start and end of the container */
            align-items: center; /* Vertically center items */
            background-color: #aaa; /* Set background color for the navbar */
            color: #000; /* Set text color */
            padding: 10px 20px; /* Add padding for better spacing */
            position: fixed; /* Fix the navbar at the top of the page */
            top: 0; /* Position it at the top */
            left: 0; /* Align it to the left */
            right: 0; /* Align it to the right */
            z-index: 1000; /* Ensure it stays on top of other content */
        }

        .navbar header h1 {
            margin: 0; /* Remove default margin */
        }

        .navbar .user-account {
            display: flex;
            align-items: right; /* Vertically center items */
        }

        .navbar .user-account form {
            margin-left: 10px; /* Add margin between the login/register buttons */
        }
    </style>
    
    <!-- <link rel="stylesheet" href="css/selection_box.css"> -->
</head>


<div class="navbar">
    <!-- Header and User Information -->
    <header>
        <h1>Movie Database</h1>
    </header>

    <!-- Show username if login -->
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

<body>
    <div id="filters">
        <!-- Filter -->
        <div id="selection">
            <!-- Result -->
            <?php include 'filter_result.php'; ?>
        </div>
    </div>

    <!-- <script>
        var checkList = document.getElementById('list1');
        checkList.getElementsByClassName('anchor')[0].onclick = function(evt) {
            if (checkList.classList.contains('visible'))
                checkList.classList.remove('visible');
            else
                checkList.classList.add('visible');
        }
    </script> -->

</body>
</html>
