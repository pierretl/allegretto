<?php 

session_start();

include 'function/securite-action-admin.php';
include 'function/DotEnv.php';
include 'function/json-manipulation.php';
include 'function/dev.php';

// Charge les variables d'environnement
(new DotEnv('../.env'))->load();

$jsonSejour = "../".getenv('DATA_SEJOUR');

//recupère la key du séjour a supprimer
$sejourKey = $_GET['key'] - 1; // -1 car la loop de twig commence à 1

//liste des utilisateurs
$data = getDataJson($jsonSejour);

// supprime le séjour
unset($data[$sejourKey]);

// re-index le json
$dataReIndex = array_values($data);

//met a jour le json
updateJason($jsonSejour, $dataReIndex);

//redirige sur la page
header("location:../index.php?p=sejour");


exit();