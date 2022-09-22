<?php 

//retire les notices PHP
error_reporting (E_ALL ^ E_NOTICE); 

function debug($data) {

    echo "<pre>".print_r($data,TRUE)."</pre><hr>";
}

?>