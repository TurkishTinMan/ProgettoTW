<!DOCTYPE html>
<html>
<head>
    
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>EasyRASH</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <link rel="shortcut icon" type="image/png" href="image/favicon.png"/>

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

<div id="notification"></div>    

  
<?php
include './PHP/main.php';
setting();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    postManager($_POST["type"]);
}else{
    if (isset($_GET["verify"])){
        verify($_GET["verify"]);   
    }
}
if(!isset($_SESSION["userrole"])) :?>


<div id="logcard" class="container">
  <div class="row">
    <div class="col-md-4 col-md-offset-4">
      <div class="flip">
        <div class="card"> 
          <div class="face front"> 
            <div class="panel panel-default text-center">
              <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="form-horizontal customform">
                <br>
                
                  <div class="image">
                    <img src="image/logo.png"/>
                  </div>
                
                <br />
                <input type="hidden" name="type" value="login">
                <input name="email" class="form-control" placeholder="Username"/>
                <br />
                <input name="password" type="password" class="form-control" placeholder="Password" required="" id="pass_s">
                <br />
                <input type="submit" class="btn btn-primary" value="LOG IN">
              </form>
                <hr>
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" id="hiddenform">
                <input type="hidden" name="type" value="skiplogin">
                <p class="blue">
                <a onclick="$('#hiddenform').submit()"> Skip to EasyRASH as reader </a>
                </p>
              </form>
                <hr>
                <p>
                  <a href="#" class="fliper-btn">Create New Account?</a>
                </p>
            </div>
          </div> 
          <div class="face back"> 
            <div class="panel panel-default text-center">
                <br> 
                <form class="form-horizontal customform" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                
                  <div class="image">
                    <img src="image/logo.png"/>
                  </div>
                  <br>

                    
                  <input type="hidden" name="type" value="registrazione">
                  <input name="given_name" class="form-control" placeholder="Name"/>
                    <br />
                  <input name="family_name" class="form-control" placeholder="Surname"/>
                    <br />
                  <input class="form-control" placeholder="Email" name="email"/>
                    <br />
                  <input type="password" class="form-control" placeholder="Password" required="" id="pass_s" name="pass1">
                    <br />
                  <input type="password" class="form-control" placeholder="Password" required="" id="pass_s" name="pass2">
                    <br />
                  <select name="sex" class="form-control" id="gender">
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                  </select>
                    <br />
                    <input type="submit" class="btn btn-primary" value="REGISTRAZIONE">
                </form>
                <br>

                <p>
                  <a href="#" class="fliper-btn">Already have an account?</a>
                </p>
            
          </div>
        </div>   
      </div>


        </div>
        <div class="col-md-4"></div>

      </div>
    </div>
</div><!-- /.container -->
<script src="js/scriptlog.js" type="text/javascript"></script> 
<?php else: ?>
<div id="ViewChairJudgment"class="modal fade" id="info" tabindex="-1" role="dialog" aria-labelledby="infoLabel" aria-hidden="true">
<div class="modal-dialog">
<div class="panel panel-primary">
    <div class="panel-heading">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 class="panel-title" id="infoLabel"> Assegna un giudizio al documento</h3>
    </div>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" accept-charset="utf-8">
    <div class="modal-body">
        <h3>Sei un Chair</h3>
        <p>Esprimi il tuo giudizio sul documento.</p>
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
        <h3 class="panel-title" id="infoLabel"> Assegna un giudizio al documento</h3>
    </div>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" accept-charset="utf-8">
    <div class="modal-body">
        <h3>Sei un Reviewer</h3>
        <p>Esprimi il tuo giudizio sul documento</p>
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
            <button type="button" style="float: right;" class="btn btn-default btn-close" data-dismiss="modal">Close</button> <!--HO INSERITO STYLE -->
        </div>
        </form>
    </div>
</div>
</div>
    
    
<!--Info Modal-->
<div id="ViewHelp" class="modal fade" id="info" tabindex="-1" role="dialog" aria-labelledby="infoLabel" aria-hidden="true">
<div class="modal-dialog">
<div class="panel panel-primary">
    <div class="panel-heading">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h1 class="panel-title" id="infoLabel"><i class="glyphicon glyphicon-info-sign"></i><span>Help</span></h1>
    </div>
    <form action="#" method="post" accept-charset="utf-8">
    <div class="modal-body">
                              <h3>How does EasyRASH work</h3>
                        <p>Bacon ipsum dolor amet bacon prosciutto brisket, beef pancetta filet mignon alcatra meatloaf shoulder boudin pig shank. Pork strip steak turducken pork belly salami shank flank fatback capicola. Jowl beef ribs bresaola, drumstick short ribs andouille hamburger capicola tongue short loin kevin. Leberkas chuck beef turkey chicken. Doner ground round burgdoggen, frankfurter ribeye bresaola meatball. Chicken strip steak frankfurter swine kevin short ribs alcatra shoulder jerky hamburger short loin sausage jowl beef salami.


        Shank bacon short ribs, doner picanha chuck drumstick salami ribeye ham hock sirloin. Ribeye spare ribs rump salami sausage, shoulder tail leberkas ham hock short loin jerky jowl. Landjaeger shank rump strip steak ham hock jerky cow. Alcatra turducken flank, shank pancetta tongue leberkas ground round sausage biltong strip steak drumstick.</p>
    </div>
        <div class="panel-footer">
            <button type="button" style="float: right;" class="btn btn-default btn-close" data-dismiss="modal">Close</button> <!--HO INSERITO STYLE -->
        </div>
        </form>
    </div>
</div>
</div>

<!--Logout Modal-->
<div id="LogoutModal" class="modal fade" id="info" tabindex="-1" role="dialog" aria-labelledby="infoLabel" aria-hidden="true">
<div class="modal-dialog">
<div class="panel panel-primary">
    <div class="panel-heading">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h1 class="panel-title" id="infoLabel"><i class="glyphicon glyphicon-user"></i><span>User</span>
        </h1>
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
        
    </div>
    <div class="panel-footer">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <input type="hidden" name="type" value="logout">
            <button type="submit" style="float: left;" class="btn btn-default">Logout</button>  <!--HO INSERITO STYLE E TOLTO <HR>-->
        </form>
        <button type="button" class="btn btn-default btn-close" data-dismiss="modal">Close</button>
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
        <h3 class="panel-title" id="infoLabel"><i class="glyphicon glyphicon-log-in"></i><span>Login</span></h3>
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
    
        <div class="panel-footer">
            <button type="submit" class="btn btn-default">Submit</button>
            <button type="button" class="btn btn-default btn-close" data-dismiss="modal">Close</button>
        </div>
        </form>
    </div>
</div>
</div>
</div>
    
<!-- Annotation Modal -->
<div id="AnnotationModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
<div class="panel panel-primary">
    <div class="panel-heading">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h1 class="panel-title" id="infoLabel"><i class="glyphicon glyphicon-pencil"></i><span>Annotation</span></h1>
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
        <button type="button" class="btn btn-default btn-close" data-dismiss="modal">Close</button>
        </div>
     </form>
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
        <li><a onclick="AddAnnotation(<?php echo $_SESSION["userrole"] != "Reader" ?>)">Add Annotation</a></li>
        <?php else: ?>
        <li>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" id="registrationform">                 <input type="hidden" name="type" value="logout">
            </form>

          <a onclick="$('#registrationform').submit()">Registrazione</a>
        </li>
        <?php endif; ?>
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
                
                <!---     Menu - left coloumn    --->
                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                    
                    <!---   EVENT AREA   --->
                    <div class="panel panel-primary">
                       <div class="panel-heading pointer" onclick="ShowHideArea('#EventArea')">
                           
                           <h1 class="panel-title"><i class="glyphicon glyphicon-calendar"></i><span>Events</span></h1>
                           
                       </div>
                       <div class="panel-body" id="EventArea">
                           <ul class="list-group list-unstyled" id="EventAreaBody">
                           </ul>
                       </div>
                    </div>
                    
                    <!---   DOCUMENT AREA   --->
                    <div class="panel panel-primary">
                       <div class="panel-heading pointer" onclick="ShowHideArea('#DocArea')">
                           
                           <h1 class="panel-title"><i class="glyphicon glyphicon-folder-open"></i><span> Documents</span></h1>
                           
                       </div>
                       <div class="panel-body" id="DocArea">
                           <ul class="list-group list-unstyled" id="DocAreaBody">
                           </ul>
                       </div>
                    </div>

                    
                    <!---   META AREA   --->
                    <div class="panel panel-primary">
                       <div class="panel-heading pointer" onclick="ShowHideArea('#MetaArea')">
                           
                            <h1 class="panel-title"><i class="glyphicon glyphicon-info-sign"></i><span>About Document</span></h1>
                       
                       </div>
                       <div class="panel-body" id="MetaArea">
                           <div id="div-metaarea-events">
                                <h4 onclick="ShowHideArea('#ul-metaarea-events')">Event:</h4>
                                <ul id="ul-metaarea-events" class="list-group list-unstyled">
                                </ul>
                           </div>
                           <div id="div-metaarea-documents">
                                <h4 onclick="ShowHideArea('#ul-metaarea-documents')">Document:</h4>
                                <ul id="ul-metaarea-documents" class="list-group list-unstyled">
                                </ul>
                           </div>
                       </div>
                    </div>
                </div>
                
                <!---     Document View - right coloumn     --->
                <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">                    
                    <div class="well">
                        <div id="doc">
                        </div>
                    </div>
                    <div class="text-center">
                      <ul id="keyWordsList"></ul>  
                      <table id="Anntable" class="table table-striped">
                      </table>
                      <h3 id="chairjudgmentresume"></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
<?php endif; ?>