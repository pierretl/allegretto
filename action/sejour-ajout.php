<?php

session_start();

include 'function/securite-action.php';
include 'function/dev.php';
include 'function/json-manipulation.php';
include 'function/securite-saisie.php';

$couleurEnAttenteValidation = "var(--couleur7)";
$jsonFamille = "../data/famille.json";
$jsonSejour = "../data/sejour.json";

//recupère les valeurs saisis
$arrivee = $_POST['arrivee'];
$depart = $_POST['depart'];
$commentaire = $_POST['commentaire'];

//et les infos de l'utilisateur
$utilisateur = $_SESSION['utilisateur']['prenom'];
$familleUtilisateur = $_SESSION['utilisateur']['famille'];

//récupére la liste des familles
$listeFamille = getDataJson($jsonFamille);
// et la liste des séjour
$data = getDataJson($jsonSejour);


if ( empty($arrivee) || empty($depart) ) {

    // Erreur :

    if (empty($arrivee)) {
        $input1 = "&erreur1=1";
    } else {
        $input1 = "&arrivee=".securite_saisi($arrivee);
    }

    if (empty($depart)) {
        $input2 = "&erreur2=1";
    } else {
        $input2 = "&depart=".securite_saisi($depart);
    }

    $input3 = "&commentaire=".securite_saisi($commentaire);

    header("location:../index.php?p=sejour".$input1."".$input2."".$input3);

} else {

    // Succès :

    $validations = [];
    $accord = [];
    for ($i=0; $i < count($listeFamille); $i++){

        // donne l'accord à sa propre demande
        $accord[$i] = "";
        if ($listeFamille[$i]['id'] == $familleUtilisateur){ 
            $accord[$i] = date("Y-m-d H:i:s");
        }

        // génére le tableau des validations
        $validations+= [
            $i => array(
                "id" => $listeFamille[$i]['id'],
                "accord" => $accord[$i]
            )
        ];
    }

    //Remplis les donnée du nouveau sejour
    $lengthData = count($data); // compte a partir de 1
    $data[$lengthData]["title"] = $utilisateur;
    $data[$lengthData]["dataAjout"] = date("Y-m-d H:i:s");
    $data[$lengthData]["start"] = securite_saisi($arrivee);
    $data[$lengthData]["end"] = securite_saisi($depart);
    $data[$lengthData]["commentaire"] = securite_saisi($commentaire);
    $data[$lengthData]["backgroundColor"] = $couleurEnAttenteValidation;
    $data[$lengthData]["validation"] = $validations;

    //met a jour le json
    updateJason($jsonSejour, $data);

    //retourne sur la page
    header("location:../index.php?p=sejour");

}

exit;