<?php 

include 'function/DotEnv.php';
include 'function/json-manipulation.php';

// Charge les variables d'environnement
(new DotEnv('../.env'))->load();

//commence la session
session_start();

//récupération de la liste des utilisateurs
$jsonUtilisateur = "../".getenv('DATA_UTILISATEUR');
$dataUtilisateur = getDataJson($jsonUtilisateur);

// supprime le token dans le json
$keyUtilisateur = $_SESSION['utilisateur']['key']; // récupére la key de l'utilisateur
$dataUtilisateur[$keyUtilisateur]['authToken'] = ''; // supprime le token
updateJason($jsonUtilisateur, $dataUtilisateur); // met a jour le json

// Supprime la session
session_destroy(); 

header("location:../index.php?p=connexion");
exit;

?>
