<?php
session_start();
include('simple_html_dom.php');
include('dbManager.php');

$document = array();
$doc = new simple_html_dom();
$doc = file_get_html($_POST['localUrl'], $use_include_path = false, $context=null, $offset = -1, $maxLen=-1, $lowercase = true, $forceTagsClosed=true, $target_charset = DEFAULT_TARGET_CHARSET, $stripRN=false, $defaultBRText=DEFAULT_BR_TEXT, $defaultSpanText=DEFAULT_SPAN_TEXT);


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
    if (0 !== strpos($old_src, 'http') && 0 !== strcmp($old_src, '')) {
        $new_src_url = './Dataset/project-files/dataset/'.$old_src;
        $script -> src = $new_src_url;
    }
}


$styles = $doc_body -> find('link');
foreach ($styles as $style) {
    $old_src = $style -> src;
    if (0 !== strpos($old_src, 'http') && 0 !== strcmp($old_src, '')) {
        $new_src_url = './Dataset/project-files/dataset/'.$old_src;
        $style -> src = $new_src_url;
    }
}

$links = $doc_body -> find('a');
foreach ($links as $link) {
    $old_src = $link -> href;
    if (0 !== strpos($old_src, 'http') && 0 !== strcmp($old_src, '')) {
        $new_src_url = './Dataset/project-files/dataset/js/'.$old_src;
        $link -> href = $new_src_url;
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
    if($meta -> name == "dc.creator"){
        $document["Autori"][$meta -> about]["name"] = $meta -> content;
        $document["Autori"][$meta -> about]["linked"] = "none";
        $document["Autori"][$meta -> about]["about"] = $meta -> about;
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
$document["body"] = $doc_body -> innertext ;

$json_event = load("../Dataset/project-files/events.json");
$a = (int)$_POST['currentEvent'];
if($a >= 0){
    foreach($json_event[$a]["submissions"] as $paper){
        if(strcmp($paper["url"],basename($_POST['localUrl'])) == 0){
            $document["reviewers"] = $paper["reviewers"]; 
        }
    }
    $document["chairJudgment"] = false;
    if(strcmp($_SESSION['eventrole'],"Chair") == 0){    
        $document["chairJudgment"] = true;

        $json_j = load("../Dataset/project-files/judgment.json");
        if(isset($document["reviewers"])){
            foreach($document["reviewers"] as $reviewer){
                if(!isset($json_j[$_POST['localUrl']][$reviewer])){
                    $document["chairJudgment"] = false;
                }
            }
        }
    }
    $json_jc = load("../Dataset/project-files/chairjudgment.json");
    if(isset($json_jc[$_POST['localUrl']])){
        $document["chairJudgmentvalue"] = $json_jc[$_POST['localUrl']];
    }
}
echo json_encode($document);
?>
