<?php
session_start();
include('simple_html_dom.php');
include('dbManager.php');
$output = array();

$json_j = load("../Dataset/project-files/judgment.json");
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