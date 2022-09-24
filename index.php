<?php

require 'vendor/autoload.php';
require 'twig/ExtentionPerso.php';

// Commence la session
session_start();

// Routing
$page = 'connexion'; // page par défaut
if (isset($_GET['p'])) {
    $page = $_GET['p'];
}

// Récupèration des datas
function getDataJson ($jsonUrl) {
    $jsonString = file_get_contents($jsonUrl);
    $data = json_decode($jsonString, true);
    return $data;
}

//securite
function securite($pageGroupe = null) {

    // Si pas d'info de session alors redirection pas de connexion
    if(!isset($_SESSION['Utilisateur']['Identifiant'])){
        header("location:index.php?p=connexion");
        exit;
    }

    //si la page demande un groupe d'utilisateur
    if (isset($pageGroupe)) {

        // et que le groupe en session ne correspond pas au groupe demandé
        if ( $_SESSION['Utilisateur']['Groupe'] != $pageGroupe ){
            header("location:index.php?p=calendrier");
            exit;
        }
    }

}

// Rendu du template
$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/twig');
$twig = new \Twig\Environment($loader, [
    'cache' => __DIR__ . '/tmp' // mise en cache, dossier tmp a supprimer pour mettre a jour -> pour la production
    //'cache' => false
]);

// Ajout des functions et filtres personnalisé
$twig->addExtension(new ExtentionPerso());

// Ajout d'une variable global
$twig->addGlobal('current_page', $page);


switch ($page) {

    case 'connexion':
        echo $twig->render('page/connexion.twig', [
            'post' => $_POST,
            'get' => $_GET
        ]);
        break;

    case 'admin':
        securite('admin');
        echo $twig->render('page/admin.twig', [
            'session' => $_SESSION,
            'post' => $_POST,
            'get' => $_GET,
            'utilisateurs' => getDataJson('data/utilisateur.json')
            
        ]);
        break;

    case 'sejour':
        securite();
        echo $twig->render('page/sejour.twig' , [
            'session' => $_SESSION
        ]);
        break;

    case 'calendrier':
        echo $twig->render('page/calendrier.twig', [
            'session' => $_SESSION,
            'urlDAta' => 'data/sejour.json'
        ]);
        break;

    default:
        header('HTTP/1.0 404 Not Found');
        echo $twig->render('page/404.twig');
        break;

}

?>