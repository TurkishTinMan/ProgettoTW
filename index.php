<!DOCTYPE html>
<html>
<head>
    
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title>EasyRASH</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <link rel="shortcut icon" type="image/png" href="image/favicon.png"/>

    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/styleh.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto+Slab">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto">
    
    <link href='https://fonts.googleapis.com/css?family=Philosopher' rel='stylesheet' type='text/css'> 
    <link href='https://fonts.googleapis.com/css?family=Muli' rel='stylesheet' type='text/css'>
    
    <script  src="https://code.jquery.com/jquery-3.1.1.min.js"   integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script>
    
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

    <script src="js/script.js" type="text/javascript"></script>

    
</head>

<body>

<div id="notification"></div>    

<script src="js/scriptlog.js" type="text/javascript"></script>   
<?php
function getGUID(){
    if (function_exists('com_create_guid')){
        return com_create_guid();
    }
    else {
        mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $hyphen = chr(45);// "-"
        $uuid = chr(123)// "{"
            .substr($charid, 0, 8).$hyphen
            .substr($charid, 8, 4).$hyphen
            .substr($charid,12, 4).$hyphen
            .substr($charid,16, 4).$hyphen
            .substr($charid,20,12)
            .chr(125);// "}"
        return $uuid;
    }
}

session_start();

if(!isset($_SESSION["eventrole"])){
    $_SESSION["eventrole"] = "None";
}
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        switch($_POST["type"]){
            case 'addChairJudgment':

?>
                <script>
                    $( document ).ready(function(){
                        ChangeEvent("<?php echo $_POST['Event'] ?>");
                        LoadDocument("<?php echo $_POST['Doc'] ?>");
                    });
                </script>
<?php  
                break;
            
            case 'addJudgment':
                if(empty($_POST['judgment'])){
?>
                                <script>
                                    Notify('error',"Devi immettere un giudizio");
                                </script>
<?php                
                }else{
                    $string = file_get_contents("./Dataset/project-files/judgment.json");
                    $json_j = json_decode($string,true);
                    $json_j[$_POST['Doc']][$_SESSION["name"]] = $_POST['judgment'];
                    $json_j = json_encode($json_j);
                    file_put_contents("./Dataset/project-files/judgment.json",$json_j);

                    
?>
                    <script>
                        Notify('success',"Giudizio aggiunto con successo!");
                    </script>  
<?php  
                }
?>
                <script>
                    $( document ).ready(function(){
                        ChangeEvent("<?php echo $_POST['Event'] ?>");
                        LoadDocument("<?php echo $_POST['Doc'] ?>");
                    });
                </script>
<?php  
                break;
            
            case 'changepassword':
                if(empty($_POST['oldPass']) || empty($_POST['newPass']) || empty($_POST['newPass2']) || (strcmp($_POST['newPass'],$_POST['newPass2']) != 0)){
?>
                                <script>
                                    Notify('error',"Wrong password!");
                                </script>
<?php               
                }else{
                    $string = file_get_contents("./Dataset/project-files/users.json");
                    $json_a = json_decode($string,true);
                    foreach ($json_a as $key => $person_name) {
                        if($person_name['email'] == $_SESSION["email"]){
                            if($person_name['pass'] == $_POST['oldPass']){
                                $person_name["pass"] = $_POST['newPass'];
                                $json_a[$key] = $person_name;
?>
                                <script>
                                    Notify('success',"Password cambiata con successo!");
                                </script>
<?php   
                            }else{
?>
                                <script>
                                    Notify('error',"Wrong password!");
                                </script>
<?php                        
                            }
                        }
                    }
                    $json_a = json_encode($json_a);
                    file_put_contents("./Dataset/project-files/users.json",$json_a);
                }
                break;
            case 'logout':
                session_unset();
                unset($_SESSION["userrole"]);
?>
                            <script>
                                Notify('success',"Logout complete!");
                            </script>
<?php          
                break;
            case 'skiplogin':
                $_SESSION["userrole"] = "Reader";
                $_SESSION["name"] = "Utente";
                break;
            case 'login':
                $user = true;
            
                $string = file_get_contents("./Dataset/project-files/users.json");
                $json_a = json_decode($string,true);
                foreach ($json_a as $key => $person_name) {
                    if($person_name['email'] == $_POST['email']){
                        $user = false;
                        if($person_name['pass'] == $_POST['password']){
                            $_SESSION["userrole"] = "Annotator";
                            $_SESSION["name"] = $key;
                            $_SESSION["email"] = $person_name['email'];
                        }else{
?>
                            <script>
                                Notify('error',"Wrong password!");
                            </script>
<?php                        
                        }
                    }
                }
            
                if($user){
?>
                    <script>
                        Notify('error',"User don't exist!");
                    </script>
<?php
                }else{
                    if(isset($_SESSION["name"])){
?>
                    <script>
                        Notify('success',"Login Success!");
                    </script>
<?php    
                    }
                }
            break;
            case 'registrazione':
                $success = true;
                if(empty($_POST['given_name'])){
?>
                    <script>
                        Notify('error',"Empty name!");
                    </script>  
<?php              
                    $success = false;
                }
                if(empty($_POST['family_name'])){
?>
                    <script>
                        Notify('error',"Empty cognome!");
                    </script>  
<?php              
                    $success = false;

                }
            
                if(empty($_POST['email'])){
?>
                    <script>
                        Notify('error',"Empty email!");
                    </script>  
<?php              
                    $success = false;

                }
            
                if(empty($_POST['pass1'])){
?>
                <script>
                    Notify('error',"Empty password!");
                </script>  
<?php              
                    $success = false;

                }
            
                if(empty($_POST['pass2'])){
?>
                    <script>
                        Notify('error',"Empty conferma password!");
                    </script>  
<?php              
                    $success = false;

                }
            
                if($_POST['pass1'] != $_POST['pass2']){
?>
                    <script>
                        Notify('error',"Password conferma non corrisponde a password!");
                    </script>  
<?php              
                    $success = false;

                }
            
                if($success){
                    $string = file_get_contents("./Dataset/project-files/temp-users.json");
                    $json_a = json_decode($string,true);
                    $cont = true;
                    
                    if($json_a != null){
                        foreach ($json_a as $key => $person_name) {
                            if(strcmp($person_name['email'], $_POST['email']) == 0){
                                $cont = false;    
                            }
                        }
                    }
                    
                    $string_b = file_get_contents("./Dataset/project-files/users.json");
                    $json_b = json_decode($string_b,true);
                    
                    if($json_b != null){
                        foreach ($json_b as $key => $person_name) {
                            if(strcmp($person_name['email'], $_POST['email']) == 0){
                                $cont = false;    
                            }
                        }
                    }

                    
                    if($cont){
                        $GUID = getGUID();
                        $new_person['given_name'] = $_POST['given_name'];
                        $new_person['family_name'] = $_POST['family_name'];
                        $new_person['email'] = $_POST['email'];
                        $new_person['pass'] = $_POST['pass1'];
                        $new_person['sex'] = $_POST['sex'];
                        $new_person['GUID'] = $GUID;
                        /*TsU{7pCCePneBtAT*/
                        
                        require_once('./PHP/class.phpmailer.php');

                        $mail             = new PHPMailer();

                        $body             = file_get_contents('./extra/contents.html');
                        $body             = eregi_replace("[\]",'',$body);
                        $body             = str_replace("#linktoreplace",$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?verify=".$GUID,$body);
                        

                        $mail->IsSMTP();
                        $mail->Host       = "mail.yourdomain.com"; 
                                          
                        $mail->SMTPAuth   = true;                  
                        $mail->SMTPSecure = "tls";                 
                        
                        $mail->Host       = "smtp.gmail.com";      
                        $mail->Port       = 587;                    
                        
                        $mail->Username   = "noreplyeasyrash@gmail.com";  // GMAIL username
                        $mail->Password   = "TsU{7pCCePneBtAT";            // GMAIL password

                        $mail->SetFrom('noreplyeasyrash@gmail.com', 'EasyRash');

                        $mail->AddReplyTo("noreplyeasyrash@gmail.com","EasyRash");

                        $mail->Subject    = "Mail di verifica";

                        $mail->AltBody    = "To view the message, please use an HTML compatible email viewer!";

                        $mail->MsgHTML($body);

                        $address = $_POST['email'];
                        $mail->AddAddress($address, $_POST['given_name']." ".$_POST['family_name']);

                        if(!$mail->Send()) {
?>
                            <script>
                                Notify('error',"Problemi interni, impossibile mandare la mail, riprovare più tardi");
                            </script>  
<?php
                        } else {
                            $new_key = $_POST['given_name']." ".$_POST['family_name']." <".$_POST['email'].">";
                            $json_a[$new_key] = $new_person;
                            $json_a = json_encode($json_a);
                            if(!file_exists("./Dataset/project-files/temp-users.json")){
                                touch("./Dataset/project-files/temp-users.json");
                            }
                            file_put_contents("./Dataset/project-files/temp-users.json",$json_a);
?>
                            <script>
                                Notify('success',"Registrazione avvenuta con successo, a breve riceverai una mail per completare la registrazione!");
                            </script>  
<?php          

                        }
                    }else{
?>
                        <script>
                            Notify('error',"Email già registrata!");
                        </script>  
<?php     
                    }
                }

            break;
            case 'addAnnotation':
                if(!isset($_POST['Annotation']) || !isset($_POST['OffsetFromStart']) || !isset($_POST['Path']) || !isset($_POST['Data']) || !isset($_POST['Doc']) || !isset($_POST['LenghtAnnotation'])){
?>
                    <script>
                        Notify('error',"Internal Error!");
                    </script>  
<?php              
                }else{
                    if(!file_exists("./Dataset/project-files/annotations.json")){
                        file_put_contents("./Dataset/project-files/annotations.json", '');
                    }
                    $string = file_get_contents("./Dataset/project-files/annotations.json");
                    $json_a = json_decode($string,true);

                    $new_annotation["Author"] = $_SESSION["email"];
                    $new_annotation["Annotation"] = $_POST['Annotation'];
                    $new_annotation["Path"] = $_POST['Path'];
                    $new_annotation["OffsetFromStart"] = $_POST['OffsetFromStart'];
                    $new_annotation["LenghtAnnotation"] = $_POST['LenghtAnnotation'];
                    $new_annotation["Data"] = $_POST['Data'];
                    $new_annotation["Doc"] = $_POST['Doc'];
                    
                    if(empty($json_a)){
                        $json_a = array();
                    }
                    array_push($json_a,$new_annotation);
                    file_put_contents("./Dataset/project-files/annotations.json",json_encode($json_a));
?>
                    <script>
                        Notify('success',"Annotazione aggiunta con successo!");
                        $( document ).ready(function(){
                            ChangeEvent("<?php echo $_POST['Event'] ?>");
                            LoadDocument("<?php echo $_POST['Doc'] ?>");
                        });
                    </script>  
<?php  
                }
                break;
        }
    }else{
        if (isset($_GET["verify"])){
            $GETGUID = $_GET["verify"];
            $string = file_get_contents("./Dataset/project-files/temp-users.json");
            $json_a = json_decode($string,true);
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
                $string_b = file_get_contents("./Dataset/project-files/users.json");
                $json_b = json_decode($string_b,true);

                $new_key = $json_a[$user]['given_name']." ".$json_a[$user]['family_name']." <".$json_a[$user]['email'].">";
                $json_b[$new_key] = $json_a[$user];
                
                if(!file_exists("./Dataset/project-files/users.json")){
                    touch("./Dataset/project-files/users.json");
                }
                $json_b = json_encode($json_b);
                file_put_contents("./Dataset/project-files/users.json",$json_b);
                unset($json_a[$user]);
                
                $json_a = json_encode($json_a);
                if(!file_exists("./Dataset/project-files/temp-users.json")){
                    touch("./Dataset/project-files/temp-users.json");
                }
                file_put_contents("./Dataset/project-files/temp-users.json",$json_a);
                
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
    
    }

if(!isset($_SESSION["userrole"])) :?>
<script src="js/scriptlog.js" type="text/javascript"></script>    
    
<div class="container">
  <div class="row">
    <div class="col-md-4 col-md-offset-4">
      <div class="flip">
        <div class="card"> 
          <div class="face front"> 
            <div class="panel panel-default">
              <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="form-horizontal customform">
                <br>
                <div align="center">
                  <div class="image">
                    <img src="image/logo.png"/>
                  </div>
                  <br>
                </div>
                <input type="hidden" name="type" value="login">
                <input name="email" class="form-control" placeholder="Username"/>
                <input name="password" type="password" class="form-control" placeholder="Password" required="" id="pass_s">
                <br>
                <input type="submit" class="btn btn-primary btn-block" value="LOG IN">
              </form>
                <hr>
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" id="hiddenform">
                <input type="hidden" name="type" value="skiplogin">
                <p class="blue">
                <a onclick="$('#hiddenform').submit()"> Skip to EasyRASH </a>
                </p>
              </form>
                <hr>
                <p class="text-center">
                  <a href="#" class="fliper-btn">Create new account?</a>
                </p>
            </div>
          </div> 
          <div class="face back"> 
            <div class="panel panel-default">
                <br> 
                <form class="form-horizontal customform" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <div align="center">
                  <div class="image">
                    <img src="image/logo.png"/>
                  </div>
                  <br>

                    
                  <input type="hidden" name="type" value="registrazione">
                  <input name="given_name" class="form-control" placeholder="Name"/>
                  <input name="family_name" class="form-control" placeholder="Surname"/>
                  <input class="form-control" placeholder="Email" name="email"/>
                  <input type="password" class="form-control" placeholder="Password" required="" id="pass_s" name="pass1">
                  <input type="password" class="form-control" placeholder="Password" required="" id="pass_s" name="pass2">
                  <select name="sex" class="form-control" id="gender">
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                  </select>
                    <input type="submit" class="btn btn-primary btn-block" value="REGISTRAZIONE">
                </form>
                <br>

                <p class="text-center">
                  <a href="#" class="fliper-btn">Already have an account?</a>
                </p>
            </div>
          </div>
        </div>   
      </div>




        </div>
        <div class="col-md-4"></div>

      </div>

    </div><!-- /.container -->

<script type="text/javascript">
$('.fliper-btn').click(function(){
    $('.flip').find('.card').toggleClass('flipped');
});
</script>
    
<?php else: ?>
      
<div id="ViewChairJudgment"class="modal fade" id="info" tabindex="-1" role="dialog" aria-labelledby="infoLabel" aria-hidden="true">
<div class="modal-dialog">
<div class="panel panel-primary">
    <div class="panel-heading">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 class="panel-title" id="infoLabel"> Assegna un giudizio al documento </i></h3>
    </div>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" accept-charset="utf-8">
    <div class="modal-body">
        <h3>Sei un Chair</h3>
        <p>Tutti i reviewer si sono espressi sul documento, manca solo il tuo giudizio.</p>
        <p id="resumereviewers"></p>
        <input type="hidden" name="type" value="addChairJudgment">
        <input name="Doc" type="hidden" class="form-control" id="Doc" value="">
        <input name="Event" type="hidden" class="form-control" id="Eventid" value="">
        <div class="radio">
          <label><input type="radio" name="judgment" value="Rejected">Rejected</label>
        </div>
        <div class="radio">
          <label><input type="radio" name="judgment" value="Accepted">Accepted</label>
        </div>        
    </div>
        <div class="panel-footer">
            <button type="submit" class="btn btn-default">Submit</button>
            <button type="button" style="float: right;" class="btn btn-default btn-close" data-dismiss="modal">Close</button>
        </div>
        </form>
    </div>
</div>
</div>

    
    
    
<div id="ViewJudgmentModal"class="modal fade" id="info" tabindex="-1" role="dialog" aria-labelledby="infoLabel" aria-hidden="true">
<div class="modal-dialog">
<div class="panel panel-primary">
    <div class="panel-heading">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 class="panel-title" id="infoLabel"> Assegna un giudizio al documento </i></h3>
    </div>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" accept-charset="utf-8">
    <div class="modal-body">
        <h3>Sei un Reviewer</h3>
        <p>bls bla bla bla</p>
        <p>qui sotto metto la form ma tranquillo puoi sempre riaprire questo modal quando vorrai basta cliccare sul tuo nome fra i membri, puoi sempre cambiare il tuo giudizio</p>
        <input type="hidden" name="type" value="addJudgment">
        <input name="Doc" type="hidden" class="form-control" id="Doc1" value="">
        <input name="Event" type="hidden" class="form-control" id="Eventid1" value="">
        <div class="radio">
          <label><input type="radio" name="judgment" value="Rejected">Rejected</label>
        </div>
        <div class="radio">
          <label><input type="radio" name="judgment" value="Modification Request">Modification Request</label>
        </div>
        <div class="radio">
          <label><input type="radio" name="judgment" value="Accepted">Accepted</label>
        </div>        
    </div>
        <div class="panel-footer">
            <button type="submit" class="btn btn-default">Submit</button>
            <button type="button" style="float: right;" class="btn btn-default btn-close" data-dismiss="modal">Close</button>
        </div>
        </form>
    </div>
</div>
</div>
    
    
<!--Info Modal-->
<div id="ViewHelp" class="modal fade" id="info" tabindex="-1" role="dialog" aria-labelledby="infoLabel" aria-hidden="true"></li>
<div class="modal-dialog">
<div class="panel panel-primary">
    <div class="panel-heading">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 class="panel-title" id="infoLabel"> Help <i class="glyphicon glyphicon-info-sign"></i></h3>
    </div>
    <form action="#" method="post" accept-charset="utf-8">
    <div class="modal-body">
                              <h3>How does EasyRASH work</h3>
                        <p>Bacon ipsum dolor amet bacon prosciutto brisket, beef pancetta filet mignon alcatra meatloaf shoulder boudin pig shank. Pork strip steak turducken pork belly salami shank flank fatback capicola. Jowl beef ribs bresaola, drumstick short ribs andouille hamburger capicola tongue short loin kevin. Leberkas chuck beef turkey chicken. Doner ground round burgdoggen, frankfurter ribeye bresaola meatball. Chicken strip steak frankfurter swine kevin short ribs alcatra shoulder jerky hamburger short loin sausage jowl beef salami.


        Shank bacon short ribs, doner picanha chuck drumstick salami ribeye ham hock sirloin. Ribeye spare ribs rump salami sausage, shoulder tail leberkas ham hock short loin jerky jowl. Landjaeger shank rump strip steak ham hock jerky cow. Alcatra turducken flank, shank pancetta tongue leberkas ground round sausage biltong strip steak drumstick.</p>
    </div>
        <div class="panel-footer">
            <button type="button" style="float: right;" class="btn btn-default btn-close" data-dismiss="modal">Close</button>
        </div>
        </form>
    </div>
</div>
</div>

<!--Logout Modal-->
<div id="LogoutModal" class="modal fade" id="info" tabindex="-1" role="dialog" aria-labelledby="infoLabel" aria-hidden="true"></li>
<div class="modal-dialog">
<div class="panel panel-primary">
    <div class="panel-heading">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 class="panel-title" id="infoLabel"> Gestione Utente </h3>
    </div>
    <div class="modal-body">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <input type="hidden" name="type" value="changepassword">
            <div class="form-group">
                <label for="oldPass">Vecchia password</label>
                <input id="oldPass" class="form-control" type="password" placeholder="Vecchia password" name="oldPass">
            </div>
            <div class="form-group">
                <label for="newPass">Nuova password</label>
                <input id="newPass" class="form-control" type="password" placeholder="Nuova password" name="newPass">
            </div>
            <div class="form-group">
                <label for="newPass2">Conferma nuova password</label>
                <input id="newPass2" class="form-control" type="password" placeholder="Conferma nuova password" name="newPass2">
            </div>
            <button type="submit" class="btn btn-default">Cambia password</button>
        </form>
        <hr>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <input type="hidden" name="type" value="logout">
            <button type="submit" class="btn btn-default">Logout</button>
        </form>
    </div>
    <div class="panel-footer">
        <button type="button" style="float: right;" class="btn btn-default btn-close" data-dismiss="modal">Close</button>
    </div>
</div>
</div>
</div>
  
<!-- Login Modal -->
<div id="LoginModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
<div class="panel panel-primary">
    <div class="panel-heading">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 class="panel-title" id="infoLabel"> Login <i class="glyphicon glyphicon-log-in"></i></h3>
    </div>
    <form action="#" method="post" accept-charset="utf-8">
    <div class="modal-body">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
          <input type="hidden" name="type" value="login">
          <div class="form-group">
            <label for="email">Email address:</label>
            <input name="email" type="email" class="form-control" id="email">
          </div>
          <div class="form-group">
            <label for="pwd">Password:</label>
            <input name ="password" type="password" class="form-control" id="pwd">
          </div>
    </div>
        <div class="panel-footer">
            <button type="submit" class="btn btn-default">Submit</button>
            <button type="button" style="float: right;" class="btn btn-default btn-close" data-dismiss="modal">Close</button>
        </div>
        </form>
    </div>
</div>
</div>
    
<!-- Annotation Modal -->
<div id="AnnotationModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
<div class="panel panel-primary">
    <div class="panel-heading">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 class="panel-title" id="infoLabel"> Annotation <i class="glyphicon glyphicon-log-in"></i></h3>
    </div>
    <div class="modal-body">

        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <input type="hidden" name="type" value="addAnnotation">
        <div class="form-group">
          <label for="Annotation-content">Annotation:</label>
          <textarea class="form-control" rows="3" id="Annotation-content" disabled></textarea>
        </div>
        <div class="form-group">
          <label for="User">User:</label>
          <input name="User" type="text" class="form-control" value="<?php echo $_SESSION["email"] ?>" disabled>
        </div>
        <div class="form-group">
          <label for="Annotation">Annotation:</label>
          <input name="Annotation" type="text" class="form-control" value="" placeholder="Scrivi qui le tua considerazioni">
        </div>
            
          <input name="Path" type="hidden" class="form-control" id="Path">
          <input name="OffsetFromStart" type="hidden" class="form-control" id="OffsetFromStart">
          <input name="LenghtAnnotation" type="hidden" class="form-control" id="LenghtAnnotation">
          <input name="Data" type="hidden" class="form-control" id="Data">
          <input name="Doc" type="hidden" class="form-control" id="Doc2" value="">
          <input name="Event" type="hidden" class="form-control" id="Eventid2" value="">
      </div>

        <div class="panel-footer">
        <button type="submit" class="btn btn-default">Submit</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </form>
      </div>
    </div>

  </div>
</div>  
    
<!-- List Annotation Modal -->
<div id="ViewAnnotationModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
<div class="panel panel-primary">
    <div class="panel-heading">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 class="panel-title" id="infoLabel"> List Annotation <i class="glyphicon glyphicon-log-in"></i></h3>
    </div>
    <div class="modal-body">

          <table id="Anntable" class="table table-striped">
              <thead>
                <tr>
                  <th>
                    User
                  </th>
                  <th>
                    Data
                  </th>
                  <th>
                    Content
                  </th>
                  <th>
                    Delete
                  </th>
                </tr>
              </thead>
                  
          </table>
      </div>

        <div class="panel-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

    
<nav class="navbar navbar-default navbar-fixed-top">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" id="homebutton" style="padding:0;"><img id="logo" src="image/logo.png"/></a>
    </div>
    <div id="navbar">
      <ul class="nav navbar-nav navbar-right">
        <li><a onclick="OpenHelp()">Help</a></li>
        <?php if ($_SESSION["userrole"] != "Reader") : ?>
        <li><a onclick="AddAnnotation(<?php echo $_SESSION["userrole"] != "Reader" ?>)"> Aggiungi annotazione</a></li>
        <?php else: ?>
          <li><form "<?php echo $_SERVER['PHP_SELF']; ?>" method="post"><input type="hidden" name="type" value="logout"><button type="submit">Registrazione</button></form></li>
        <?php endif; ?>
        <li><a  onclick="ViewAnnotation()">Controlla Annotazioni</a></li>
       <li>
           <?php if ($_SESSION["userrole"] != "Reader") : ?>
           <a data-toggle="modal" data-target="#LogoutModal" class="pointer">
           <?php else: ?>
           <a data-toggle="modal" data-target="#LoginModal" class="pointer">
           <?php endif; ?>
           <?php
echo "<span id='Name'>".$_SESSION["name"]."</span> : <span id='Role'>".$_SESSION["userrole"]."</span> : <span id='eventRole'>".$_SESSION["eventrole"]."</span>"; 
           ?>
           </a>
       </li>
       </ul>
    </div>
  </div>
</nav>
<div class="tab-content">
  <!---  MAIN  --->
  <div id="main" class="tab-pane fade in active">
        <div class="container">
            <div class="row">
                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                   <div class="panel panel-default">
                       <div class="panel-heading pointer" onclick="ShowHideArea('#DocArea')">
                           <i class="glyphicon glyphicon-folder-open"></i>
                       </div>
                       <div class="panel-body" id="DocArea">
                           <ul class="list-group list-unstyled" id="DocAreaBody">
                           </ul>
                       </div>
                    </div>
                    
                    <div class="panel panel-default">
                       <div class="panel-heading pointer" onclick="ShowHideArea('#EventArea')">
                            <i class="glyphicon glyphicon-calendar"></i>
                       </div>
                       <div class="panel-body" id="EventArea">
                           <ul class="list-group list-unstyled" id="EventAreaBody">
                           </ul>
                       </div>
                    </div>
                    
                    <div class="panel panel-default">
                       <div class="panel-heading pointer" onclick="ShowHideArea('#MetaArea')">
                            <i class="glyphicon glyphicon-asterisk"></i>    
                       </div>
                       <div class="panel-body" id="MetaArea">
                           <div id="div-metaarea-events">
                                <h4 onclick="ShowHideArea('#ul-metaarea-events')">Evento</h4>
                                <ul id="ul-metaarea-events" class="list-group list-unstyled">
                                </ul>
                           </div>
                           <div id="div-metaarea-documents">
                                <h4 onclick="ShowHideArea('#ul-metaarea-documents')">Documento</h4>
                                <ul id="ul-metaarea-documents" class="list-group list-unstyled">
                                </ul>
                           </div>
                       </div>
                    </div>
                   

                </div>
                <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                    <ul class="nav nav-tabs">
                      <li><a data-toggle="tab" href="#doc"  id="docClick" class="truncate">Documento caricato</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="well">
                            <div id="doc" class="tab-pane fade">
                            <h3>Documento</h3>
                            <p>----</p>
                            </div>
                        </div>
                        <div class="well" style="top:600px;">
                            <div id="metaarea-ann" class="list-group list-unstyled">
                            </div>
                        </div>
                    </div> 
                </div>
            </div>
        </div>
    </div>
  <!----
    <div id="registrazione" class="tab-pane fade">
        <div class="container">
            <form "<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <input type="hidden" name="type" value="registrazione">

                <div class="form-group row">
                  <label for="inputName" class="col-sm-2 col-sm-offset-2 col-form-label">Nome</label>
                  <div class="col-sm-6">
                    <input type="text" class="form-control" id="inputName" placeholder="Nome" name="given_name">
                  </div>
                </div>

                <div class="form-group row">
                  <label for="inputCogname" class="col-sm-2 col-sm-offset-2 col-form-label">Cognome</label>
                  <div class="col-sm-6">
                    <input type="text" class="form-control" id="inputCogname" placeholder="Cognome" name="family_name">
                  </div>
                </div>

                <div class="form-group row">
                  <label for="inputEmail" class="col-sm-2 col-sm-offset-2 col-form-label">Email</label>
                  <div class="col-sm-6">
                    <input type="email" class="form-control" id="inputEmail" placeholder="Email" name="email">
                  </div>
                </div>

                <div class="form-group row">
                  <label for="inputPassword1" class="col-sm-2 col-sm-offset-2 col-form-label">Password</label>
                  <div class="col-sm-6">
                    <input type="password" class="form-control" id="inputPassword1" placeholder="Password" name="pass1">
                  </div>
                </div>

                <div class="form-group row">
                  <label for="inputPassword2" class="col-sm-2 col-sm-offset-2 col-form-label">Conferma Password</label>
                  <div class="col-sm-6">
                    <input type="password" class="form-control" id="inputPassword2" placeholder="Password" name="pass2">
                  </div>
                </div>


                <fieldset class="form-group row">
                  <label class="col-form-legend col-sm-2 col-sm-offset-2">Radios</label>
                  <div class="col-sm-6">
                    <div class="form-check">
                      <label class="form-check-label">
                        <input class="form-check-input" type="radio" name="sex" id="gridRadios1" value="male" checked>
                        Male
                      </label>
                    </div>
                    <div class="form-check">
                      <label class="form-check-label">
                        <input class="form-check-input" type="radio" name="sex" id="gridRadios2" value="female">
                        Female                  
                        </label>
                    </div>
                  </div>
                </fieldset>

                <div style="text-align:center;">
                    <button type="reset" class="btn btn-default">Reset</button>
                    <button type="submit" class="btn btn-default">Submit</button>
                </div>
            </form>
        </div>
    </div>
---->
</div>
</body>
</html>
    
<?php endif; ?>