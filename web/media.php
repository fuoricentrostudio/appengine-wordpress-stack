<?php

$matches = array();

$source = $_SERVER['REQUEST_URI'];

$filename = "";

if(preg_match('~/wp-content/uploads/.*/((.*)(-\d+x\d+)\.(.*))$~', $source, $matches)){
    $filename = $matches[2].'.'.$matches[4];
}elseif(preg_match('~/wp-content/uploads/.*/(.*\.(.*))$~', $source, $matches)){
    $filename = $matches[1];
}

if($filename){
    header('Location: https://storage.googleapis.com/piananotizie-eu.appspot.com/'.$filename, true, 302);
} else {
    header("Location: http://hosting.piananotizie.it/".$_SERVER['REQUEST_URI'], true, 302);
}
