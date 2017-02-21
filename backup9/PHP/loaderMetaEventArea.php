<?php
include('simple_html_dom.php');
include('dbManager.php');
$output = load("../Dataset/project-files/events.json");
$a = (int)$_POST['numberEvent'];
echo json_encode($output[$a]);
?>