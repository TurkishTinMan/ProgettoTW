<?php
session_start();
include('simple_html_dom.php');
include('dbManager.php');
$output = array();
if($_SESSION["email"] == $_POST['author']){
    $json = load("../Dataset/project-files/annotations.json");
    $json_new = array();
    if($json != null){
        foreach($json as $key=>$value){
            if($value["Data"] == $_POST['data']){
                $output["refresh"] = $value['Doc'];
                unset($json[$key]);
            }
        }
    }
    write("../Dataset/project-files/annotations.json",$json);
}else{
    $output["error"] = "Annotazione non posseduta dall'utente loggato!";
}
echo json_encode($output);
?>