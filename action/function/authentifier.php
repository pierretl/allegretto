<?php

function authentifier($pageGroupe = null) {

    
    if(
        !isset($_SESSION['utilisateur']['mail']) && // Si pas d'info de session
        !isset($_COOKIE['authToken']) // le cookie n'existe pas
    ){

        if ( 
            !isset($_GET['p']) || // si on est pas sur la page d'acceuil
            $_GET['p'] != 'connexion' // ni la page de connexion
        ) {

            // redirection page de connexion
            header("location:index.php?p=connexion");
            exit;

        }

    } else {

        //récupération de la liste des utilisateurs
        $jsonUtilisateur = getenv('DATA_UTILISATEUR');
        $dataUtilisateur = getDataJson($jsonUtilisateur);

        //récupération du token
        $authToken = $_COOKIE['authToken'];

        // Stock les données de l'utilisateur
        $logins= [];
        for ($i=0; $i < count($dataUtilisateur); $i++) {
            $logins += [ 
                $dataUtilisateur[$i]['authToken'] => array(
                    "prenom" => $dataUtilisateur[$i]['prenom'],
                    "mail" => $dataUtilisateur[$i]['mail'],
                    "motDePasse" => $dataUtilisateur[$i]['motDePasse'],
                    "famille" => $dataUtilisateur[$i]['famille'],
                    "groupe" => $dataUtilisateur[$i]['groupe'],
                    "authToken" => $dataUtilisateur[$i]['authToken']
                ) 
            ];
        }

        // si le token correspond à celui d'un des utilisateurs
        if ( array_search($authToken, array_column($logins, 'authToken')) !== false ) {

            $keyUtilisateur = array_search($authToken, array_column($logins, 'authToken')); // récupére la key de l'utilisateur

            // set la session de l'utilisateur
            $_SESSION['utilisateur']['prenom'] = $logins[$authToken]['prenom'];
            $_SESSION['utilisateur']['mail'] = $logins[$authToken]['mail'];
            $_SESSION['utilisateur']['famille'] = $logins[$authToken]['famille'];
            $_SESSION['utilisateur']['groupe'] = $logins[$authToken]['groupe'];
            $_SESSION['utilisateur']['key'] = $keyUtilisateur;

        }
    }

    //si la page demande un groupe d'utilisateur
    if (isset($pageGroupe)) {

        // et que le groupe en session ne correspond pas au groupe demandé
        if ( $_SESSION['utilisateur']['groupe'] != $pageGroupe ){
            header("location:index.php?p=dashboard");
            exit;
        }

    }

}