<?php
    session_start();
    $string = file_get_contents("../Dataset/project-files/users.json");
    $json_a = json_decode($string,true);
    foreach ($json_a as $person_name) {
        if($person_name['email'] == $_POST['email']){
            if($person_name['pass'] == $_POST['password']){
                $_SESSION["userrole"] = "Annotator";
                $_SESSION["name"] = $person_name["given_name"];
                $_SESSION["famname"] = $person_name["family_name"];
                $_SESSION["userrole"] = "Annotator";
                header("Location: ../index.php");
                die();
            }
        }
    }
    $_SESSION["userrole"] = "Reader";
    $_SESSION["name"] = "Utente";
    header("Location: ../index.php");
    die();
?>