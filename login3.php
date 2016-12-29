<?php
session_start();
$page_to="page.html";

if(!isset($_SESSION["userrole"])){
    $_SESSION["userrole"] = "Reader";
    $_SESSION["name"] = "Utente";
    $_SESSION["eventrole"] = "None";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    switch($_POST["type"]){
        case 'login':
            $_SESSION["userrole"] = "Reader";
            $_SESSION["name"] = "Utente";
            $_SESSION["eventrole"] = "None";
            $user = true;

            $string = file_get_contents("./Dataset/project-files/users.json");
            $json_a = json_decode($string,true);
            foreach ($json_a as $key => $person_name) {
                if($person_name['email'] == $_POST['email']){
                    $user = false;
                    if($person_name['pass'] == $_POST['password']){
                        $_SESSION["userrole"] = "Annotator";
                        $_SESSION["name"] = $key;
                    }else{


                      header("Location:login.php?error_pass=1");


                    }
                }
            }

            if($_SESSION["name"] == "Utente"){
                if($user){

                    header("Location:login.php?error_user=1");


                }
            }else{

                    header("Location:" .$page_to);
}
}
}

?>
