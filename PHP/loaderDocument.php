<?php

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

$headers = $doc_body -> find('header');
foreach($headers as $header){
    $header->innertext = "cacca";
}


$document[$doc_title] = $doc_body -> innertext;

echo json_encode($document);


?>
