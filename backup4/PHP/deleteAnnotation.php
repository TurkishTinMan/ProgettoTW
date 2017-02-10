<?php
session_start();
include('simple_html_dom.php');
$output = array();
if($_SESSION["email"] == $_POST['author']){
    $json = file_get_contents("../Dataset/project-files/annotations.json");
    $json = json_decode($json,true);
    $json_new = array();
    if($json != null){
        foreach($json as $key=>$value){
            if($value["Data"] == $_POST['data']){
                $output["refresh"] = $value['Doc'];
                unset($json[$key]);
            }
        }
    }
    file_put_contents("../Dataset/project-files/annotations.json",json_encode($json));
}else{
    $output["error"] = "Annotazione non posseduta dall'utente loggato!";
}
echo json_encode($output);
?>