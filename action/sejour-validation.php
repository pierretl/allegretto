<?php

session_start();

//recupère les valeurs nécessaire
$key = $_GET['key'];
$famille = $_SESSION['utilisateur']['famille'];



// a faire
$test = "&date=". date("Y-m-d H:i:s");


header("location:../index.php?p=sejour".$test);

exit;