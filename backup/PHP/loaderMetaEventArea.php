<?php
include('simple_html_dom.php');
$output = file_get_contents("../Dataset/project-files/events.json");
$output = json_decode($output,true);
$a = (int)$_POST['numberEvent'];
echo json_encode($output[$a]);
?>