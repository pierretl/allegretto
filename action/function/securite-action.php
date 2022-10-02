<?php

if ( !isset($_SESSION['utilisateur']['prenom']) ){

    header("location:../index.php?p=connexion");
    exit;

}