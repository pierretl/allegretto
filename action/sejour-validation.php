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
$sejourDataAjout = urldecode($_GET['dataAjout']);
$famille = $_SESSION['utilisateur']['famille'];

//liste des séjours
$data = getDataJson($jsonSejour);

//On récupère les datas du séjour a validé
$dataDuSejour = [];
for ($i=0; $i < count($data); $i++) {
    if ($data[$i]['dataAjout'] == $sejourDataAjout  ) {
        array_push($dataDuSejour, $data[$i]); 
    }
}

// si pas de data, redirection
if ($dataDuSejour === []) {
    header("location:../index.php?p=".$pageRedirection);
    exit;
}

// récupére la key coorespondent à la famille dans le tableau validation
$keyFamilleInValidation = array_search($famille,array_column($dataDuSejour[0]['validation'], 'id'));

// Ajoute l'heure actuel 
$dataDuSejour[0]['validation'][$keyFamilleInValidation]['accord'] = date("Y-m-d H:i:s");

//vérifie si toute les familles on donnée leur accord
$all_accord = true;
for ($i=0; $i < count($dataDuSejour[0]['validation']); $i++) {
    if ( $dataDuSejour[0]['validation'][$i]['accord'] == '' ) {
        $all_accord = false;
    }
}
if ($all_accord === true) {
    $dataDuSejour[0]['etat'] = getenv('CODE_SEJOUR_VALIDE');//change l'état du séjour
    $dataDuSejour[0]['backgroundColor'] = getenv('CALENDRIER_BGCOLOR-VALIDE');//passe la couleur du séjour en validé
} 

//créer une nouvelle liste sans l'entrée correspondant au séjour a valider
$tousLesAutreSejour = [];
for ($i=0; $i < count($data); $i++) {
    if ($data[$i]['dataAjout'] != $sejourDataAjout  ) {
        array_push($tousLesAutreSejour, $data[$i]); 
    }
}

//fusionne les tableaux
$dataUpdate = array_merge($tousLesAutreSejour, $dataDuSejour);

//met a jour le json
updateJason($jsonSejour, $dataUpdate);

header("location:../index.php?p=".$pageRedirection);

exit;