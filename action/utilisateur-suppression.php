<?php

session_start();

include 'function/securite-action-admin.php';
include 'function/dev.php';
include 'function/json-manipulation.php';

$jsonUtilisateur = "../data/utilisateur.json";

//recupère la key de l'utilisateur a supprimer
$utilisateurKey = $_GET['key'] - 1; // -1 car la loop de twig commence à 1

//liste des utilisateurs
$data = getDataJson($jsonUtilisateur);
//debug($data);

// on supprime que les utilisateur non admin
if ($data[$utilisateurKey]['groupe'] != "admin") {

    // supprimer l'utilisateur
    unset($data[$utilisateurKey]);

    // re-index le json
    $dataReIndex = array_values($data);

    //met a jour le json
    updateJason($jsonUtilisateur, $dataReIndex);

    //redirige sur la page admin
    header("location:../index.php?p=admin");

}

exit;