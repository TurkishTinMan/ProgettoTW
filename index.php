<!DOCTYPE html>
<html>
<head>
    
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title>EasyRASH</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">


    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto+Slab">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto">
    
    <link href='https://fonts.googleapis.com/css?family=Philosopher' rel='stylesheet' type='text/css'> 
    <link href='https://fonts.googleapis.com/css?family=Muli' rel='stylesheet' type='text/css'>
    
    <script  src="https://code.jquery.com/jquery-3.1.1.min.js"   integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script>
    
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

    <script src="js/script.js" type="text/javascript"></script>

    
</head>

<body>
    <?php
    session_start();
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
?>
                            <script>
                                Notify('error',"Wrong password!");
                            </script>
<?php                        
                        }
                    }
                }
            
                if($_SESSION["name"] == "Utente"){
                    if($user){
?>
                        <script>
                            Notify('error',"User don't exist!");
                        </script>
<?php
                    }
                }else{
?>
                    <script>
                        Notify('success',"Login Success!");
                    </script>
<?php    
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
                    $string = file_get_contents("./Dataset/project-files/users.json");
                    $json_a = json_decode($string,true);
                    $cont = true;
                    
                    foreach ($json_a as $key => $person_name) {
                        if($person_name['email'] == $_POST['email']){
                            $cont = false;
?>
                            <script>
                                Notify('error',"Email già in archivio!");
                            </script>  
<?php       
                        }
                    }
                    if($cont){
                        $new_person['given_name'] = $_POST['given_name'];
                        $new_person['family_name'] = $_POST['family_name'];
                        $new_person['email'] = $_POST['email'];
                        $new_person['pass'] = $_POST['pass1'];
                        $new_person['sex'] = $_POST['sex'];

                        $new_key = $_POST['given_name']." ".$_POST['family_name']." <".$_POST['email'].">";
                        $json_a[$new_key] = $new_person;
                        $json_a = json_encode($json_a);
                        if(!file_exists("./Dataset/project-files/users.json")){
                            touch("./Dataset/project-files/users.json");
                        }
                        file_put_contents("./Dataset/project-files/users.json",$json_a);

    ?>
                        <script>
                            Notify('success',"Registration Success!");
                        </script>  
    <?php          
                    }
                }else{
?>
                    <script>               
                        ChangePage('#registrazionebutton');
                    </script>  
<?php                  
                }
            break;
        }
    }
?>
<nav class="navbar navbar-default navbar-fixed-top">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" >Logo</a>
    </div>
    <div id="navbar" class="navbar-collapse collapse nav-tabs">
      <ul class="nav navbar-nav">
        <li class="active"><a data-toggle="tab" href="#main" id="homebutton">Home</a></li>
        <li><a data-toggle="tab" href="#about" id="aboutbutton">About</a></li>
        <li><a data-toggle="tab" href="#registrazione" id="registrazionebutton">Registrazione</a></li>
      </ul>
       <ul class="nav navbar-nav navbar-right">
           <li>
               <a data-toggle="modal" data-target="#LoginModal" class="pointer">
               <?php
echo "<span id='Name'>".$_SESSION["name"]."</span> : <span id='Role'>".$_SESSION["userrole"]."</span> : <span id='eventRole'>".$_SESSION["eventrole"]."</span>"; 
               ?>
               </a>
           </li>
       </ul>
    </div>
  </div>
</nav>
<div id="notification"></div>
<div class="tab-content">
  <!---  MAIN  --->
  <div id="main" class="tab-pane fade in active">
        <div class="container">
            <div class="row">
                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                   <div class="panel panel-default">
                       <div class="panel-heading pointer" onclick="ShowHideArea('#DocArea')">
                           DocArea
                       </div>
                       <div class="panel-body" id="DocArea">
                           <ul class="list-unstyled" id="DocAreaBody">
                           </ul>
                       </div>
                    </div>
                    
                    <div class="panel panel-default">
                       <div class="panel-heading pointer" onclick="ShowHideArea('#EventArea')">
                           EventArea
                       </div>
                       <div class="panel-body" id="EventArea">
                           <ul class="list-unstyled" id="EventAreaBody">
                           </ul>
                       </div>
                    </div>
                    
                    <div class="panel panel-default">
                       <div class="panel-heading pointer" onclick="ShowHideArea('#MetaArea')">
                           MetaArea
                       </div>
                       <div class="panel-body" id="MetaArea">
                            Inizialmente vuota, verr&aacute; riempita al caricamento del paper in analisi
                       </div>
                    </div>
                </div>
                <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                    <ul class="nav nav-tabs">
                      <li class="active"><a data-toggle="tab" href="#guide"  id="guidClick">Guida</a></li>
                      <li><a data-toggle="tab" href="#doc"  id="docClick">Documento caricato</a></li>
                    </ul>

                    <div class="tab-content">
                      <div id="guide" class="tab-pane fade in active">
                        <h3>Guida</h3>
                        <p>Bacon ipsum dolor amet bacon prosciutto brisket, beef pancetta filet mignon alcatra meatloaf shoulder boudin pig shank. Pork strip steak turducken pork belly salami shank flank fatback capicola. Jowl beef ribs bresaola, drumstick short ribs andouille hamburger capicola tongue short loin kevin. Leberkas chuck beef turkey chicken. Doner ground round burgdoggen, frankfurter ribeye bresaola meatball. Chicken strip steak frankfurter swine kevin short ribs alcatra shoulder jerky hamburger short loin sausage jowl beef salami.


        Shank bacon short ribs, doner picanha chuck drumstick salami ribeye ham hock sirloin. Ribeye spare ribs rump salami sausage, shoulder tail leberkas ham hock short loin jerky jowl. Landjaeger shank rump strip steak ham hock jerky cow. Alcatra turducken flank, shank pancetta tongue leberkas ground round sausage biltong strip steak drumstick.</p>
                      </div>
                      <div id="doc" class="tab-pane fade">
                        <h3>Documento</h3>
                        <p>----</p>
                      </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!---  ABOUT  --->
    <div id="about" class="tab-pane fade">
        Qui si possono aggiungere cose
    </div>
    <!---  Registrazione  --->
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
</div>
</body>
    
    
    
<!-- Login Modal -->
<div id="LoginModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Login</h4>
      </div>
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
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" onclick="ChangePage('#registrazionebutton')">Registrazione</button>
        <button type="submit" class="btn btn-default">Submit</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </form>
      </div>
    </div>

  </div>
</div>
</html>