CREATE DATABASE movie_database;

CREATE TABLE `movie_database`.`users` ( 
    `id` INT NOT NULL AUTO_INCREMENT , 
    `username` VARCHAR(50) NOT NULL , 
    `email` VARCHAR(100) NOT NULL , 
    `password` VARCHAR(255) NOT NULL , 
    PRIMARY KEY (`id`)
) ENGINE = InnoDB;

CREATE TABLE `movie_database`.`movies` ( 
    `movieID` INT NOT NULL,
    `title` VARCHAR(255) NOT NULL,
    `release_year` YEAR,
    `original_language` VARCHAR(2),
    `runtime` SMALLINT,
    `overview` TEXT,
    `poster_URL` VARCHAR(255),
    `box_office` INT,
    `budget` INT,
    `tmdb_popularity` FLOAT(6,3),
    `imdb_rating` FLOAT(3,1),
    `imdb_rating_votes` INT,
    PRIMARY KEY (`movieID`)
) ENGINE = InnoDB;

CREATE TABLE `movie_database`.`production_countries` ( 
    `countryID` VARCHAR(2) NOT NULL , 
    `countryName` VARCHAR(50) NOT NULL , 
    PRIMARY KEY (`countryID`)
) ENGINE = InnoDB;


CREATE TABLE `movie_database`.`movie_countries` (
    `movieID` INT NOT NULL,
    `countryID` VARCHAR(2) NOT NULL,
    PRIMARY KEY (`movieID`, `countryID`),
    FOREIGN KEY (`movieID`) REFERENCES `movies`(`movieID`),
    FOREIGN KEY (`countryID`) REFERENCES `production_countries`(`countryID`)
 ) ENGINE = InnoDB;

 CREATE TABLE `movie_database`.`genre` ( 
    `genreID` INT NOT NULL, 
    `genreName` VARCHAR(50) NOT NULL, 
    PRIMARY KEY (`genreID`)
) ENGINE = InnoDB;

CREATE TABLE `movie_database`.`movie_genre` ( 
    `movieID` INT NOT NULL , 
    `genreID` INT NOT NULL , 
    PRIMARY KEY (`movieID`, `genreID`),
    FOREIGN KEY (`movieID`) REFERENCES `movies`(`movieID`),
    FOREIGN KEY (`genreID`) REFERENCES `genre`(`genreID`)
) ENGINE = InnoDB;

CREATE TABLE `movie_database`.`production_companies` ( 
    `companyID` INT NOT NULL , 
    `companyName` VARCHAR(100) NOT NULL , 
    PRIMARY KEY (`companyID`)
) ENGINE = InnoDB;

CREATE TABLE `movie_database`.`movie_production_companies` ( 
    `movieID` INT NOT NULL , 
    `companyID` INT NOT NULL , 
    PRIMARY KEY (`movieID`, `companyID`),
    FOREIGN KEY (`movieID`) REFERENCES `movies`(`movieID`),
    FOREIGN KEY (`companyID`) REFERENCES `production_companies`(`companyID`)
) ENGINE = InnoDB;

CREATE TABLE `movie_database`.`crew` (
    `crewID` VARCHAR(9) NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `birth_year` YEAR,
    `end_year` YEAR,
    PRIMARY KEY (`crewID`)
 ) ENGINE = InnoDB;

CREATE TABLE `movie_database`.`crew_occupation` (
    `occupationID` INT NOT NULL,
    `occupationName` VARCHAR(50) NOT NULL,
    PRIMARY KEY (`occupationID`)
 ) ENGINE = InnoDB;

CREATE TABLE `movie_database`.`movie_crew` (
    `movieID` INT NOT NULL,
    `crewID` VARCHAR(9) NOT NULL,
    `occupationID` INT NOT NULL,
    `characters` VARCHAR(255),
    PRIMARY KEY (`movieID`, `crewID`, `occupationID`),
    FOREIGN KEY (`movieID`) REFERENCES `movies`(`movieID`),
    FOREIGN KEY (`crewID`) REFERENCES `crew`(`crewID`),
    FOREIGN KEY (`occupationID`) REFERENCES `crew_occupation`(`occupationID`)
) ENGINE = InnoDB;

CREATE TABLE `movie_database`.`rating_users` (
    `rating_userID` VARCHAR(32) NOT NULL,
    PRIMARY KEY (`rating_userID`)
 ) ENGINE = InnoDB;

CREATE TABLE `movie_database`.`ratings` (
    `rating_userID` VARCHAR(32) NOT NULL,
    `movieID` INT NOT NULL,
    `rating` INT NOT NULL,
    `timestamp` timestamp NOT NULL,
    PRIMARY KEY (`movieID`, `rating_userID`),
    FOREIGN KEY (`movieID`) REFERENCES `movies`(`movieID`),
    FOREIGN KEY (`rating_userID`) REFERENCES `rating_users`(`rating_userID`)
) ENGINE = InnoDB;

CREATE TABLE `movie_database`.`tags` (
    `tagID` INT NOT NULL,
    `tag` VARCHAR(50) NOT NULL,
    PRIMARY KEY (`tagID`)
) ENGINE = InnoDB;

CREATE TABLE `movie_database`.`movie_tags` (
    `movieID` INT NOT NULL,
    `tagID` INT NOT NULL,
    PRIMARY KEY (`movieID`, `tagID`),
    FOREIGN KEY (`movieID`) REFERENCES `movies`(`movieID`),
    FOREIGN KEY (`tagID`) REFERENCES `tags`(`tagID`)
 ) ENGINE = InnoDB;

CREATE TABLE `movie_database`.`personality` (
    `rating_userID` VARCHAR(32) NOT NULL,
    `agreeableness` FLOAT(3,2),
    `emotional_stability` FLOAT(3,2),
    `conscientiousness` FLOAT(3,2),
    `extraversion` FLOAT(3,2),
    `assigned_metric` VARCHAR(50),
    `assigned_condition` VARCHAR(50), 
    `is_persoanlised` INT NOT NULL,
    `enjoy_watching` INT NOT NULL,
    PRIMARY KEY (`rating_userID`),
    FOREIGN KEY (`rating_userID`) REFERENCES `rating_users`(`rating_userID`) 
    FOREIGN KEY (`is_persoanlised`) REFERENCES `personality_movie_prediction`(`movie_order`),
    FOREIGN KEY (`enjoy_watching`) REFERENCES `personality_movie_prediction`(`movie_order`)
) ENGINE = InnoDB;

CREATE TABLE `movie_database`.`personality_movie_prediction` (
    `rating_userID` VARCHAR(32) NOT NULL,
    `movie_order` INT NOT NULL,
    `movieID` INT NOT NULL,
    `predicted_rating` FLOAT(13,2),
    PRIMARY KEY (`rating_userID`, `movie_order`),
    FOREIGN KEY (`rating_userID`) REFERENCES `rating_users`(`rating_userID`),
    FOREIGN KEY (`movieID`) REFERENCES `movies`(`movieID`)
) ENGINE = InnoDB;

INSERT INTO `movie_database`.`users` (`username`, `email`, `password`) VALUES ('a', '1463855272@qq.com', '$2y$10$z7nXkEABKtjHbjvFG3TDo.qj7M9jw0CzWRuHC8xyvztg1FbYnblXC');

CREATE USER 'movieadmin' IDENTIFIED BY 'secretpassword';
GRANT INSERT, SELECT ON `movie_database`.* TO 'movieadmin' WITH GRANT OPTION;
GRANT SUPER ON *.* TO 'movieadmin';