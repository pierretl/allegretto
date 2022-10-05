<?php

session_start();

include 'function/securite-action.php';
include 'function/DotEnv.php';

// Charge les variables d'environnement
(new DotEnv('../.env'))->load();

//récupération du json
$data = file_get_contents("../".getenv('DATA_SEJOUR'));

echo $data;