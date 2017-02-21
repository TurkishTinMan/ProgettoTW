<?php
session_start();
include('simple_html_dom.php');
include('dbManager.php');
$doc = file_get_html($_POST['localUrl'], $use_include_path = false, $context=null, $offset = -1, $maxLen=-1, $lowercase = true, $forceTagsClosed=true, $target_charset = DEFAULT_TARGET_CHARSET, $stripRN=false, $defaultBRText=DEFAULT_BR_TEXT, $defaultSpanText=DEFAULT_SPAN_TEXT);

$rwJudgejson = array();
$output = array();

if($doc->find('head',0)->find('script[id=reviewerjudgment]',0)) {
    $rwJudgejson = json_decode($doc->find('head',0)->find('script[id=reviewerjudgment]',0)->innertext,true);
}

$rwJudgejson[$_SESSION['name']]['judge'] = $_POST['judge'];

if($doc->find('head',0)->find('script[id=reviewerjudgment]',0)) {
    $rwJudge = $doc->find('head',0)->find('script[id=reviewerjudgment]',0);
    $rwJudge->innertext = json_encode($rwJudgejson);
}else{
    $script =  "\n<!-- Judgement  script -->\n<script id='reviewerjudgment'>\n".json_encode($rwJudgejson)."\n</script>";
    $doc->find('head',0)->innertext .= $script;
}

file_put_contents($_POST['localUrl'],$doc);
$output["esito"]="success";
$output["content"]="Giudizio salvate con successo!";

echo json_encode($output);
?>