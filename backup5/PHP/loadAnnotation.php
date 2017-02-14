<?php
include('simple_html_dom.php');
include('dbManager.php');
$output = array();
$json = load("../Dataset/project-files/annotations.json");
if($json != null){
    foreach($json as $value){
        if(strcmp($_POST['localUrl'],$value["Doc"]) == 0){
            array_push($output,$value);
        }
    }
}
echo json_encode($output);
?>