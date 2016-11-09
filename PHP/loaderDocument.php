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
            if($autore["about"] == $linkedto ){
                $document["Autori"][$link -> about]["affiliation"] = $autore["name"];
                $document["Autori"][$key]["linked"] = "true";
            }
        }
    }   
}

$document["title"] =$doc_title;
$document["body"] = $doc_body -> innertext;

echo json_encode($document);


?>
