<?php

require 'vendor/autoload.php';
require 'twig/ExtentionPerso.php';

require 'action/function/securite.php';
require 'action/function/json-manipulation.php';

// Commence la session
session_start();

// Routing
$page = 'connexion'; // page par défaut
if (isset($_GET['p'])) {
    $page = $_GET['p'];
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
            'utilisateurs' => getDataJson('data/utilisateur.json'),
            'familles' => getDataJson('data/famille.json'),
            'selectOptionFamille' => selectOptionFamille()
        ]);
        break;

    case 'sejour':
        securite();
        echo $twig->render('page/sejour.twig' , [
            'session' => $_SESSION,
            'dataCalendrier' => 'data/sejour.json',
            'familles' => getDataJson('data/famille.json'),
            'sejours' => getDataJson('data/sejour.json')
        ]);
        break;

    case 'calendrier':
        echo $twig->render('page/calendrier.twig', [
            'session' => $_SESSION,
            'dataCalendrier' => 'data/sejour.json'
        ]);
        break;

    default:
        header('HTTP/1.0 404 Not Found');
        echo $twig->render('page/404.twig');
        break;

}

?>