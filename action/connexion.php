<?php

include 'function/DotEnv.php';
include 'function/dev.php';
include 'function/chiffrage.php';

// Charge les variables d'environnement
(new DotEnv('../.env'))->load();

//commence la session
session_start();

//récupérationde la liste des utilisateurs
$jsonString = file_get_contents("../data/utilisateur.json");
$dataUtilisateur = json_decode($jsonString, true);

    
// Stock les données de l'utilisateur
$logins= [];
for ($i=0; $i < count($dataUtilisateur); $i++) {
    $logins += [ 
        $dataUtilisateur[$i]['mail'] => array(
            "prenom" => $dataUtilisateur[$i]['prenom'],
            "mail" => $dataUtilisateur[$i]['mail'],
            "motDePasse" => $dataUtilisateur[$i]['motDePasse'],
            "famille" => $dataUtilisateur[$i]['famille'],
            "groupe" => $dataUtilisateur[$i]['groupe']
        ) 
    ];
}

// Stock les infos saisi dans des varaibles.
$mail = isset($_POST['mail']) ? $_POST['mail'] : '';
$motdepasse = isset($_POST['motdepasse']) ? $_POST['motdepasse'] : '';

//chiffre l'email 
$mailChiffre = chiffre($mail);

if (
    $mail !== '' && // champs mail saisi
    $motdepasse !== '' && // champs mot de passe saisi
    array_search($mailChiffre, array_column($logins, 'mail')) !== false && // le login saisi correspond à un utilisateur en data
    $motdepasse && password_verify($motdepasse, $logins[$mailChiffre]['motDePasse'])  // le mot de passe correspond à l'utilisateur
) {

    // Succès :

    // Ajouter les données de l'utilisateur en session
    $_SESSION['utilisateur']['prenom'] = $logins[$mailChiffre]['prenom'];
    $_SESSION['utilisateur']['mail'] = $logins[$mailChiffre]['mail'];
    $_SESSION['utilisateur']['famille'] = $logins[$mailChiffre]['famille'];
    $_SESSION['utilisateur']['groupe'] = $logins[$mailChiffre]['groupe'];

    // redirige sur la page adéquate
    if ( $logins[$mailChiffre]['groupe'] == 'admin' ) {
        header("location:../index.php?p=sejour");
    } else {
        header("location:../index.php?p=calendrier");
    }
    exit;
    
    
} else {

    echo "erreur";

    // Erreur :
    header("location:../index.php?p=connexion&erreur=true&mail=".$mail);
    exit;
    
}