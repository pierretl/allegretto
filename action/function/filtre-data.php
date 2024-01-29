<?php

function sejoursValide($data) {

    $sejoursValide = [];

    for ($i=0; $i < count($data); $i++) {
        if ($data[$i]['etat'] == getenv('CODE_SEJOUR_VALIDE')  ) {
            array_push($sejoursValide, $data[$i]); 
        }
    }

    return $sejoursValide;
}



function sejoursAttente($data) {

    $sejoursAttente = [];

    for ($i=0; $i < count($data); $i++) {
        if ($data[$i]['etat'] == getenv('CODE_SEJOUR_SOUMIS')  ) {
            array_push($sejoursAttente, $data[$i]); 
        }
    }

    return $sejoursAttente;
}



function sejoursAttenteDeFamille($data, $session) {

    $famille = $_SESSION['utilisateur']['famille'];
    $sejourSoumis = sejoursAttente($data);
    $sejoursAValide = [];

    foreach ($sejourSoumis as $keySejourSoumis => $dataSejourSoumis){
        foreach ($sejourSoumis[$keySejourSoumis]['validation'] as $keyValidation => $dataValidation){
            if (
                $sejourSoumis[$keySejourSoumis]['validation'][$keyValidation]['id'] == $famille && 
                $sejourSoumis[$keySejourSoumis]['validation'][$keyValidation]['accord'] == '' &&
                $sejourSoumis[$keySejourSoumis]['departReel'] > date('Y-m-d')
                ) {
                array_push($sejoursAValide, $sejourSoumis[$keySejourSoumis]); 
            }
        }
    }

    return $sejoursAValide;

}



function sejoursAVenirValideParFamille($data, $dataFamilles, $session) {

    $famille = $_SESSION['utilisateur']['famille'];
    $Sejours = $data;
    $sejoursAVenir = [];

    foreach ($Sejours as $keySejours => $dataSejourSoumis){

        //Ajoute le label de la famille au donnnée de validation
        for ($i=0; $i < count($Sejours[$keySejours]['validation']); $i++) {
            foreach ($dataFamilles as $keyDataFamilles => $dataFamille){
                if($Sejours[$keySejours]['validation'][$i]['id'] == $dataFamille['id']) {
                    $Sejours[$keySejours]['validation'][$i]['label'] = $dataFamille['label'];
                }
            }
        }

        //Fitre les séjours par
        foreach ($Sejours[$keySejours]['validation'] as $keyValidation => $dataValidation){
            if (
                $Sejours[$keySejours]['validation'][$keyValidation]['id'] == $famille && 
                $Sejours[$keySejours]['validation'][$keyValidation]['accord'] != '' &&
                $Sejours[$keySejours]['departReel'] > date('Y-m-d')
                ) {
                array_push($sejoursAVenir, $Sejours[$keySejours]); 
            }
        }
    }

    return $sejoursAVenir;
}