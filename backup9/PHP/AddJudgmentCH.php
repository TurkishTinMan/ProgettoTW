<?php
session_start();
include('simple_html_dom.php');
include('dbManager.php');
$doc = file_get_html($_POST['localUrl'], $use_include_path = false, $context=null, $offset = -1, $maxLen=-1, $lowercase = true, $forceTagsClosed=true, $target_charset = DEFAULT_TARGET_CHARSET, $stripRN=false, $defaultBRText=DEFAULT_BR_TEXT, $defaultSpanText=DEFAULT_SPAN_TEXT);

$chJudgejson = array();
$output = array();

if($doc->find('head',0)->find('script[id=chairJudgmentvalue]',0)) {
    $chJudgejson = json_decode($doc->find('head',0)->find('script[id=chairJudgmentvalue]',0)->innertext,true);
}

$chJudgejson['judge'] = $_POST['judge'];

if($doc->find('head',0)->find('script[id=chairJudgmentvalue]',0)) {
    $chJudge = $doc->find('head',0)->find('script[id=chairJudgmentvalue]',0);
    $chJudge->innertext = json_encode($chJudgejson);
}else{
    $script =  "\n<!-- Judgement Chair  script -->\n<script id='chairJudgmentvalue'>\n".json_encode($chJudgejson)."\n</script>";
    $doc->find('head',0)->innertext .= $script;
}

file_put_contents($_POST['localUrl'],$doc);
$output["esito"]="success";
$output["content"]="Giudizio salvate con successo!";

echo json_encode($output);
?>