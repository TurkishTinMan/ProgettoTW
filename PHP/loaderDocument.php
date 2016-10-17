<?php

include('simple_html_dom.php');

$document = array();

$doc = new simple_html_dom();
$doc -> load_file($_POST['localUrl']);

$doc_body = $doc -> find('body', 0);

$doc_title = $doc -> find('title', 0) -> innertext;

$tags = $doc_body -> find('img');
    foreach ($tags as $tag) {
        $old_src = $tag -> src;
        $new_src_url = './Dataset/project-files/dataset/'.$old_src;
        $tag -> src = $new_src_url;
    }

$document[$doc_title] = $doc_body -> innertext;

echo json_encode($document);


?>
