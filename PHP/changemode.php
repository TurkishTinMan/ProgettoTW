<?php
session_start();
$output= array();
if(isset($_SESSION['Annotator']) && strcmp($_SESSION['Annotator'],"true")==0){
    $output["esito"] = "success";
    $output["contenuto"] = "Cambio avvenuto con successo";
}else{
    $output["esito"] = "info";
    $output["contenuto"] = "Non hai i permessi";
}
echo json_encode($output);
?>