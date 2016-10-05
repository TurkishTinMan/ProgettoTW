<?php
include('simple_html_dom.php');
$html = file_get_html($_POST['localUrl']);
echo $html;
?>