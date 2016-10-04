<?php
include('simple_html_dom.php');
$output = array();
chdir('../Dataset/project-files/dataset');
$files = scandir('.');
foreach($files as $file){
    if(!is_dir($file)){
        $html = file_get_html(basename($file));
        $title = $html->find('title',0)->innertext;
        $output['./Dataset/project-files/dataset/'.basename($file)] = $title;
    }
}

echo json_encode($output);
?>