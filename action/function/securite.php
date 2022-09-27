<?php

function securite($pageGroupe = null) {

    // Si pas d'info de session alors redirection page de connexion
    if(!isset($_SESSION['utilisateur']['mail'])){
        header("location:index.php?p=connexion");
        exit;
    }

    //si la page demande un groupe d'utilisateur
    if (isset($pageGroupe)) {

        // et que le groupe en session ne correspond pas au groupe demandé
        if ( $_SESSION['utilisateur']['groupe'] != $pageGroupe ){
            header("location:index.php?p=calendrier");
            exit;
        }
    }

}