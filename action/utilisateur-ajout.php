<?php

session_start();

include 'function/securite-action.php';
include 'function/DotEnv.php';
include 'function/dev.php';
include 'function/json-manipulation.php';
include 'function/chiffrage.php';
include 'function/securite-saisie.php';

// Charge les variables d'environnement
(new DotEnv('../.env'))->load();

//recupère les valeur saisi
$prenom = $_POST['prenom'];
$mail = $_POST['mail'];
$motDePasse = $_POST['motdepasse'];
$famille = $_POST['famille'];

if ( empty($prenom) || empty($mail) || empty($motDePasse) || $famille == '' ) {

    // Erreur :

    if (empty($prenom)) {
        $input1 = "&erreur1=1";
    } else {
        $input1 = "&prenom=".securite_saisi($prenom);
    }

    if (empty($mail)) {
        $input2 = "&erreur2=1";
    } else {
        $input2 = "&mail=".securite_saisi($mail);
    }
    
    if (empty($motDePasse)) {
        $input3 = "&erreur3=1";
    } else {
        $input3 = "&motdepasse=".securite_saisi($motDePasse);
    }
    
    if ($famille == '') {
        $input4 = "&erreur4=1";
    } else {
        $input4 = "&famille=".securite_saisi($famille);
    }

    header("location:../index.php?p=admin".$input1."".$input2."".$input3."".$input4);

} else {

    // Succès :
    $jsonUtilisateur = "../".getenv('DATA_UTILISATEUR');

    //liste des utilisateurs
    $data = getDataJson($jsonUtilisateur);
    //debug($data);

    //chiffre l'email  
    $mailChiffre = chiffre($mail);
    

    //ajoute le nouveau utilisateur
    $lengthData = count($data); // compte a partir de 1
    $data[$lengthData]["prenom"] = securite_saisi($prenom);
    $data[$lengthData]["famille"] = securite_saisi($famille);
    $data[$lengthData]["mail"] = securite_saisi($mailChiffre);
    $data[$lengthData]["motDePasse"] = securite_saisi(password_hash($motDePasse, PASSWORD_DEFAULT));
    $data[$lengthData]["groupe"] = "";
    $data[$lengthData]["authToken"] = "";

    //met a jour le json
    updateJason($jsonUtilisateur, $data);
    //debug($data);

    //redirige sur la page
    header("location:../index.php?p=admin");
}

exit;