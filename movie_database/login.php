<?php
// Retrieve error message, if any
$error = isset($_GET['error']) ? $_GET['error'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        .bottom-sentence {
            position: fixed;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            padding: 10px;
            border-radius: 5px;
            font-size: 12px; /* Smaller font size */
        }

        .bottom-sentence a {
            color: #333;
            text-decoration: none;
        }

        .bottom-sentence a:hover {
            text-decoration: underline;
        }
    </style>
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
    <div class="login">
        <h2>Login</h2>
        <form action="login_process.php" method="POST">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username">
            <br>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password">
            <br>
            <?php if ($error) : ?>
                <div class="error"><?php echo $error; ?></div>
            <?php endif; ?>
            <br>
            <input type="submit" name="submit" value="Login">
        </form>
    </div>
    
    <br>
    <br>
    <a href="index.php">back</a>

    <div class="bottom-sentence">
    Made with LOVE by <a href="mailto:junzhang.li.21@ucl.ac.uk"><span style="font-weight: bold;">Group 8</span></a>
    </div>

</body>
</html>
