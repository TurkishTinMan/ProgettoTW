<?php

include('simple_html_dom.php');

$document = array();

$doc_body = new simple_html_dom();
$doc_body -> load_file($_POST['localUrl']);

$doc_title = $doc_body -> find('title',0) -> innertext;

$document[$doc_title] = $doc_body -> innertext;

echo json_encode($document);


?>
