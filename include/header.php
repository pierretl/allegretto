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

    <body class="vague">

        <div class="vague__forme">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path d="M985.66,92.83C906.67,72,823.78,31,743.84,14.19c-82.26-17.34-168.06-16.33-250.45.39-57.84,11.73-114,31.07-172,41.86A600.21,600.21,0,0,1,0,27.35V120H1200V95.8C1132.19,118.92,1055.71,111.31,985.66,92.83Z" class="vague__fill"></path>
            </svg>
        </div>

        <div class="text-center">
            <img src="design/logo.svg" alt="" loading="lazy" width="300px">
		    <h1 class="mt-0"><?php echo $title ?></h1>
        </div>

<?php } else { ?>

    <body class="v_centre">

<?php } ?>