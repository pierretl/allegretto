<?php

include 'function/dev.php';
include 'function/json-manipulation.php';
include 'function/encryption.php';
include 'function/cle-de-cryptage.php';

function securite_saisi($data) {
    return str_replace('"', '', $data); // pour pas casser le json
}

//recupère les valeur saisi
$prenom = $_POST['prenom'];
$mail = $_POST['mail'];
$motDePasse = $_POST['motdepasse'];

if ( empty($prenom) || empty($mail) || empty($motDePasse) ) {

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

    header("location:../index.php?p=admin".$input1."".$input2."".$input3);

} else {

    // Succès :
    $jsonUtilisateur = "../data/utilisateur.json";

    //liste des utilisateurs
    $data = decodeJason($jsonUtilisateur);
    //debug($data);

    //crypte l'email  
    $mailCrypte = encrypt_decrypt($mail, 'encrypt',$encrypt_method,$secret_key,$secret_iv,$hash);
    

    //ajoute le nouveau utilisateur
    $lengthData = count($data); // compte a partir de 1
    $data[$lengthData]["prenom"] = securite_saisi($prenom);
    $data[$lengthData]["mail"] = securite_saisi($mailCrypte);
    $data[$lengthData]["motDePasse"] = securite_saisi(password_hash($motDePasse, PASSWORD_DEFAULT));

    //met a jour le json
    updateJason($jsonUtilisateur, $data);
    //debug($data);

    //redirige sur la page
    header("location:../index.php?p=admin");
}

exit;