<?php

//commence la session
session_start();

//récupérationde la liste des utilisateurs
$jsonString = file_get_contents("../data/utilisateur.json");
$dataUtilisateur = json_decode($jsonString, true);

    
// Stock les nom d'utilisateur et mot de passe associé
$logins= [];
for ($i=0; $i < count($dataUtilisateur); $i++) {
    $logins += [ 
        $dataUtilisateur[$i]['idendifiant'] => array(
            "idendifiant" => $dataUtilisateur[$i]['idendifiant'],
            "motDePasse" => $dataUtilisateur[$i]['motDePasse'],
            "groupe" => $dataUtilisateur[$i]['groupe']
        ) 
    ];
}

// Stock les infos saisi dans des varaibles.
$Identifiant = isset($_POST['Identifiant']) ? $_POST['Identifiant'] : '';
$MotDePasse = isset($_POST['MotDePasse']) ? $_POST['MotDePasse'] : '';

if ( 
    $Identifiant !== '' && // champs identifiant saisi
    $MotDePasse !== '' && // champs Mot de passe saisi
    array_search($Identifiant, array_column($logins, 'idendifiant')) !== false && // le login saisi correspond à un utilisateur en data
    $MotDePasse && password_verify($MotDePasse, $logins[$Identifiant]['motDePasse'])  // le mot de passe correspond à l'utilisateur
) {

    // Succès :

    // Ajouter les données de l'utilisateur en session
    $_SESSION['Utilisateur']['Identifiant'] = $logins[$Identifiant]['idendifiant'];
    $_SESSION['Utilisateur']['Groupe'] = $logins[$Identifiant]['groupe'];

    // redirige sur la page adéquate
    if ( $logins[$Identifiant]['groupe'] == 'admin' ) {
        header("location:../index.php?p=admin");
    } else {
        header("location:../index.php?p=calendrier");
    }
    exit;
    
    
} else {

    // Erreur :
    header("location:../index.php?p=connexion&erreur=true&Identifiant=".$Identifiant);
    exit;
    
}