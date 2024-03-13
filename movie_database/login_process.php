<?php
require 'setup_database.php';

if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if username exists
    $query = "SELECT * FROM users WHERE username = ?";
    $stmt = mysqli_prepare($mysqli, $query);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) == 1) {
        // Username exists, now verify password
        $query = "SELECT password FROM users WHERE username = ?";
        $stmt = mysqli_prepare($mysqli, $query);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        mysqli_stmt_bind_result($stmt, $hashed_password);
        mysqli_stmt_fetch($stmt);

        // Verify password
        if (password_verify($password, $hashed_password)) {
            // Password is correct, redirect to dashboard or homepage
            session_start();
            $_SESSION['username'] = $username;
            header('Location: index.php');
            exit();
        } else {
            // Password is incorrect
            $error = "Invalid Username or Password";
            header("Location: login.php?error=" . urlencode($error));
            exit();
        }
    } else {
        // Username doesn't exist
        $error = "Invalid Username or Password";
        header("Location: login.php?error=" . urlencode($error));
        exit();
    }
}

mysqli_close($mysqli);
?>
