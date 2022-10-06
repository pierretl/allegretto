<?php 

session_start();

include 'function/securite-action-admin.php';
include 'function/DotEnv.php';
include 'function/dev.php';
include 'function/json-manipulation.php';

// Charge les variables d'environnement
(new DotEnv('../.env'))->load();

$jsonFamille = "../".getenv('DATA_FAMILLE');
$jsonSejour = "../".getenv('DATA_SEJOUR');

//recupère la code de la famille a supprimer
$familleKey = $_GET['key'] - 1; // -1 car la loop de twig commence à 1

//chargement des données
$dataFamille = getDataJson($jsonFamille);
$dataSejour = getDataJson($jsonSejour);

// maj famille
unset($dataFamille[$familleKey]);                   // supprimer la famille
$dataFamilleReIndex = array_values($dataFamille);   // re-index le json
updateJason($jsonFamille, $dataFamilleReIndex);     //met a jour le json

// maj sejour
for ($i=0; $i < count($dataSejour); $i++) {
    unset($dataSejour[$i]['validation'][$familleKey]);                      // supprimer la famille
    $dataValidationReIndex = array_values($dataSejour[$i]['validation']);   // re-index le json
    $dataSejour[$i]['validation'] = $dataValidationReIndex;                 // update le tableau validation de chaque sejour
    updateJason($jsonSejour, $dataSejour);                                  //met a jour le json
}

//redirige sur la page admin
header("location:../index.php?p=admin");
exit;