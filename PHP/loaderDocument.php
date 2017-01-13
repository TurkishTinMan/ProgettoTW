<?php
session_start();
include('simple_html_dom.php');

$document = array();

$doc = new simple_html_dom();
$doc -> load_file($_POST['localUrl']);



$doc_body = $doc -> find('body',0);



$doc_title = $doc -> find('title', 0) -> innertext;

$tags = $doc_body -> find('img');
foreach ($tags as $tag) {
    $old_src = $tag -> src;
    $new_src_url = './Dataset/project-files/dataset/'.$old_src;
    $tag -> src = $new_src_url;
}

$scripts = $doc_body -> find('script');
foreach ($scripts as $script) {
    $old_src = $script -> src;
    if (0 !== strpos($old_src, 'http')) {
        $new_src_url = './Dataset/project-files/dataset/'.$old_src;
        $script -> src = $new_src_url;
    }
}


$styles = $doc_body -> find('link');
foreach ($styles as $style) {
    $old_src = $style -> src;
    if (0 !== strpos($old_src, 'http')) {
        $new_src_url = './Dataset/project-files/dataset/'.$old_src;
        $style -> src = $new_src_url;
    }
}

$metas = $doc -> find('meta');
$document["keyword"] = array();
$document["Autori"] = array();
$counterkey = 0;
foreach($metas as $meta){
    if($meta -> property == "prism:keyword"){
        $document["keyword"][$counterkey] = $meta -> content;
        $counterkey = $counterkey+1;
    }
    if($meta -> property == "schema:name"){
        $document["Autori"][$meta -> about]["name"] = $meta -> content;
        $document["Autori"][$meta -> about]["linked"] = "false";
        $document["Autori"][$meta -> about]["about"] = $meta -> about;
    }
    if($meta -> property == "schema:email"){
        $document["Autori"][$meta -> about]["email"] = $meta -> content;
    }
}

$links = $doc -> find('link');
foreach($links as $link){
    if($link -> property == "schema:affiliation"){
        $linkedto = $link -> href;
        foreach($document["Autori"] as $key =>$autore){
            if(!empty($linkedto) && !empty($autore["about"]) && $autore["about"] == $linkedto ){
                $document["Autori"][$link -> about]["affiliation"] = $autore["name"];
                $document["Autori"][$key]["linked"] = "true";
            }
        }
    }   
}

$document["title"] =$doc_title;
$document["body"] = $doc_body -> innertext;


$json_event = file_get_contents("../Dataset/project-files/events.json");
$json_event = json_decode($json_event, true);
$a = (int)$_POST['currentEvent'];
if($a >= 0){
    foreach($json_event[$a]["submissions"] as $paper){
        if(strcmp($paper["url"],basename($_POST['localUrl'])) == 0){
            $document["reviewers"] = $paper["reviewers"]; 
        }
    }
    if(strcmp($_SESSION['userrole'],"Chair")){    
        $document["chairJudgment"] = true;

        $json_j = file_get_contents("../Dataset/project-files/judgment.json");
        $json_j = json_decode($json_j,true);
        foreach($document["reviewers"] as $reviewer){
            if(!isset($json_j[$_POST['localUrl']][$reviewer])){
                $document["chairJudgment"] = false;
            }
        }
    }
    $json_jc = file_get_contents("../Dataset/project-files/chairjudgment.json");
    $json_jc = json_decode($json_jc,true);
    if(isset($json_jc[$_POST['localUrl']])){
        $document["chairJudgmentvalue"] = $json_jc[$_POST['localUrl']];
        $document["chairJudgment"] = false;
    }
}
echo json_encode($document);


?>
