<?php

//recupère les valeur saisi
$identifiant = $_POST['idendifiant'];
$motDePasse = $_POST['motDePasse'];

if (empty($identifiant)) {
    $erreurIdendifiant = true;
}

if (empty($motDePasse)) {
    $erreurMotDePasse = true;
}

if ($identifiant && $motDePasse) {

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

    //redirige sur la page pour retire les parametres url
    header("Location: index.php");

}

?>