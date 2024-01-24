<?php

session_start();

//pour le template du mail
require '../vendor/autoload.php';
require '../twig/ExtentionPerso.php';

require '../action/function/filtre-data.php';

// quelques utilitaires
include 'function/securite-action.php';
include 'function/DotEnv.php';
include 'function/dev.php';
include 'function/json-manipulation.php';
include 'function/chiffrage.php';
include 'function/securite-saisie.php';

//Charge les variables d'environnement
(new DotEnv('../.env'))->load();

// Rendu du template twig
$loader = new \Twig\Loader\FilesystemLoader('../twig');
if (getenv('APP_ENV') === 'prod') {
    $twig = new \Twig\Environment($loader, [
        'cache' =>'../tmp'
        // la mise en cache, nécessite de supprimer le dossier "tmp" pour mettre a jour le site
    ]);
} else {
    $twig = new \Twig\Environment($loader, [
        'cache' => false
    ]);
}

// Ajout des functions et filtres personnalisé
$twig->addExtension(new ExtentionPerso());

//datas
$jsonUtilisateur = "../".getenv('DATA_UTILISATEUR');
$jsonSejour = "../".getenv('DATA_SEJOUR');
$jsonDernierRappel = "../".getenv('DATA_RAPPEL');

$mailUtilisateur = $_SESSION['utilisateur']['mail'];
$familleUtilisateur = $_SESSION['utilisateur']['famille'];

//Aujourd'hui
$dateLast = date("d m Y");

// liste des sejour en attente de validation
$listeSejourAttente = sejoursAttente(getDataJson($jsonSejour));
//debug($listeSejourAttente);

//Email - début ---------------------------------------------------------------

//Liste de tous les utilisateurs
$listeUtilisateur = getDataJson($jsonUtilisateur);

$listeDeDiffusion = [];
$index = 0;
for ($i=0; $i < count($listeUtilisateur); $i++){
    $listeDeDiffusion[$index] = dechiffre($listeUtilisateur[$i]['mail']);
    $index ++;
}

$destinataire = implode(",", $listeDeDiffusion);

//sujet
$sujet = "Rappel de séjour(s) à validé";

// corp de l'email
$bodyEmail = $twig->render('email/sejour-rappel.twig', [
    'titre' => $sujet,
    'listeSejourAttente' => $listeSejourAttente,
]);

//retire les commentaires HTML
$bodyEmail = preg_replace('/<!--(.|\s)*?-->/', '', $bodyEmail);

//retire les commentaires CSS
$bodyEmail = preg_replace( '!/\*[^*]*\*+([^/][^*]*\*+)*/!' , '' , $bodyEmail);

//echo $bodyEmail; exit; // test email

// Pour envoyer un mail HTML, l'en-tête Content-type doit être défini
$enTete[] = 'MIME-Version: 1.0';
$enTete[] = 'Content-type: text/html; charset=iso-8859-1';

// En-têtes additionnels
$enTete[] = 'From: '.getenv('APP_NAME').' <'. getenv('APP_MAIL') .'>';

//Email - fin -------------------------------------------------------------------

//envoie du mail
//$destinataire = getenv('APP_TEST-MAIL'); // pour test : envoie du mail a moi uniquement
if( !mail($destinataire, $sujet, $bodyEmail, implode("\r\n", $enTete)) ) {
    //erreur envoie du mail
    $rappelReturn = "ko";

    //Redirige sur la page
    header("location:../index.php?p=dashboard&rappel=".$rappelReturn);
    exit;
}

$rappelReturn = "ok";

//Met a jour le json
$data = getDataJson($jsonDernierRappel);
$data[0]["date"] = $dateLast;
updateJason($jsonDernierRappel, $data);

//Redirige sur la page
header("location:../index.php?p=dashboard&rappel=".$rappelReturn);