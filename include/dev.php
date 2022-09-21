<?php 

//retire les notices PHP
error_reporting (E_ALL ^ E_NOTICE); 

function debug($data) {

    var_dump($data);
    echo "<hr>";

}

?>