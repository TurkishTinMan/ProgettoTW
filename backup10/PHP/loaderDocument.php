<?php
session_start();
include('simple_html_dom.php');
include('dbManager.php');

$document = array();
$doc = new simple_html_dom();
$doc = file_get_html($_POST['localUrl'], $use_include_path = false, $context=null, $offset = -1, $maxLen=-1, $lowercase = true, $forceTagsClosed=true, $target_charset = DEFAULT_TARGET_CHARSET, $stripRN=false, $defaultBRText=DEFAULT_BR_TEXT, $defaultSpanText=DEFAULT_SPAN_TEXT);

if($doc->find('script[id=annotation]',0)){
    $document["annotations"] = json_decode($doc->find('script[id=annotation]',0)->innertext);
}else{
    $document["annotations"] = array();
}

$doc_body = $doc -> find('body',0);


$doc_title = $doc -> find('title', 0) -> innertext;
$delimiter = "--";
$titles = explode($delimiter, $doc_title);


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
$ref = [];
$refcount = 1;
$links = $doc_body -> find('a');
foreach ($links as $link) {
    $old_src = $link -> href;
    if (0 === strpos($old_src, '#')){
        if(isset($ref[$old_src])){
            $link -> innertext = "[".$ref[$old_src]."]";
        }else{
            $ref[$old_src] = $refcount;
            $link -> innertext = "[".$ref[$old_src]."]";            
            $refcount=$refcount +1;
        }
    }else{
        if (0 !== strpos($old_src, 'http') && 0 !== strcmp($old_src, '')) {
            $new_src_url = './Dataset/project-files/dataset/js/'.$old_src;
            $link -> href = $new_src_url;
        }
    }
}


$metas = $doc -> find('meta');
$document["keyword"] = array();
$document["Autori"] = array();
$document["ACM"] = array();
$counterkey = 0;
foreach($metas as $meta){
    if($meta -> name == "dcterms.subject"){
        $document["ACM"][] = (string)$meta -> content;
    }
    
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

if(isset($titles[1])){
    $document["title"] =$titles[0];
    $document["subtitle"] =$titles[1];
}else{
    $document["title"] = $doc_title;
}
$document["body"] = $doc_body -> innertext ;

$json_event = load("../Dataset/project-files/events.json");
$a = (int)$_POST['currentEvent'];
if($a >= 0){
    $reviewers = array();
    $document["reviewersjudge"] = array();
    foreach($json_event[$a]["submissions"] as $paper){
        if(strcmp($paper["url"],basename($_POST['localUrl'])) == 0){
            $reviewers = $paper["reviewers"]; 
        }
    }

    if($doc->find('script[id=reviewerjudgment]',0)){
        $document["reviewersjudge"] = json_decode($doc->find('script[id=reviewerjudgment]',0)->innertext,true);
    }
    
    $allreviewer = true;
    $_SESSION['Annotator'] = "false";
    if(strcmp($_SESSION['eventrole'],"Chair") == 0){
        $_SESSION['Annotator'] = "true";
    }
    
    foreach($reviewers as $reviewer){
        if(!array_key_exists($reviewer,$document["reviewersjudge"])){
            $document["reviewersjudge"][$reviewer]["judge"] = "Inespresso";
            $allreviewer = false;
        }
        if(strcmp($reviewer,$_SESSION['name']) == 0){
            $document["reviewersjudge"][$reviewer]["own"] = "true";
            $_SESSION['Annotator'] = "true";
        }else{
            $document["reviewersjudge"][$reviewer]["own"] = "false";
        }
    }
    $document["chairJudgment"] = false;
    if(strcmp($_SESSION['eventrole'],"Chair") == 0 && $allreviewer){    
        $document["chairJudgment"] = true;
    }
    if($doc->find('script[id=chairJudgmentvalue]',0)){
        $document["chairJudgmentvalue"] = json_decode($doc->find('script[id=chairJudgmentvalue]',0)->innertext,true)['judge'];
    }else{
        $document["chairJudgmentvalue"] = "Inespresso";       
    }
}
echo json_encode($document);
?>
