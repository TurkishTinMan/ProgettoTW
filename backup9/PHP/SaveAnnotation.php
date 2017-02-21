<?php
session_start();
include('simple_html_dom.php');
include('dbManager.php');
$doc = file_get_html($_POST['localUrl'], $use_include_path = false, $context=null, $offset = -1, $maxLen=-1, $lowercase = true, $forceTagsClosed=true, $target_charset = DEFAULT_TARGET_CHARSET, $stripRN=false, $defaultBRText=DEFAULT_BR_TEXT, $defaultSpanText=DEFAULT_SPAN_TEXT);
$output = array();

if(!$doc->find('script[id=annotation]',0)) {
    $doc->find('head',0)->innertext .= "\n<!-- Annotation script -->\n<script id='annotation'></script>";
    file_put_contents($_POST['localUrl'],$doc);
    $doc = file_get_html($_POST['localUrl'], $use_include_path = false, $context=null, $offset = -1, $maxLen=-1, $lowercase = true, $forceTagsClosed=true, $target_charset = DEFAULT_TARGET_CHARSET, $stripRN=false, $defaultBRText=DEFAULT_BR_TEXT, $defaultSpanText=DEFAULT_SPAN_TEXT);

}

$annotation = $doc->find('script[id=annotation]',0);
if(isset($_POST['annotations'])){
    $annotation->innertext = "\n".json_encode($_POST['annotations'])."\n";
}else{
    $annotation->innertext = "";
}


file_put_contents($_POST['localUrl'],$doc);
$output["esito"]="success";
$output["content"]="Annotatzioni salvate con successo!";

echo json_encode($output);
?>