<?php 

function securite_saisi($data) {
    return str_replace('"', '', $data); // pour pas casser le json
}