<?php

include 'function/dev.php';
include 'function/encryption.php';
include 'function/cle-de-cryptage.php';

//commence la session
session_start();

//récupérationde la liste des utilisateurs
$jsonString = file_get_contents("../data/utilisateur.json");
$dataUtilisateur = json_decode($jsonString, true);

    
// Stock les nom d'utilisateur et mot de passe associé
$logins= [];
for ($i=0; $i < count($dataUtilisateur); $i++) {
    $logins += [ 
        $dataUtilisateur[$i]['mail'] => array(
            "prenom" => $dataUtilisateur[$i]['prenom'],
            "mail" => $dataUtilisateur[$i]['mail'],
            "motDePasse" => $dataUtilisateur[$i]['motDePasse'],
            "groupe" => $dataUtilisateur[$i]['groupe']
        ) 
    ];
}

// Stock les infos saisi dans des varaibles.
$mail = isset($_POST['mail']) ? $_POST['mail'] : '';
$motdepasse = isset($_POST['motdepasse']) ? $_POST['motdepasse'] : '';

//crypte l'email 
$mailCrypte = encrypt_decrypt($mail, 'encrypt',$encrypt_method,$secret_key,$secret_iv,$hash);

if (
    $mail !== '' && // champs mail saisi
    $motdepasse !== '' && // champs mot de passe saisi
    array_search($mailCrypte, array_column($logins, 'mail')) !== false && // le login saisi correspond à un utilisateur en data
    $motdepasse && password_verify($motdepasse, $logins[$mailCrypte]['motDePasse'])  // le mot de passe correspond à l'utilisateur
) {

    // Succès :

    // Ajouter les données de l'utilisateur en session
    $_SESSION['utilisateur']['prenom'] = $logins[$mailCrypte]['prenom'];
    $_SESSION['utilisateur']['mail'] = $logins[$mailCrypte]['mail'];
    $_SESSION['utilisateur']['groupe'] = $logins[$mailCrypte]['groupe'];

    // redirige sur la page adéquate
    if ( $logins[$mailCrypte]['groupe'] == 'admin' ) {
        header("location:../index.php?p=admin");
    } else {
        header("location:../index.php?p=calendrier");
    }
    exit;
    
    
} else {

    // Erreur :
    header("location:../index.php?p=connexion&erreur=true&mail=".$mail);
    exit;
    
}