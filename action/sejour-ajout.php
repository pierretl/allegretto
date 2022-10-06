<?php

session_start();

include 'function/securite-action.php';
include 'function/DotEnv.php';
include 'function/dev.php';
include 'function/json-manipulation.php';
include 'function/securite-saisie.php';

// Charge les variables d'environnement
(new DotEnv('../.env'))->load();

$couleurEnAttenteValidation = getenv('CALENDRIER_COLOR-ATTENTE');
$jsonFamille = "../".getenv('DATA_FAMILLE');
$jsonSejour = "../".getenv('DATA_SEJOUR');

//recupère les valeurs saisis
$label = $_POST['label'];
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


if ( 
    empty($label) || 
    empty($arrivee) || 
    empty($depart) ||
    strtotime($depart) < strtotime($arrivee)
) {

    // Erreur :

    if (empty($label)) {
        $input1 = "&erreur1=1";
    } else {
        $input1 = "&label=".securite_saisi($label);
    }

    if (empty($arrivee)) {
        $input2 = "&erreur2=1";
    } else {
        $input2 = "&arrivee=".securite_saisi($arrivee);
    }

    if (empty($depart)) {
        $input3 = "&erreur3=1";
    } else {
        $input3 = "&depart=".securite_saisi($depart);
    }

    if (strtotime($depart) < strtotime($arrivee)) {
        $dateInverse = "&dateInverse=1";
    }

    $input4 = "&commentaire=".securite_saisi($commentaire);

    header("location:../index.php?p=sejour".$input1."".$input2."".$input3."".$input4."".$dateInverse);

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
    $data[$lengthData]["title"] = $label;
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