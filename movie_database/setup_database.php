<?php

    $mysqli = new mysqli("db", "movieadmin", "secretpassword", "movie_database");
    $mysqli->query('SET GLOBAL local_infile=1');

    mysqli_options($mysqli, MYSQLI_OPT_LOCAL_INFILE, true);
    $local_infile = 'SET GLOBAL local_infile=1';

    if ($mysqli->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 

    if ($result = $mysqli->query($local_infile)){
        echo "local file is set \n";
    }else{
        echo $mysqli->error;
    }


    // Loading rating-users
    $result = $mysqli->query("SELECT * FROM `rating-users` LIMIT 1");
    if ($result && $result->num_rows > 0) {
        // echo "Table is not empty. No need to load data.\n";
    } else {
        $loadDataSQL = "LOAD DATA LOCAL INFILE 'Data/rating_users.csv'
                        INTO TABLE `rating-users`
                        FIELDS TERMINATED BY ','
                        LINES TERMINATED BY '\n'
                        IGNORE 1 LINES";

        // Execute the SQL statement
        if ($mysqli->query($loadDataSQL) === TRUE) {
            echo "rating-users.csv loaded successfully.\n";
        } else {
            echo "Error loading data: " . $mysqli->error;
        }
    }
?>
