<?php
session_start();

include('simple_html_dom.php');
$output = array();

$json_event = file_get_contents("../Dataset/project-files/events.json");
$json_event = json_decode($json_event, true);

$json_j = file_get_contents("../Dataset/project-files/judgment.json");
$json_j = json_decode($json_j,true);
if($json_j != null){
    foreach($json_j as $document => $value){
        if(strcmp($_POST['localUrl'],$document) == 0){
            if(isset($value[$_POST['user']])){
                $output["judgment"] = $value[$_POST['user']];
            }
        }
    }
}

if(strcmp($_POST['user'], $_SESSION["name"]) == 0){
    $output["role"] = "reviewer";
}


echo json_encode($output);