<?php
session_start();
include('simple_html_dom.php');
include('dbManager.php');
$json_e = load("../Dataset/project-files/events.json");
if(isset($_SESSION["name"])){
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
}
$output = json_encode($json_e);
echo $output;
?>