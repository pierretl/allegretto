<?php

function sejoursValide($data) {

    $sejoursValide = [];

    for ($i=0; $i < count($data); $i++) {
        if ($data[$i]['backgroundColor'] == getenv('CALENDRIER_COLOR-VALIDE')  ) {
            array_push($sejoursValide, $data[$i]); 
        }
    }

    return $sejoursValide;
}



function sejoursAttente($data) {

    $sejoursAttente = [];

    for ($i=0; $i < count($data); $i++) {
        if ($data[$i]['backgroundColor'] == getenv('CALENDRIER_COLOR-ATTENTE')  ) {
            array_push($sejoursAttente, $data[$i]); 
        }
    }

    return $sejoursAttente;
}