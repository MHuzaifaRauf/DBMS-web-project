<?php
require_once "utils/protected.php";
require_once "utils/data.php";

protectManagerPage();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="images/favicon.png">
    <title>Dasboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />

    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/dashboard.css" />

</head>

<body class="dark-theme">
    <?php require_once 'components/Nav.php'; ?>
    <?php require_once 'components/sidebar.php';
    ?>
    <br>
    <div class="content">
        <h2 class='heading'>STRIVING FOR EXELLENCE</h2>
        <br>
        <div class="details">
            <?php


            foreach ($tables as $table) {
                // echo '<div class="card">';
                echo '<a class="card" href="' . $table['link'] . '"><i class="' . $table['icon'] . '"></i>' . strtoupper($table['name']) . '</a>';
                // echo '</div>';
            }
            ?>
        </div>
    </div>

</body>

</html>