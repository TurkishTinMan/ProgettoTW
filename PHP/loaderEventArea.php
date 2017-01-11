<?php
session_start();
include('simple_html_dom.php');
$events = file_get_contents("../Dataset/project-files/events.json");
$json_e = json_decode($events,true);
foreach ($json_e as $key=>$event) {
    $cantsee = true;
    foreach($event["chairs"] as $chair){
        if(strcmp($chair,$_SESSION["name"]) == 0){
            $cantsee = false;
        }
    }
    
    foreach($event["pc_members"] as $pc_member){
        if(strcmp($pc_member,$_SESSION["name"]) == 0){
            $cantsee = false;
        }
    }
    
    if($cantsee){
        unset($json_e[$key]);
    }
}

$output = json_encode($json_e);
echo $output;
?>