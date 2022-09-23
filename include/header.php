<?php

if (isset($securite)) {
    include 'securite.php';
}
include 'dev.php'; 

if ( $title !== "Connexion") {
    $vague = true;
}

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

<?php if ( $vague === true) { ?>

    <body class="bg-3 vague df fd-c">

        <div class="vague__forme">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path d="M1200,25.8V1.6L0,1.6l0,92.7c102.5-38.9,213.6-48.9,321.4-29.1c58,10.8,114.2,30.1,172,41.9c82.4,16.7,168.2,17.7,250.5,0.4c79.9-16.8,162.8-57.8,241.8-78.6C1055.7,10.3,1132.2,2.7,1200,25.8z" class="vague__fill"></path>
            </svg>
        </div>

        <div class="page df fd-c fg-1">

            <div class="df fd-c--medium ai-c mb-1 header">
                <img src="design/logo.svg" alt="" loading="lazy" class="logo">
                <h1 class="m-0"><?php echo $title ?></h1>
            </div>

<?php } else { ?>

    <body class="v_centre">

<?php } ?>