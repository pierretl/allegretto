<?php

function validation_sejour_via_email() {

    if(
        isset($_SESSION['utilisateur']['mail']) &&
        isset($_GET['valideLeSejour'])
    ){
        $dataAjout = $_GET['valideLeSejour'];
        header("location:action/sejour-validation.php?dataAjout=".$dataAjout);
        exit;
    }

}

