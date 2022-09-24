<?php

include 'function/dev.php';
include 'function/json-manipulation.php';

//recupère les valeur saisi
$identifiant = $_POST['idendifiant'];
$motDePasse = $_POST['motdepasse'];

if (empty($identifiant) || empty($motDePasse)) {

    // Erreur :

    if (empty($identifiant)) {
        $input1 = "&erreur1=1";
    } else {
        $input1 = "&idendifiant=".$identifiant;
    }
    
    if (empty($motDePasse)) {
        $input2 = "&erreur2=1";
    } else {
        $input2 = "&motdepasse=".$motDePasse;
    }

    header("location:../index.php?p=admin".$input1."".$input2);

} else {

    // Succès :
    $jsonUtilisateur = "../data/utilisateur.json";

    //liste des utilisateurs
    $data = decodeJason($jsonUtilisateur);
    //debug($data);
    

    //ajoute le nouveau utilisateur
    $lengthData = count($data); // compte a partir de 1
    $data[$lengthData]["idendifiant"] = $identifiant;
    $data[$lengthData]["motDePasse"] = password_hash($motDePasse, PASSWORD_DEFAULT);

    //met a jour le json
    updateJason($jsonUtilisateur, $data);
    //debug($data);

    //redirige sur la page
    header("location:../index.php?p=admin");
}

exit;