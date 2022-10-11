<?php

function validation_sejour_via_email() {

    if(
        isset($_SESSION['utilisateur']['mail']) &&
        isset($_GET['valideSejour'])
    ){
        $keySejour = $_GET['valideSejour'];

        if (is_numeric($keySejour)) {

            header("location:action/sejour-validation.php?key=".$keySejour);
            exit;
        }
        
    }

}

