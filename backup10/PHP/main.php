<?php
include('./PHP/mailer.php');
include('./PHP/dbManager.php');

$JudgmentChairFileUrl = "./Dataset/project-files/chairjudgment.json";
$JudgmentReviwerFileUrl = "./Dataset/project-files/judgment.json";
$UserFileUrl = "./Dataset/project-files/users.json";
$TempUserFileUrl = "./Dataset/project-files/temp-users.json";
$AnnotationFileUrl = "./Dataset/project-files/annotations.json";

function setting(){
    session_start();
    $_SESSION["eventrole"] = "None";
    $_SESSION['Annotator'] = "false";
    if(!isset($_SESSION["userrole"])){
        $_SESSION["userrole"] = "Reader";
    }
}

function getGUID(){
    if (function_exists('com_create_guid')){
        return com_create_guid();
    }
    else {
        mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $hyphen = chr(45);
        $uuid = substr($charid, 0, 8).$hyphen
            .substr($charid, 8, 4).$hyphen
            .substr($charid,12, 4).$hyphen
            .substr($charid,16, 4).$hyphen
            .substr($charid,20,12);
        return $uuid;
    }
}

function postManager($type){
    switch($type){
        case 'changepassword':
            if(empty($_POST['oldPass']) || empty($_POST['newPass']) || empty($_POST['newPass2']) || (strcmp($_POST['newPass'],$_POST['newPass2']) != 0)){
                $error = "Campi immessi non validi";
            }else{
                $json_a = load($GLOBALS['UserFileUrl']);
                foreach ($json_a as $key => $person_name) {
                    if(strcmp($person_name['email'], $_SESSION["email"]) == 0){
                        if(strcmp($person_name['pass'] == $_POST['oldPass']) == 0){
                            $person_name["pass"] = $_POST['newPass'];
                            $json_a[$key] = $person_name;
                            $success = "Password cambiata con successo!";
                        }else{
                            $error = "Password errata!";
                        }
                    }
                }
                write($GLOBALS['UserFileUrl'],$json_a);
            }
            break;
        case 'logout':
            session_unset();
            unset($_SESSION["userrole"]);
            unset($_SESSION["name"]);
            unset($_SESSION["given_name"]);
            unset($_SESSION["family_name"]);
            unset($_SESSION["sex"]);       
            unset($_SESSION['Annotator']);
            $success = "Logout complete!";
            break;
        case 'skiplogin':
            $_SESSION["userrole"] = "Reader";
            $_SESSION["name"] = "Utente";
            break;
        case 'login':
            $user = true;
            $json_a = load($GLOBALS['UserFileUrl']);
            foreach ($json_a as $key => $person_name) {
                if($person_name['email'] == $_POST['email']){
                    $user = false;
                    if($person_name['pass'] == $_POST['password']){
                        $_SESSION["name"] = $key;
                        $_SESSION["email"] = $person_name['email'];
                        $_SESSION["given_name"] = $person_name['given_name'];
                        $_SESSION["family_name"] = $person_name['family_name'];
                        $_SESSION["sex"] = $person_name['sex'];                     
                    }else{
                        $error = "Wrong password!";
                    }
                }
            }

            if($user){
                $error = "User don't exist!";
            }else{
                if(isset($_SESSION["name"])){
                    $success = "Login Success!";
                }
            }
        break;
        case 'registrazione':
            $regsuccess = true;
            if(empty($_POST['given_name'])){
                $error = "Empty name!";
                $regsuccess = false;
            }
            if(empty($_POST['family_name'])){
                $error = "Empty cognome!";
                $regsuccess = false;
            }
            if(empty($_POST['email'])){
                $error = "Empty email!";
                $regsuccess = false;

            }
            if(empty($_POST['pass1'])){
                $error = "Empty password!";
                $regsuccess = false;
            }
            if(empty($_POST['pass2'])){
                $error = "Empty conferma password!";
                $regsuccess = false;
            }
            if(strcmp($_POST['pass1'],$_POST['pass2']) != 0){
                $error = "Password non corrispondono!";
                $regsuccess = false;
            }
            if($regsuccess){
                $json_a = load($GLOBALS['TempUserFileUrl']);
                $cont = true;
                if($json_a != null){
                    foreach ($json_a as $key => $person_name) {
                        if(strcmp($person_name['email'], $_POST['email']) == 0){
                            $cont = false;    
                        }
                    }
                }                    
                $json_b = load($GLOBALS['UserFileUrl']);
                if($json_b != null){
                    foreach ($json_b as $key => $person_name) {
                        if(strcmp($person_name['email'], $_POST['email']) == 0){
                            $cont = false;    
                        }
                    }
                }


                if($cont){
                    if(SendMail($json_a)){
                        $success="E-mail inviata, seguire le istruzioni per completare la registrazione!";
                    }else{
                        $error="Errore Interno!";
                    }
                }else
                    $error="Email già registrata!";
                }
            break;
    }
    
/* Esecuzione eventuale del notify e del caricamento del documento modificato, annotato o giudicato con jQuery */
    if(isset($success)){
?>
    <script>
        Notify('success','<?php echo $success; ?>');
    </script>
<?php
    }
    if(isset($error)){
?>
    <script>
        Notify('error','<?php echo $error; ?>');
    </script>
<?php
    }
    if(isset($returnEvent)){
?>
    <script>
        $( document ).ready(function(){
            currentEvent = <?php echo $returnEvent; ?>;
            LoadDocument('<?php echo $returnDoc; ?>');
        });
    </script>
<?php    
    }
}


function verify($GETGUID){
    $json_a = load($GLOBALS['TempUserFileUrl']);
    $cont = false;
    $user = array();        
    
    if($json_a != null){
        foreach ($json_a as $key => $person_name) {
            if(strcmp($person_name['GUID'], $GETGUID) == 0){
                $cont = true;
                $user = $key;  
                unset($json_a[$user]['GUID']);
            }
        }
    }
            
    if($cont){
        $json_b = load($GLOBALS['UserFileUrl']);
        $new_key = $json_a[$user]['given_name']." ".$json_a[$user]['family_name']." <".$json_a[$user]['email'].">";
        $json_b[$new_key] = $json_a[$user];
                
        write($GLOBALS['UserFileUrl'],$json_b);
        unset($json_a[$user]);
        write($GLOBALS['TempUserFileUrl'],$json_a);        
?>
    <script>
        Notify('success',"Registrazione completata, da ora è possibile effettuare l'accesso!");
    </script>  
<?php
    }else{
?>
    <script>
        Notify('error',"Codice di verifica non valido o già usato!");
    </script>  
<?php
    }
}
?>