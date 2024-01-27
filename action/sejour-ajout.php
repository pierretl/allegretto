<?php

session_start();

//pour le template du mail
require '../vendor/autoload.php';
require '../twig/ExtentionPerso.php';

// quelques utilitaires
include 'function/securite-action.php';
include 'function/DotEnv.php';
include 'function/dev.php';
include 'function/json-manipulation.php';
include 'function/securite-saisie.php';
include 'function/chiffrage.php';

// Charge les variables d'environnement
(new DotEnv('../.env'))->load();

// Rendu du template twig
$loader = new \Twig\Loader\FilesystemLoader('../twig');
if (getenv('APP_ENV') === 'prod') {
    $twig = new \Twig\Environment($loader, [
        'cache' =>'../tmp'
        // la mise en cache, nécessite de supprimer le dossier "tmp" pour mettre a jour le site
    ]);
} else {
    $twig = new \Twig\Environment($loader, [
        'cache' => false
    ]);
}

// Ajout des functions et filtres personnalisé
$twig->addExtension(new ExtentionPerso());

//datas
$codeSejourSoumis = getenv('CODE_SEJOUR_SOUMIS');
$couleurEnAttenteValidation = getenv('CALENDRIER_BGCOLOR-SOUMIS');
$jsonFamille = "../".getenv('DATA_FAMILLE');
$jsonSejour = "../".getenv('DATA_SEJOUR');
$jsonUtilisateur = "../".getenv('DATA_UTILISATEUR');

//recupère les valeurs saisis
$pageRedirection = isset($_POST['page']) ? $_POST['page'] : 'sejour';
$label = $_POST['label'];
$arrivee = $_POST['arrivee'];
$depart = $_POST['depart'];
$commentaire = $_POST['commentaire'];

//et les infos de l'utilisateur
$utilisateur = $_SESSION['utilisateur']['prenom'];
$mailUtilisateur = $_SESSION['utilisateur']['mail'];
$familleUtilisateur = $_SESSION['utilisateur']['famille'];
$groupeUtilisateur = $_SESSION['utilisateur']['groupe'];

//récupére la liste des familles
$listeFamille = getDataJson($jsonFamille);
// et la liste des séjour
$data = getDataJson($jsonSejour);


if ( 
    empty($label) || 
    empty($arrivee) || 
    empty($depart) ||
    strtotime($depart) < strtotime($arrivee)
) {

    // Erreur :

    if (empty($label)) {
        $input1 = "&erreur1=1";
    } else {
        $input1 = "&label=".securite_saisi($label);
    }

    if (empty($arrivee)) {
        $input2 = "&erreur2=1";
    } else {
        $input2 = "&arrivee=".securite_saisi($arrivee);
    }

    if (empty($depart)) {
        $input3 = "&erreur3=1";
    } else {
        $input3 = "&depart=".securite_saisi($depart);
    }

    if (strtotime($depart) < strtotime($arrivee)) {
        $dateInverse = "&dateInverse=1";
    }

    $input4 = "&commentaire=".securite_saisi($commentaire);

    header("location:../index.php?p=".$pageRedirection."".$input1."".$input2."".$input3."".$input4."".$dateInverse);

} else {

    // Succès :

    $validations = [];
    $accord = [];
    for ($i=0; $i < count($listeFamille); $i++){

        // donne l'accord à sa propre demande
        $accord[$i] = "";
        if ($listeFamille[$i]['id'] == $familleUtilisateur){ 
            $accord[$i] = date("Y-m-d H:i:s");
        }

        // génére le tableau des validations
        $validations+= [
            $i => array(
                "id" => $listeFamille[$i]['id'],
                "accord" => $accord[$i]
            )
        ];
    }

    //fullcalednbar n'affiche pas le jour "end" sur le calendrier, donc on rajoute 1 jour a la date saisie
    $endDate = str_replace("-", "/", $depart);
    $endDate = date("Y-m-d", strtotime($depart. "+1 days"));

    //Remplis les donnée du nouveau sejour
    $lengthData = count($data); // compte a partir de 1
    $data[$lengthData]["title"] = $label;
    $data[$lengthData]["dataAjout"] = date("Y-m-d H:i:s");
    $data[$lengthData]["start"] = securite_saisi($arrivee);
    $data[$lengthData]["end"] = securite_saisi($endDate);
    $data[$lengthData]["departReel"] = securite_saisi($depart);
    $data[$lengthData]["commentaire"] = securite_saisi($commentaire);
    $data[$lengthData]["pourFamille"] = $familleUtilisateur;
    $data[$lengthData]["parUtilisateur"] = $utilisateur;
    $data[$lengthData]["mailDemandeur"] = $mailUtilisateur;
    $data[$lengthData]["etat"] = $codeSejourSoumis;
    $data[$lengthData]["backgroundColor"] = $couleurEnAttenteValidation;
    $data[$lengthData]["validation"] = $validations;

    // Email : ----------------------------------------------------------------------------------------------

    //récupére la liste des utilisateurs
    $listeUtilisateur = getDataJson($jsonUtilisateur);
    $listeDeDiffusion = [];
    $index = 0;
    for ($i=0; $i < count($listeUtilisateur); $i++){
        // on ne prend pas en compte l'auteur de la demande dans la liste de diffusion
        // et les membres de sa famille
        if ( 
            $listeUtilisateur[$i]['mail'] != $mailUtilisateur &&
            $listeUtilisateur[$i]['famille'] != $familleUtilisateur
        ) {
            $listeDeDiffusion[$index] = dechiffre($listeUtilisateur[$i]['mail']);
            $index ++;
        }
    }

    $destinataire = implode(",", $listeDeDiffusion);
    $sujet = $utilisateur ." a soumis un séjour";

    // corp de l'email
    $bodyEmail = $twig->render('email/ajout-sejour.twig', [
        'titre' => "Un séjour attend votre validation",
        'prenom' => $utilisateur,
        'arrivee' => $arrivee,
        'depart' => $depart,
        'commentaire' => $commentaire,
        'keySejour' => $lengthData
    ]);

    //retire les commentaires HTML
    $bodyEmail = preg_replace('/<!--(.|\s)*?-->/', '', $bodyEmail);

    //retire les commentaires CSS
    $bodyEmail = preg_replace( '!/\*[^*]*\*+([^/][^*]*\*+)*/!' , '' , $bodyEmail);

    //test de l'email
    //echo $bodyEmail; exit;

    // Pour envoyer un mail HTML, l'en-tête Content-type doit être défini
    $enTete[] = 'MIME-Version: 1.0';
    $enTete[] = 'Content-type: text/html; charset=iso-8859-1';

    // En-têtes additionnels
    $enTete[] = 'From: '.getenv('APP_NAME').' <'. getenv('APP_MAIL') .'>';

    //envoie du mail
    //$destinataire = getenv('APP_TEST-MAIL'); // envoie du mail a moi uniquement
    if( !mail($destinataire, $sujet, $bodyEmail, implode("\r\n", $enTete)) ) {
        //erreur envoie du mail
        $erreurMail = "&erreurMail=1";
    }

    //met a jour le json
    updateJason($jsonSejour, $data);

    //retourne sur la page
    header("location:../index.php?p=".$pageRedirection."".$erreurMail."&ajout=".$arrivee);

}

exit;