<?php
// Retrieve error message, if any
$error = isset($_GET['error']) ? $_GET['error'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="css/register.css">
</head>
<body>
    <div class="register">
        <h2>Register</h2>
        <form action="register_process.php" method="POST">
            <label for="username">Username:</label>
            <input type="text" name="username" required>
            <br>
            <label for="email">Email:</label>
            <input type="email" name="email" required>
            <br>
            <label for="confirm_email">Confirm Email:</label>
            <input type="email" name="confirm_email" required>
            <br>
            <label for="password">Password:</label>
            <input type="password" name="password" required>
            <br>
            <label for="dob">Date of Birth:</label>
            <input type="date" name="dob" required>
            <br>
            <?php if ($error) : ?>
                <div class="error"><?php echo $error; ?></div>
            <?php endif; ?>
            <input type="submit" name="submit" value="Register">
        </form>
    </div>
    
    <br>
    <br>
    <a href="index.php">back</a>

</body>
</html>
