<?php

include 'function/DotEnv.php';
include 'function/dev.php';
include 'function/chiffrage.php';
include 'function/json-manipulation.php';

// Charge les variables d'environnement
(new DotEnv('../.env'))->load();

//récupération de la liste des utilisateurs
$jsonUtilisateur = "../".getenv('DATA_UTILISATEUR');
$dataUtilisateur = getDataJson($jsonUtilisateur);

// Stock les infos saisi dans des varaibles.
$mail = isset($_POST['mail']) ? $_POST['mail'] : '';
$motdepasse = isset($_POST['motdepasse']) ? $_POST['motdepasse'] : '';
$keymail = isset($_POST['keymail']) ? $_POST['keymail'] : '';

//chiffre l'email 
$mailChiffre = chiffre($mail);
$motdepasseChiffre = chiffre($motdepasse);

// Erreur mot de passe identique à l'email
$erreurMotDePasseIdentiqueEmail = false;
if ($mail == $motdepasse) {
    $erreurMotDePasseIdentiqueEmail = true;
}

// Tous les email des utilisateurs
$allMailUtilisateur= [];
for ($i=0; $i < count($dataUtilisateur); $i++) {
    $allMailUtilisateur[$i] = $dataUtilisateur[$i]['mail'];
}

// l'email correspond à un utilisateur en data
$utilisateurExistant = array_search($mailChiffre, $allMailUtilisateur);

// Vérificationde de lala clé de sécurité $keymail
$keyConforme = false; // init
$demande = isset($dataUtilisateur[$utilisateurExistant]['resetMdp'])? $dataUtilisateur[$utilisateurExistant]['resetMdp'] : ''; // récupération de la key de la demmande en data
if ($keymail == $demande) { // Vérification si ca match avec celle du formulaire
    $keyConforme = true;
}

// Vérification si la date de la demande est toujours valide
$dateConforme = false; // init
$dateDemande = isset($dataUtilisateur[$utilisateurExistant]['resetMdpDate'])? $dataUtilisateur[$utilisateurExistant]['resetMdpDate'] : ''; // récupération de la date de la demande
$dateDemandeLimit = date('Y-m-d H:i:s', strtotime('+1 day', strtotime($dateDemande))); // date limite
$dateDuJour = date("Y-m-d H:i:s");
// comparatif
$format = "Y-m-d H:i:s";
$dateA  = \DateTime::createFromFormat($format, $dateDemandeLimit);
$dateB  = \DateTime::createFromFormat($format, date("Y-m-d H:i:s"));
// conformité
if ($dateA > $dateB) {
    $dateConforme = true;
}

if (
    $mail !== '' && // champs mail saisi
    $motdepasse !== '' && // champs mot de passe saisi
    $erreurMotDePasseIdentiqueEmail === false && // Erreur mot de passe identique à l'email
    $utilisateurExistant !== false && // l'email correspond à un utilisateur en data
    $keyConforme === true && // demande provenant d'un email en data
    $dateConforme === true // date de la demande conforme (moins de 1 jour)
) { 

    // Succès :
    
    $dataUtilisateur[$utilisateurExistant]['motDePasse'] = password_hash($motdepasse, PASSWORD_DEFAULT); //met a jour le mot de passe
    $dataUtilisateur[$utilisateurExistant]['resetMdp'] = ''; // supprime la key de la demande
    $dataUtilisateur[$utilisateurExistant]['resetMdpDate'] = ''; // supprime la date de la demande
    updateJason($jsonUtilisateur, $dataUtilisateur); // met a jour le json
    header("location:../index.php?p=connexion&resetmdp=1");
    exit;


} else {

    // Erreur :

    if ( $mail === '' ){
        $erreur1 = "&erreur1=1";
    }

    if ( $motdepasse === '' ){
        $erreur2 = "&erreur2=1";
    }

    if ($erreurMotDePasseIdentiqueEmail === true) {
        $erreur3 = "&erreur3=1";
    }

    if (
        $utilisateurExistant === false or
        $keyConforme === false
    ) {
        $erreur4 = "&erreur4=1";
    }

    if ($dateConforme === false) {
        $erreur5 = "&erreur5=1";
    }

    header("location:../index.php?p=reset-password&l=".$mailChiffre."&pw=".$motdepasseChiffre."&k=".$keymail.$erreur1.$erreur2.$erreur3.$erreur4.$erreur5);
    exit;

}