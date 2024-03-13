<?php
require 'setup_database.php';

if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $confirm_email = $_POST['confirm_email'];
    $password = $_POST['password'];
    $dob = $_POST['dob'];

    // Hash the password before storing it in the database (improve security)
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    // Evaluate the age of user
    $currentDate = date('Y-m-d');
    $age = date_diff(date_create($dob), date_create($currentDate))->y;

    // Validate matching email and confirm email
    if ($email !== $confirm_email) {
        $error = "Email and Confirm Email do not match.";
        header("Location: register.php?error=" . urlencode($error));
        exit();
    } 
    
    elseif ($age < 18){
        $error = "You must be 18 years old to register.";
        header("Location: register.php?error=" . urlencode($error));
        exit();
    } 
    
    else {
        // Check if username or email already exists
        $query = "SELECT * FROM users WHERE username = ? OR email = ?";
        $stmt = mysqli_prepare($mysqli, $query);
        mysqli_stmt_bind_param($stmt, "ss", $username, $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) > 0) {
            $error = "Username or Email already exists.";
            header("Location: register.php?error=" . urlencode($error));
            exit();
        } else {
            // Use prepared statement to insert user data
            $insert_query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
            $insert_stmt = mysqli_prepare($mysqli, $insert_query);
            mysqli_stmt_bind_param($insert_stmt, "sss", $username, $email, $hashed_password);

            // Execute the prepared statement
            if (!mysqli_stmt_execute($insert_stmt)) {
                error_log('Database Error: ' . mysqli_error($mysqli));
                die('Oops! Something went wrong. Please try again later.');
            } else {
                echo '<script>alert("Account created successfully!"); window.location.href = "login.php";</script>';
                exit();
            }
        }
    }
}

mysqli_close($mysqli);
?>
