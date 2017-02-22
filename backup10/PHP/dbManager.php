<?php
function load($fileurl){
    if(!file_exists($fileurl)){
        file_put_contents($fileurl, '');
    }
    $string = file_get_contents($fileurl);
    return json_decode($string,true);
}

function write($fileurl,$json){
    $json = json_encode($json);
    if(!file_exists($fileurl)){
        touch($fileurl);
    }
    file_put_contents($fileurl,$json);
}
?>