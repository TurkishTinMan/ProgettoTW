<?php
session_start();

include('simple_html_dom.php');
$output = array();
chdir('../Dataset/project-files/dataset');

$json_event = file_get_contents("../events.json");
$json_event = json_decode($json_event, true);

$json_users = file_get_contents("../users.json");
$json_users = json_decode($json_users, true);



$files = preg_grep('/^([^.])/', scandir('.'));    

$_SESSION["eventrole"] = "None";
$a = (int)$_POST['numberEvent'];
if($a >= 0){
    $documentToLoad = array();

    foreach($json_event[$a]["chairs"] as $chair){
        if (strcmp($chair, $_SESSION["name"]) == 0) {
            $_SESSION["eventrole"] = "Chair";
        }else{
            $_SESSION["eventrole"] = "PC Member";
        }
    }
    
    foreach ($json_event[$a]["submissions"] as $document) {
        array_push($documentToLoad,$document["url"]);
    } 

    foreach($files as $file){
        if(!is_dir($file) && in_array($file, $documentToLoad)){
            $html = file_get_html(basename($file));
            $title = $html->find('title',0)->innertext;
            $output['../Dataset/project-files/dataset/'.basename($file)] = $title;
        }
    }
}else{ 
    foreach($files as $file){
        if(!is_dir($file)){
            $html = file_get_html(basename($file));
            $title = $html->find('title',0)->innertext;
            $output['../Dataset/project-files/dataset/'.basename($file)] = $title;
        }
    }
}
$output["Role"] = $_SESSION["eventrole"];
echo json_encode($output);
?>