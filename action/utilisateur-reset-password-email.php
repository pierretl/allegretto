<?php

//pour le template du mail
require '../vendor/autoload.php';
require '../twig/ExtentionPerso.php';

// quelques utilitaires
include 'function/DotEnv.php';
include 'function/dev.php';
include 'function/chiffrage.php';
include 'function/json-manipulation.php';

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

// Stock les infos saisi dans des varaibles.
$mail = isset($_POST['mail']) ? $_POST['mail'] : '';

// chiffre l'email 
$mailChiffre = chiffre($mail);

// récupération de la liste des utilisateurs
$jsonUtilisateur = "../".getenv('DATA_UTILISATEUR');
$dataUtilisateur = getDataJson($jsonUtilisateur);

// Tous les email des utilisateurs
$allMailUtilisateur= [];
for ($i=0; $i < count($dataUtilisateur); $i++) {
    $allMailUtilisateur[$i] = $dataUtilisateur[$i]['mail'];
}

// l'email correspond à un utilisateur en data
$utilisateurExistant = array_search($mailChiffre, $allMailUtilisateur);

// Créer une key de demande de changement de mot de passe et la stock dans les data
$keyDemande = chiffre($mail."reinitMotDePasse");
$dateDemande = date("Y-m-d H:i:s");
$dataUtilisateur[$utilisateurExistant]['resetMdp'] = $keyDemande; // Assigne la valeur au champ adéquate
$dataUtilisateur[$utilisateurExistant]['resetMdpDate'] = $dateDemande; // Assigne la valeur au champ adéquate
updateJason($jsonUtilisateur, $dataUtilisateur); // met a jour le json

if (
    $mail !== '' // champs mail saisi
) {

    // Succès :
    if ($utilisateurExistant) {


        // Email : -----------------------------------------------------------------------

        // corp de l'email
        $bodyEmail = $twig->render('email/reset-password.twig', [
            'prenom' => $dataUtilisateur[$utilisateurExistant]['prenom'],
            'linkDemande'=> getenv('APP_URL')."index.php?p=reset-password&k=".$keyDemande."&l=".chiffre($mail)
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
        $destinataire = $mail;
        $sujet = "Réinitialisation de votre mot de passe";
        if( !mail($destinataire, $sujet, $bodyEmail, implode("\r\n", $enTete)) ) {
            //erreur envoie du mail
            header("location:../index.php?p=reset-password-email&mail=".$mail."&erreurMail=1");
            exit;
        } else {
            //mail partis, retourne sur la page
            header("location:../index.php?p=reset-password-email&mail=".$mail."&succes=1");
            exit;
        }

        

    } else {
        // pas de correspondance, pas d'email
        header("location:../index.php?p=reset-password-email&mail=".$mail."&succes=1");
        exit;
    }

} else {

    // Erreur :

    header("location:../index.php?p=reset-password-email&erreur1=1");
    exit;
    
}