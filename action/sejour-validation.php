<?php

session_start();

include 'function/securite-action.php';
include 'function/DotEnv.php';
include 'function/dev.php';
include 'function/json-manipulation.php';

// Charge les variables d'environnement
(new DotEnv('../.env'))->load();


$jsonSejour = "../".getenv('DATA_SEJOUR');

//recupère les valeurs nécessaire
$pageRedirection = isset($_POST['page']) ? $_POST['page'] : 'dashboard';
$key = $_GET['key'] - 1; // -1 car la loop de twig commence à 1
$famille = $_SESSION['utilisateur']['famille'];

//liste des séjours
$data = getDataJson($jsonSejour);

// Vérifie si la key existe pas dans les datas
if (!isset($data[$key])) {
    header("location:../index.php?p=".$pageRedirection);
    exit;
} 

// récupére la key coorespondent à la famille dans le tableau validation
$keyFamilleInValidation = array_search($famille,array_column($data[$key]['validation'], 'id'));

// Ajoute l'heure actuel 
$data[$key]['validation'][$keyFamilleInValidation]['accord'] = date("Y-m-d H:i:s");

//vérifie si toute les familles on donnée leur accord
$all_accord = true;
for ($i=0; $i < count($data[$key]['validation']); $i++) {
    if ( $data[$key]['validation'][$i]['accord'] == '' ) {
        $all_accord = false;
    }
}
if ($all_accord === true) {
    $data[$key]['etat'] = getenv('CODE_SEJOUR_VALIDE');//change l'état du séjour
    $data[$key]['backgroundColor'] = getenv('CALENDRIER_BGCOLOR-VALIDE');//passe la couleur du séjour en validé
} 

//met a jour le json
updateJason($jsonSejour, $data);

header("location:../index.php?p=".$pageRedirection);

exit;