<?php
    session_start();
    $_SESSION["userrole"] = "Reader";
    $_SESSION["name"] = "Utente";
    $_SESSION["eventrole"] = "None";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $string = file_get_contents("./Dataset/project-files/users.json");
        $json_a = json_decode($string,true);
        foreach ($json_a as $key => $person_name) {
            if($person_name['email'] == $_POST['email']){
                if($person_name['pass'] == $_POST['password']){
                    $_SESSION["userrole"] = "Annotator";
                    $_SESSION["name"] = $key;
                }
            }
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title>EasyRASH</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous"> !-->

    <link rel="stylesheet" href="css/style.css">
    
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto+Slab">
    
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto">
    
    
    <link href='https://fonts.googleapis.com/css?family=Philosopher' rel='stylesheet' type='text/css'>
    
    <link href='https://fonts.googleapis.com/css?family=Muli' rel='stylesheet' type='text/css'>
    
    
</head>

<body>
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
        <li class="active"><a data-toggle="tab" href="#main">Home</a></li>
        <li><a data-toggle="tab" href="#about">About</a></li>
        <li><a data-toggle="tab" href="#contact">Contact</a></li>
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
    <!---  COntact  --->
    <div id="contact" class="tab-pane fade">
        Qui pure
    </div>
</div>
    
    <script  src="https://code.jquery.com/jquery-3.1.1.min.js"   integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script>
    
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

    <script src="js/script.js" type="text/javascript"></script>
    
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
        <form action="index.php" method="post">
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
        <button type="submit" class="btn btn-default">Submit</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </form>
      </div>
    </div>

  </div>
</div>
</html>