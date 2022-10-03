<?php

// Chiffre et déchiffre une donnée
// Ne conviens pas au mot de passe, utilisé password_hash() et password_verify()

function chiffre($string) {
    $encrypt_method = getenv('CRYPTAGE_METHOD');
    $secret_key = getenv('CRYPTAGE_KEY');
    $secret_iv = getenv('CRYPTAGE_IV');
    $hash = getenv('CRYPTAGE_HASH');
    $key = hash($hash, $secret_key);
    $iv = substr(hash($hash, $secret_iv), 0, 16);

    $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
    $output = base64_encode($output);
    return $output;
}

function dechiffre($string) {
    $encrypt_method = getenv('CRYPTAGE_METHOD');
    $secret_key = getenv('CRYPTAGE_KEY');
    $secret_iv = getenv('CRYPTAGE_IV');
    $hash = getenv('CRYPTAGE_HASH');
    $key = hash($hash, $secret_key);
    $iv = substr(hash($hash, $secret_iv), 0, 16);

    $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    return $output;
}