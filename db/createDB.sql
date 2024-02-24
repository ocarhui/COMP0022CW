CREATE DATABASE movie_database;

CREATE TABLE `movie_database`.`users` ( 
    `id` INT NOT NULL AUTO_INCREMENT , 
    `username` VARCHAR(50) NOT NULL , 
    `email` VARCHAR(100) NOT NULL , 
    `password` VARCHAR(255) NOT NULL , 
    PRIMARY KEY (`id`)
) ENGINE = InnoDB;

CREATE TABLE `movie_database`.`movie_db` ( 
    `id` INT NOT NULL AUTO_INCREMENT , 
    `name` VARCHAR(50) NOT NULL , 
    `year` VARCHAR(100) NOT NULL , 
    `country` VARCHAR(255) NOT NULL , 
    PRIMARY KEY (`id`)
) ENGINE = InnoDB;

INSERT INTO `movie_database`.`movie_db` (`name`, `year`, `country`) VALUES ('movie1', '2018', 'CN');
INSERT INTO `movie_database`.`movie_db` (`name`, `year`, `country`) VALUES ('movie2', '2021', 'UK');
INSERT INTO `movie_database`.`movie_db` (`name`, `year`, `country`) VALUES ('movie3', '2021', 'US');
INSERT INTO `movie_database`.`movie_db` (`name`, `year`, `country`) VALUES ('movie4', '2024', 'CN');
INSERT INTO `movie_database`.`movie_db` (`name`, `year`, `country`) VALUES ('movie5', '2025', 'CN');
INSERT INTO `movie_database`.`movie_db` (`name`, `year`, `country`) VALUES ('movie6', '2025', 'CN');
INSERT INTO `movie_database`.`movie_db` (`name`, `year`, `country`) VALUES ('movie7', '2026', 'CN');

INSERT INTO `movie_database`.`users` (`username`, `email`, `password`) VALUES ('a', '1463855272@qq.com', '$2y$10$z7nXkEABKtjHbjvFG3TDo.qj7M9jw0CzWRuHC8xyvztg1FbYnblXC');

CREATE USER 'movieadmin' IDENTIFIED BY 'secretpassword';
GRANT INSERT, SELECT ON `movie_database`.* TO 'movieadmin' WITH GRANT OPTION;
