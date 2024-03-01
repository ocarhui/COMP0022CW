<?php
// index.php

require 'data_processing.php';

if (isset($_POST['submit'])) {
    $selected_countries = $_POST['selected_countries'] ?? [];
    $result = processData($selected_countries, $connection);
}

?>


<?php if (isset($result) && $result->num_rows > 0): ?>
    <div class="selected-record">
        <hr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="movie-details">
                <strong><?= $row['name'] ?></strong><br>
                <span>Year: <?= $row['year'] ?></span><br>
                <span>Country: <?= $row['country'] ?></span>
                <hr>
            </div>
        <?php endwhile ?>
    </div>
<?php elseif (isset($result)): ?>
    <p>No record found for the selected country.</p>
<?php endif ?>
