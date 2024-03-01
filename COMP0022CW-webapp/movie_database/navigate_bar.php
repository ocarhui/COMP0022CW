<head>
    <link rel="stylesheet" href="css/navigation_bar.css">
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
