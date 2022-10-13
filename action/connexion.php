<?php

include 'function/DotEnv.php';
include 'function/dev.php';
include 'function/chiffrage.php';
include 'function/json-manipulation.php';

// Charge les variables d'environnement
(new DotEnv('../.env'))->load();

//commence la session
session_start();

//récupération de la liste des utilisateurs
$jsonUtilisateur = "../".getenv('DATA_UTILISATEUR');
$dataUtilisateur = getDataJson($jsonUtilisateur);

    
// Stock les données de l'utilisateur
$logins= [];
for ($i=0; $i < count($dataUtilisateur); $i++) {
    $logins += [ 
        $dataUtilisateur[$i]['mail'] => array(
            "prenom" => $dataUtilisateur[$i]['prenom'],
            "mail" => $dataUtilisateur[$i]['mail'],
            "motDePasse" => $dataUtilisateur[$i]['motDePasse'],
            "famille" => $dataUtilisateur[$i]['famille'],
            "groupe" => $dataUtilisateur[$i]['groupe'],
            "authToken" => $dataUtilisateur[$i]['authToken']
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

    // Token d'authentification
    $token = hash('sha256',$logins[$mailChiffre]['mail'] . time()); // création du Token 
    setcookie('authToken', $token, time()+60*60*24*365, '/'); // création du coookie, expire dans 365 jours
    $keyUtilisateur = array_search($mailChiffre, array_column($logins, 'mail')); // récupére la key de l'utilisateur
    $dataUtilisateur[$keyUtilisateur]['authToken'] = $token; // Assigne la valeur au champ adéquate
    updateJason($jsonUtilisateur, $dataUtilisateur); // met a jour le json


    // Ajouter les données de l'utilisateur en session
    $_SESSION['utilisateur']['prenom'] = $logins[$mailChiffre]['prenom'];
    $_SESSION['utilisateur']['mail'] = $logins[$mailChiffre]['mail'];
    $_SESSION['utilisateur']['famille'] = $logins[$mailChiffre]['famille'];
    $_SESSION['utilisateur']['groupe'] = $logins[$mailChiffre]['groupe'];
    $_SESSION['utilisateur']['key'] = $keyUtilisateur;


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