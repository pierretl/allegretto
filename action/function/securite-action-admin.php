<?php

if ( $_SESSION['utilisateur']['groupe'] != "admin" ){

    header("location:../index.php?p=calendrier");
    exit;

}