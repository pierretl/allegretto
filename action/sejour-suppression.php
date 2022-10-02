<?php 

session_start();

include 'function/json-manipulation.php';
include 'function/dev.php';

if ( $_SESSION['utilisateur']['groupe'] != "admin" ){

    header("location:../index.php?p=calendrier");

} else {

    $jsonSejour = "../data/sejour.json";

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

}


exit();