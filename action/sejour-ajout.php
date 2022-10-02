<?php

include 'function/dev.php';
include 'function/json-manipulation.php';
include 'function/securite-saisie.php';

session_start();

$couleurEnAttenteValidation = "var(--couleur7)";
$jsonFamille = "../data/famille.json";
$jsonSejour = "../data/sejour.json";

//recupère les valeurs saisis
$arrivee = $_POST['arrivee'];
$depart = $_POST['depart'];
$commentaire = $_POST['commentaire'];

//et les infos de l'utilisateur
$utilisateur = $_SESSION['utilisateur']['prenom'];
$famille = $_SESSION['utilisateur']['famille'];

//récupére la liste des familles
$listeFamille = getDataJson($jsonFamille);
// et la liste des séjour
$data = getDataJson($jsonSejour);


if ( empty($arrivee) || empty($depart) ) {

    // Erreur :

    if (empty($arrivee)) {
        $input1 = "&erreur1=1";
    } else {
        $input1 = "&arrivee=".securite_saisi($arrivee);
    }

    if (empty($depart)) {
        $input2 = "&erreur2=1";
    } else {
        $input2 = "&depart=".securite_saisi($depart);
    }

    $input3 = "&commentaire=".securite_saisi($commentaire);

    header("location:../index.php?p=sejour".$input1."".$input2."".$input3);

} else {

    // Succès :
    echo "ajout du séjour a faire";

    exit;

    //header("location:../index.php?p=sejour");

}


//ajouter commentaire et date de la demande
//si date de la demande corespojnda a la validation faire un afficahge différent dans sejour.php



exit;

