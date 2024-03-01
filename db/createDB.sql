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
    `original_language` VARCHAR(2),
    `runtime` SMALLINT,
    `overview` TEXT,
    `poster_URL` VARCHAR(255),
    `box_office` INT,
    `budget` INT,
    `tmdb_popularity` FLOAT(6,3),
    `imdb_rating` FLOAT(3,1),
    `imdb_rating_votes` INT
    PRIMARY KEY (`movieID`),
    FOREIGN KEY (`production_country_id`) REFERENCES `production_countries`(`countryID`)
) ENGINE = InnoDB;

CREATE TABLE `movie_database`.`countries` {
    `production_country_id` CHAR NOT NULL,
    `country_name` VARCHAR(50) NOT NULL,
    PRIMARY KEY (`production_country_id`)
} ENGINE = InnoDB;

CREATE TABLE `movie_database`.`movie_countries` {
    `movieID` INT NOT NULL,
    `production_country_id` CHAR NOT NULL,
    PRIMARY KEY (`movieID`, `production_country_id`),
    FOREIGN KEY (`movieID`) REFERENCES `movies`(`movieID`),
    FOREIGN KEY (`production_country_id`) REFERENCES `countries`(`production_country_id`)
} ENGINE = InnoDB;

CREATE TABLE `movie_database`.`genre` ( 
    `genreID` VARCHAR NOT NULL, 
    `genreName` VARCHAR(50) NOT NULL, 
    PRIMARY KEY (`genreID`)
) ENGINE = InnoDB;

CREATE TABLE `movie_database`.`movie_genre` ( 
    `movieID` INT NOT NULL , 
    `genreID` INT NOT NULL , 
    PRIMARY KEY (`movieID`, `genreID`)
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
    PRIMARY KEY (`movieID`, `companyID`)
    FOREIGN KEY (`movieID`) REFERENCES `movies`(`movieID`),
    FOREIGN KEY (`companyID`) REFERENCES `production_companies`(`companyID`)
) ENGINE = InnoDB;

CREATE TABLE `movie_database`.`crew` {
    `crewID` INT NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `birth_year` YEAR,
    `end_year` YEAR,
    PRIMARY KEY (`crewID`)
} ENGINE = InnoDB;

CREATE TABLE `movie_database`.`crew_occupation` {
    `occupationID` INT NOT NULL,
    `occupationName` VARCHAR(50) NOT NULL,
    PRIMARY KEY (`occupationID`)
} ENGINE = InnoDB;

CREATE TABLE `movie_database`.`movie_crew` {
    `movieID` INT NOT NULL,
    `crewID` INT NOT NULL,
    `occupationID` INT NOT NULL,
    PRIMARY KEY (`movieID`, `crewID`, `occupationID`),
    FOREIGN KEY (`movieID`) REFERENCES `movies`(`movieID`),
    FOREIGN KEY (`crewID`) REFERENCES `crew`(`crewID`),
    FOREIGN KEY (`occupationID`) REFERENCES `crew_occupation`(`occupationID`)
} ENGINE = InnoDB;

CREATE TABLE `movie_database`.`ratings` {
    `ratingID` INT NOT NULL,
    `movieID` INT NOT NULL,
    `rating-userID` INT NOT NULL,
    `rating` INT NOT NULL,
    `timestamp` TIMESTAMP NOT NULL,
    PRIMARY KEY (`ratingID`),
    FOREIGN KEY (`movieID`) REFERENCES `movies`(`movieID`),
    FOREIGN KEY (`userID`) REFERENCES `rating-users`(`id`)
} ENGINE = InnoDB;

CREATE TABLE `movie_database`.`rating-users` {
    `rating-userID` INT NOT NULL
    PRIMARY KEY (`userID`)
} ENGINE = InnoDB;

CREATE TABLE `movie_database`.`tags` {
    `tagID` INT NOT NULL,
    `tag` VARCHAR(50) NOT NULL,
    PRIMARY KEY (`tagID`)
} ENGINE = InnoDB;

CREATE TABLE `movie_database`.`movie_tags` {
    `movieID` INT NOT NULL,
    `userID` INT NOT NULL,
    `tagID` INT NOT NULL,
    `timestamp` TIMESTAMP NOT NULL,
    PRIMARY KEY (`movieID`, `userID`, `tagID`),
    FOREIGN KEY (`movieID`) REFERENCES `movies`(`movieID`),
    FOREIGN KEY (`userID`) REFERENCES `rating-users`(`id`),
    FOREIGN KEY (`tagID`) REFERENCES `tags`(`tagID`)
} ENGINE = InnoDB;

INSERT INTO `movie_database`.`users` (`username`, `email`, `password`) VALUES ('a', '1463855272@qq.com', '$2y$10$z7nXkEABKtjHbjvFG3TDo.qj7M9jw0CzWRuHC8xyvztg1FbYnblXC');

CREATE USER 'movieadmin' IDENTIFIED BY 'secretpassword';
GRANT INSERT, SELECT ON `movie_database`.* TO 'movieadmin' WITH GRANT OPTION;


