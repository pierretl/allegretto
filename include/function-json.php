<?php

function decodeJason($urlJason) {
    $jsonString = file_get_contents($urlJason);
    $data = json_decode($jsonString, true);
    return $data;
}

function updateJason($urlJason, $data) {
    $newJsonString = json_encode($data);
    file_put_contents($urlJason, $newJsonString);
}

?>