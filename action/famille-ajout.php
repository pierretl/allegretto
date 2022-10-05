<?php 

session_start();

include 'function/securite-action-admin.php';
include 'function/DotEnv.php';
include 'function/dev.php';
include 'function/json-manipulation.php';
include 'function/securite-saisie.php';

// Charge les variables d'environnement
(new DotEnv('../.env'))->load();

$jsonFamille = "../".getenv('DATA_FAMILLE');
$jsonSejour = "../".getenv('DATA_SEJOUR');

//recupère les valeur saisi
$id = $_POST['id'];
$label = $_POST['label'];

//récupére la liste des familles
$listeFamille = getDataJson($jsonFamille);
$listeSejour = getDataJson($jsonSejour);

// Stock les données des familles
$familles= [];
for ($i=0; $i < count($listeFamille); $i++) {
    $familles += [ 
        $listeFamille[$i]['label'] => $listeFamille[$i]['id']
    ];
}


if ( 
    empty($id) || 
    empty($label) ||
    array_search($id, $familles) !== false // le code saisi correspond un code existant
) {

    // Erreur :

    if (empty($id)) {
        $input1 = "&erreurFam1=1";
    } else {
        $input1 = "&id=".securite_saisi($id);
    }

    if (empty($label)) {
        $input2 = "&erreurFam2=1";
    } else {
        $input2 = "&label=".securite_saisi($label);
    }

    if (array_search($id, $familles) !== false) {
        $input1 = "&erreurId=1";
    }

    header("location:../index.php?p=admin".$input1."".$input2);

} else {

    // Succès :

    // Ajout la famille dans le tableau "validation" de chaque séjour
    for ($i=0; $i < count($listeSejour); $i++) {
        $listeSejour[$i]['validation'][count($listeSejour[$i]['validation'])] = array(
            "id" => $id,
            "accord" => ""
        );
    }
    updateJason($jsonSejour, $listeSejour);
    


    // Ajoute la famille dans le json
    $listeFamille[count($listeFamille)] = array(
        "id" => $id,
        "label" => $label
    );
    updateJason($jsonFamille, $listeFamille);


    // retourne sur la page
    header("location:../index.php?p=admin");

}

exit;