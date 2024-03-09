<?php

    $mysqli = new mysqli("haproxy", "movieadmin", "secretpassword", "movie_database");
    $mysqli->query('SET GLOBAL local_infile=1');

    mysqli_options($mysqli, MYSQLI_OPT_LOCAL_INFILE, true);
    $local_infile = 'SET GLOBAL local_infile=1';

    if ($mysqli->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 

    if ($result = $mysqli->query($local_infile)){
        // echo "local file is set \n";
    }else{
        echo $mysqli->error;
    }

    
    // Loading movies
    $result = $mysqli->query("SELECT * FROM `movies` LIMIT 1");
    if ($result && $result->num_rows > 0) {
        // echo "Table 'movies' is not empty. No need to load data.\n";
    } else {
        $loadDataSQL = "LOAD DATA LOCAL INFILE 'Data/movies_split.txt'
                        INTO TABLE `movies`
                        FIELDS TERMINATED BY '|'
                        LINES TERMINATED BY '\n'
                        IGNORE 1 LINES
                        (movieID, title, release_year, original_language, runtime, overview, poster_URL, box_office, budget, tmdb_popularity, imdb_rating, imdb_rating_votes)
                        SET original_language = NULLIF(original_language, ''), 
                        runtime = NULLIF(runtime, ''), 
                        overview = NULLIF(overview, ''), 
                        poster_URL = NULLIF(poster_URL, ''), 
                        box_office = NULLIF(box_office, ''), 
                        budget = NULLIF(budget, ''), 
                        tmdb_popularity = NULLIF(tmdb_popularity, ''), 
                        imdb_rating = NULLIF(imdb_rating, ''), 
                        imdb_rating_votes = NULLIF(imdb_rating_votes, '')";


        // Execute the SQL statement
        if ($mysqli->query($loadDataSQL) === TRUE) {
            // echo "movies_split.txt loaded successfully.\n";
        } else {
            echo "Error loading data: " . $mysqli->error;
        }
    }
    
    // Table with no FOREIGN KEY
    // Loading rating_users
    $result = $mysqli->query("SELECT * FROM `rating_users` LIMIT 1");
    if ($result && $result->num_rows > 0) {
        // echo "Table 'rating_users' is not empty. No need to load data.\n";
    } else {
        // $loadDataSQL = "LOAD DATA LOCAL INFILE 'Data/rating_users.csv'
        $loadDataSQL = "LOAD DATA LOCAL INFILE 'Data/rating_users.csv'
                        INTO TABLE `rating_users`
                        FIELDS TERMINATED BY ','
                        LINES TERMINATED BY '\n'
                        IGNORE 1 LINES";

        // Execute the SQL statement
        if ($mysqli->query($loadDataSQL) === TRUE) {
            // echo "rating_users.csv loaded successfully.\n";
        } else {
            echo "Error loading data: " . $mysqli->error;
        }
    }

    // Loading tags
    $result = $mysqli->query("SELECT * FROM `tags` LIMIT 1");
    if ($result && $result->num_rows > 0) {
        // echo "Table 'tags' is not empty. No need to load data.\n";
    } else {
        $loadDataSQL = "LOAD DATA LOCAL INFILE 'Data/tags.csv'
                        INTO TABLE `tags`
                        FIELDS TERMINATED BY ','
                        LINES TERMINATED BY '\n'
                        IGNORE 1 LINES
                        (tagID, tag)
                        SET tag = NULLIF(tag, '')";

        // Execute the SQL statement
        if ($mysqli->query($loadDataSQL) === TRUE) {
            // echo "tags.csv loaded successfully.\n";
        } else {
            echo "Error loading data: " . $mysqli->error;
        }
    }

    // Loading crew_occupation
    $result = $mysqli->query("SELECT * FROM `crew_occupation` LIMIT 1");
    if ($result && $result->num_rows > 0) {
        // echo "Table 'crew_occupation' is not empty. No need to load data.\n";
    } else {
        $loadDataSQL = "LOAD DATA LOCAL INFILE 'Data/crew_occupation.csv'
                        INTO TABLE `crew_occupation`
                        FIELDS TERMINATED BY ','
                        LINES TERMINATED BY '\n'
                        IGNORE 1 LINES
                        (occupationID, occupationName)";

        // Execute the SQL statement
        if ($mysqli->query($loadDataSQL) === TRUE) {
            // echo "crew_occupation.csv loaded successfully.\n";
        } else {
            echo "Error loading data: " . $mysqli->error;
        }
    }

    // Loading crew
    $result = $mysqli->query("SELECT * FROM `crew` LIMIT 1");
    if ($result && $result->num_rows > 0) {
        // echo "Table 'crew' is not empty. No need to load data.\n";
    } else {
        $loadDataSQL = "LOAD DATA LOCAL INFILE 'Data/crew.csv'
                        INTO TABLE `crew`
                        FIELDS TERMINATED BY ','
                        LINES TERMINATED BY '\n'
                        IGNORE 1 LINES
                        (crewID, Name, birth_year, end_year)
                        SET end_year = NULLIF(end_year, ''), birth_year = NULLIF(birth_year, '')";

        // Execute the SQL statement
        if ($mysqli->query($loadDataSQL) === TRUE) {
            // echo "crew.csv loaded successfully.\n";
        } else {
            echo "Error loading data: " . $mysqli->error;
        }
    }

    // Loading production_companies
    $result = $mysqli->query("SELECT * FROM `production_companies` LIMIT 1");
    if ($result && $result->num_rows > 0) {
        // echo "Table 'production_companies' is not empty. No need to load data.\n";
    } else {
        $loadDataSQL = "LOAD DATA LOCAL INFILE 'Data/production_companies.csv'
                        INTO TABLE `production_companies`
                        FIELDS TERMINATED BY ','
                        LINES TERMINATED BY '\n'
                        IGNORE 1 LINES
                        (companyID,companyName)";

        // Execute the SQL statement
        if ($mysqli->query($loadDataSQL) === TRUE) {
            // echo "production_companies.csv loaded successfully.\n";
        } else {
            echo "Error loading data: " . $mysqli->error;
        }
    }

    // Loading genre
    $result = $mysqli->query("SELECT * FROM `genre` LIMIT 1");
    if ($result && $result->num_rows > 0) {
        // echo "Table 'genre' is not empty. No need to load data.\n";
    } else {
        $loadDataSQL = "LOAD DATA LOCAL INFILE 'Data/genre.csv'
                        INTO TABLE `genre`
                        FIELDS TERMINATED BY ','
                        LINES TERMINATED BY '\n'
                        IGNORE 1 LINES
                        (genreID, genreName)";

        // Execute the SQL statement
        if ($mysqli->query($loadDataSQL) === TRUE) {
            // echo "genre.csv loaded successfully.\n";
        } else {
            echo "Error loading data: " . $mysqli->error;
        }
    }

    // Loading production_countries
    $result = $mysqli->query("SELECT * FROM `production_countries` LIMIT 1");
    if ($result && $result->num_rows > 0) {
        // echo "Table 'production_countries' is not empty. No need to load data.\n";
    } else {
        $loadDataSQL = "LOAD DATA LOCAL INFILE 'Data/production_countries.csv'
                        INTO TABLE `production_countries`
                        FIELDS TERMINATED BY ','
                        LINES TERMINATED BY '\n'
                        IGNORE 1 LINES
                        (countryID, countryName)";

        // Execute the SQL statement
        if ($mysqli->query($loadDataSQL) === TRUE) {
            // echo "production_countries.csv loaded successfully.\n";
        } else {
            echo "Error loading data: " . $mysqli->error;
        }
    }



    // Table with FOREIGN KEY
    // Loading movie_countries
    $result = $mysqli->query("SELECT * FROM `movie_countries` LIMIT 1");
    if ($result && $result->num_rows > 0) {
        // echo "Table 'movie_countries' is not empty. No need to load data.\n";
    } else {
        $loadDataSQL = "LOAD DATA LOCAL INFILE 'Data/movie_countries.csv'
                        INTO TABLE `movie_countries`
                        FIELDS TERMINATED BY ','
                        LINES TERMINATED BY '\n'
                        IGNORE 1 LINES
                        (movieID, countryID)";
        
        // Execute the SQL statement
        if ($mysqli->query($loadDataSQL) === TRUE) {
            // echo "movie_countries.csv loaded successfully.\n";
        } else {
            echo "Error loading data: " . $mysqli->error;
        }
    }

    // Loading movie_genre
    $result = $mysqli->query("SELECT * FROM `movie_genre` LIMIT 1");
    if ($result && $result->num_rows > 0) {
        // echo "Table 'movie_genre' is not empty. No need to load data.\n";
    } else {
        $loadDataSQL = "LOAD DATA LOCAL INFILE 'Data/movie_genre.csv'
                        INTO TABLE `movie_genre`
                        FIELDS TERMINATED BY ','
                        LINES TERMINATED BY '\n'
                        IGNORE 1 LINES
                        (movieID, genreID)";
        
        // Execute the SQL statement
        if ($mysqli->query($loadDataSQL) === TRUE) {
            // echo "movie_genre.csv loaded successfully.\n";
        } else {
            echo "Error loading data: " . $mysqli->error;
        }
    }

    // Loading movie_production_companies
    $result = $mysqli->query("SELECT * FROM `movie_production_companies` LIMIT 1");
    if ($result && $result->num_rows > 0) {
        // echo "Table 'movie_production_companies' is not empty. No need to load data.\n";
    } else {
        $loadDataSQL = "LOAD DATA LOCAL INFILE 'Data/movie_production_companies.csv'
                        INTO TABLE `movie_production_companies`
                        FIELDS TERMINATED BY ','
                        LINES TERMINATED BY '\n'
                        IGNORE 1 LINES
                        (movieID, companyID)";
        
        // Execute the SQL statement
        if ($mysqli->query($loadDataSQL) === TRUE) {
            // echo "movie_production_companies.csv loaded successfully.\n";
        } else {
            echo "Error loading data: " . $mysqli->error;
        }
    }

    // Loading movie_tags
    $result = $mysqli->query("SELECT * FROM `movie_tags` LIMIT 1");
    if ($result && $result->num_rows > 0) {
        // echo "Table 'movie_tags' is not empty. No need to load data.\n";
    } else {
        $loadDataSQL = "LOAD DATA LOCAL INFILE 'Data/movie_tags.csv'
                        INTO TABLE `movie_tags`
                        FIELDS TERMINATED BY ','
                        LINES TERMINATED BY '\n'
                        IGNORE 1 LINES
                        (movieID, tagID)";
        
        // Execute the SQL statement
        if ($mysqli->query($loadDataSQL) === TRUE) {
            // echo "movie_tags.csv loaded successfully.\n";
        } else {
            echo "Error loading data: " . $mysqli->error;
        }
    }

    // Loading movie_crew
    $result = $mysqli->query("SELECT * FROM `movie_crew` LIMIT 1");
    if ($result && $result->num_rows > 0) {
        // echo "Table 'movie_crew' is not empty. No need to load data.\n";
    } else {
        $loadDataSQL = "LOAD DATA LOCAL INFILE 'Data/movie_crew.csv'
                        INTO TABLE `movie_crew`
                        FIELDS TERMINATED BY ','
                        LINES TERMINATED BY '\n'
                        IGNORE 1 LINES
                        (movieID, crewID, occupationID, characters)
                        SET characters = NULLIF(characters, '')";
        
        // Execute the SQL statement
        if ($mysqli->query($loadDataSQL) === TRUE) {
            // echo "movie_crew.csv loaded successfully.\n";
        } else {
            echo "Error loading data: " . $mysqli->error;
        }
    }

    // Loading ratings
    $result = $mysqli->query("SELECT * FROM `ratings` LIMIT 1");
    if ($result && $result->num_rows > 0) {
        // echo "Table 'ratings' is not empty. No need to load data.\n";
    } else {
        $loadDataSQL = "LOAD DATA LOCAL INFILE 'Data/ratings.csv'
                        INTO TABLE `ratings`
                        FIELDS TERMINATED BY ','
                        LINES TERMINATED BY '\n'
                        IGNORE 1 LINES
                        (rating_userID, movieID, rating, timestamp)";
        
        // Execute the SQL statement
        if ($mysqli->query($loadDataSQL) === TRUE) {
            // echo "ratings.csv loaded successfully.\n";
        } else {
            echo "Error loading data: " . $mysqli->error;
        }
    }

?>
