<?php

function encrypt_decrypt($string, $action = 'encrypt',$encrypt_method,$secret_key,$secret_iv,$hash) {
    $key = hash($hash, $secret_key);
    $iv = substr(hash($hash, $secret_iv), 0, 16);
    if ($action == 'encrypt') {
        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);
    } else if ($action == 'decrypt') {
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    }
    return $output;
}