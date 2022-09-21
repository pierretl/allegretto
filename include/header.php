<?php

if (isset($securite)) {
    include 'securite.php';
}
include 'dev.php'; 

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php
        if (!isset($title)) {
            $title = "Allegretto";
        }
    ?>
    <title><?php echo $title ?></title>
    <link rel="stylesheet" href="css/style.css" />
    <link rel="icon" type="image/png" sizes="16x16" href="design/favicon.svg">
</head>

<body>