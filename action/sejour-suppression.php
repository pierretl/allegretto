<?php 

session_start();

include 'function/securite-action.php';
include 'function/DotEnv.php';
include 'function/json-manipulation.php';
include 'function/dev.php';

// Charge les variables d'environnement
(new DotEnv('../.env'))->load();

$jsonSejour = "../".getenv('DATA_SEJOUR');

//page de redirection
$pageRedirection = isset($_POST['page']) ? $_POST['page'] : 'dashboard';

//recupère la date d'ajout du séjour a supprimer
$sejourDataAjout = urldecode($_GET['dataAjout']);

//liste des séjours
$data = getDataJson($jsonSejour);

//créer une nouvelle liste sans l'entrée correspondant à $sejourDataAjout
//donc supprime le séjour
$dataUpdate = [];
for ($i=0; $i < count($data); $i++) {
    if ($data[$i]['dataAjout'] != $sejourDataAjout  ) {
        array_push($dataUpdate, $data[$i]); 
    }
}

//met a jour le json
updateJason($jsonSejour, $dataUpdate);

//redirige sur la page
header("location:../index.php?p=".$pageRedirection);


exit();