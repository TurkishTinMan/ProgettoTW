<?php
include('simple_html_dom.php');
$output = array();
$json = file_get_contents("../Dataset/project-files/annotations.json");
$json = json_decode($json,true);

foreach($json as $value){
    if(strcmp($_POST['localUrl'],$value["Doc"]) == 0){
        array_push($output,$value);
    }
}
echo json_encode($output);
?>