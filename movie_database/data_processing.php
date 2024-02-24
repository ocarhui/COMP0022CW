<?php
// data_processing.php

require 'database.php';

function processData($selected_countries, $connection) {
    if (!empty($selected_countries)) {
        $placeholders = rtrim(str_repeat('?,', count($selected_countries)), ',');
        $query = "SELECT * FROM movie_db WHERE country IN ($placeholders)";
        $stmt = $connection->prepare($query);
        $types = str_repeat('s', count($selected_countries));
        $stmt->bind_param($types, ...$selected_countries);
    } else {
        $query = "SELECT * FROM movie_db";
        $stmt = $connection->prepare($query);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    return $result;
}
