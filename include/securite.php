<?php

session_start(); //commence la session

if(!isset($_SESSION['Utilisateur']['Identifiant'])){
	header("location:connexion.php");
	exit;
}

?>