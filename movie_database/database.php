<?php
  $connection = mysqli_connect("db", "movieadmin", "secretpassword", "movie_database");

  if (mysqli_connect_errno())
    echo 'Failed to connect to the MySQL server: '. mysqli_connect_error();
?>
