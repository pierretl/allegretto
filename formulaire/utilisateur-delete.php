<?php

//recupère la key de l'utilisateur a supprimer
$utilisateurKey = $_GET['key'];

//liste des utilisateurs
$data = decodeJason($jsonUtilisateur);

// on supprime que les utilisateur non admin
if ($data[$utilisateurKey]['groupe'] != "admin") {

    // supprimer l'utilisateur
    unset($data[$utilisateurKey]);

    // re-index le json
    $dataReIndex = array_values($data);

    //met a jour le json
    updateJason($jsonUtilisateur, $dataReIndex);

    //redirige sur la page pour retire les parametres url
    header("Location: index.php");

}


?>