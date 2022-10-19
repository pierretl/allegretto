<?php

require 'vendor/autoload.php';
require 'twig/ExtentionPerso.php';

require 'action/function/DotEnv.php';
require 'action/function/filtre-data.php';
require 'action/function/authentifier.php';
require 'action/function/chiffrage.php';
require 'action/function/json-manipulation.php';
require 'action/function/validation-sejour-via-email.php';

// Commence la session
session_start();

// validation via lien email
validation_sejour_via_email();

// Charge les variables d'environnement
(new DotEnv(__DIR__ . '/.env'))->load();

// Routing
$page = 'connexion'; // page par défaut
if (isset($_GET['p'])) {
    $page = $_GET['p'];
} else {
    //si on est deja connecter
    if ( isset( $_SESSION['utilisateur']['prenom']) ) {
        header("location:index.php?p=sejour");
    }
}

// Rendu du template
$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/twig');
if (getenv('APP_ENV') === 'prod') {
    $twig = new \Twig\Environment($loader, [
        'cache' => __DIR__ . '/tmp'
        // la mise en cache, nécessite de supprimer le dossier "tmp" pour mettre a jour le site
    ]);
} else {
    $twig = new \Twig\Environment($loader, [
        'cache' => false
    ]);
}

// Ajout des functions et filtres personnalisé
$twig->addExtension(new ExtentionPerso());

// Ajout d'une variable global
$twig->addGlobal('current_page', $page);


switch ($page) {

    case 'connexion':
        authentifier();
        echo $twig->render('page/connexion.twig', [
            'post' => $_POST,
            'get' => $_GET
        ]);
        break;

    case 'admin':
        authentifier('admin');
        echo $twig->render('page/admin.twig', [
            'session' => $_SESSION,
            'post' => $_POST,
            'get' => $_GET,
            'utilisateurs' => getDataJson(getenv('DATA_UTILISATEUR')),
            'familles' => getDataJson(getenv('DATA_FAMILLE')),
            'selectOptionFamille' => selectOptionFamille()
        ]);
        break;

    case 'sejour':
        authentifier();
        echo $twig->render('page/sejour.twig' , [
            'session' => $_SESSION,
            'post' => $_POST,
            'get' => $_GET,
            'dataCalendrier' => getenv('DATA_SEJOUR'),
            'familles' => getDataJson(getenv('DATA_FAMILLE')),
            'sejours' => getDataJson(getenv('DATA_SEJOUR')),
            'sejoursValide' => sejoursValide(getDataJson(getenv('DATA_SEJOUR')))
        ]);
        break;

    case 'calendrier':
        echo $twig->render('page/calendrier.twig', [
            'session' => $_SESSION,
            'dataCalendrier' => getenv('DATA_SEJOUR')
        ]);
        break;

    default:
        header('HTTP/1.0 404 Not Found');
        echo $twig->render('page/404.twig');
        break;

}

?>