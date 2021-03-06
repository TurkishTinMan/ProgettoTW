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

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto+Slab">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto">

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
if(!isset($_SESSION["name"])) :?>

<!--- Home - Login --->
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
</div><!-- /Home - Login -->
<script src="js/scriptlog.js" type="text/javascript"></script>
<?php else: ?>
<div id="ViewChairJudgment"class="modal fade" id="info" tabindex="-1" role="dialog" aria-labelledby="infoLabel" aria-hidden="true">
<div class="modal-dialog">
<div class="panel panel-primary">
    <div class="panel-heading">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 class="panel-title" id="infoLabel"> Assegna un giudizio al documento</h3>
    </div>
    <div class="modal-body">
        <h3>Sei un Chair</h3>
        <p>Esprimi il tuo giudizio sul documento.</p>
        <p id="resumereviewers"></p>
        <form id="chairform">
        <div class="radio">
          <label><input type="radio" name="judgment" value="Rejected" checked>Rejected</label>
        </div>
        <div class="radio">
          <label><input type="radio" name="judgment" value="Accepted">Accepted</label>
        </div>
        </form>
    </div>
        <div class="panel-footer">
            <button onclick="AddJudgmentCH()" class="btn btn-default">Submit</button>
            <button type="button" style="float: right;" class="btn btn-default btn-close" data-dismiss="modal">Close</button>
        </div>
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
    <div class="modal-body">
        <h3>Sei un Reviewer</h3>
        <p>Esprimi il tuo giudizio sul documento</p>
        <form id="reviewer">
        <div class="radio">
          <label><input type="radio" name="judgment" value="Rejected">Rejected</label>
        </div>
        <div class="radio">
          <label><input type="radio" name="judgment" value="Modification Request" checked>Modification Request</label>
        </div>
        <div class="radio">
          <label><input type="radio" name="judgment" value="Accepted">Accepted</label>
        </div>
        </form>
    </div>
        <div class="panel-footer">
            <button onclick="AddJudgmentRw()" type="submit" class="btn btn-default">Submit</button>
            <button type="button" style="float: right;" class="btn btn-default btn-close" data-dismiss="modal">Close</button> <!--HO INSERITO STYLE -->
        </div>
    </div>
</div>
</div>

<!--Help Modal-->
<div id="Help" class="modal fade" id="info" tabindex="-1" role="dialog" aria-labelledby="infoLabel" aria-hidden="true">
<div class="modal-dialog modal-lg">
<div class="panel panel-primary">
    <div class="panel-heading">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h1 class="panel-title" id="infoLabel"><i class="glyphicon glyphicon-info-sign"></i><span>Help</span>
        </h1>
    </div>
    
    <div class="modal-body">
      
        <h3>What is EasyRASH?</h3>
        <p>
            EasyRASH is an online system to allow the evaluation of articles in RASH, integrating content and comments in one package and easing the peer review process. It focuses on writing and view comments from reviewers, includes some optional modules to handle other functionality such as registration of new users.<br />
            EasyRASH has two roles:
        </p>

        <ul>
            <li>Chair: organizer of an event, assign the review and decide the status of an article (rejected or accepted) based on the reviewers' comments;</li>
            <li>Reviewer: receiving articles to comment and expresses an overall judgment on each item and a series of specific comments on the content.</li>
        </ul>

        <p>
            In the top right you can see your name. In Reader mode - passive user - you can read the article; in Annotator mode - active user - you can make annotations and submit review and your role in the document uploaded.
        </p>

        <ul>
            <li>Chair <span class="glyphicon glyphicon-pencil"></span></li>
            <li>Member event <span class="glyphicon glyphicon-eye-open"></span></li>
            <li>Any role <span class="glyphicon glyphicon-ban-circle"></span></li>
        </ul>

        <h3>Annotator mode</h3>
        <p>
            You can annotate documents which you are the reviewer, giving judgment and leaving a comment on the text sections. Steps:
            You select the text to be changed, you click on "Add Annotation", you leave your comments and finally you save by clicking <span id="save" class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span>.
            To check your own annotations you view the table under the article, in which one can find the record or delete it.
        </p>

    </div>
    <div class="panel-footer">
        <button type="button" class="btn btn-default btn-close" data-dismiss="modal">Close</button>
    </div>

</div>
</div>
</div>

<!--About Modal-->
<div id="AboutModal" class="modal fade" id="info" tabindex="-1" role="dialog" aria-labelledby="infoLabel" aria-hidden="true">
<div class="modal-dialog">
<div class="panel panel-primary">
    <div class="panel-heading">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h1 class="panel-title" id="infoLabel"><i class="glyphicon glyphicon-user"></i><span>About</span>
        </h1>
    </div>
    <div class="modal-body">
      <h4 class="text-center">EasyRush is a project by:</h4><br />
      <ul  class="list-unstyled" id="contact-list">
                          <li >
                              <div class="col-xs-12 col-sm-3">
                                  <img src="image/user1.png" alt="Riccardo Salladini" class="img-responsive img-circle" />
                              </div>
                              <div class="col-xs-12 col-sm-9">
                                  <span class="name">Riccardo Salladini</span><br/>
                                  <span class="glyphicon glyphicon-map-marker text-muted c-info" data-toggle="tooltip" title="Mura Anteo Zamboni, 7, 40126 (BO)"></span>
                                  <span class="visible-xs"> <span class="text-muted">Mura Anteo Zamboni, 7, 40126 (BO)</span><br/></span>
                                  <span class="glyphicon glyphicon-envelope text-muted c-info" data-toggle="tooltip" title="riccardo.salladini@studio.unibo.it"></span>
                                  <span class="visible-xs"> <span class="text-muted">riccardo.salladini@studio.unibo.it</span><br/></span>
                              </div>
                              <div class="clearfix"></div>
                          </li><br />
                          <li >
                              <div class="col-xs-12 col-sm-3">
                                  <img src="image/user1.png" alt="Manuel Felix" class="img-responsive img-circle" />
                              </div>
                              <div class="col-xs-12 col-sm-9">
                                  <span class="name">Manuel Felix</span><br/>
                                  <span class="glyphicon glyphicon-map-marker text-muted c-info" data-toggle="tooltip" title="Mura Anteo Zamboni, 7, 40126 (BO)"></span>
                                  <span class="visible-xs"> <span class="text-muted">Mura Anteo Zamboni, 7, 40126 (BO)</span><br/></span>
                                  <span class="glyphicon glyphicon-envelope text-muted c-info" data-toggle="tooltip" title="juanmanuel.felix@studio.unibo.it"></span>
                                  <span class="visible-xs"> <span class="text-muted">juanmanuel.felix@studio.unibo.it</span><br/></span>
                              </div>
                              <div class="clearfix"></div>
                          </li><br />
                          <li >
                              <div class="col-xs-12 col-sm-3">
                                  <img src="image/user2.png" alt="Martina Bergonzoni" class="img-responsive img-circle" />
                              </div>
                              <div class="col-xs-12 col-sm-9">
                                  <span class="name">Martina Bergonzoni</span><br/>
                                  <span class="glyphicon glyphicon-map-marker text-muted c-info" data-toggle="tooltip" title="Mura Anteo Zamboni, 7, 40126 (BO)"></span>
                                  <span class="visible-xs"> <span class="text-muted">Mura Anteo Zamboni, 7, 40126 (BO)</span><br/></span>
                                  <span class="glyphicon glyphicon-envelope text-muted c-info" data-toggle="tooltip" title="martina.bergonzoni2@studio.unibo.it"></span>
                                  <span class="visible-xs"> <span class="text-muted">martina.bergonzoni2@studio.unibo.it</span><br/></span>
                              </div>
                              <div class="clearfix"></div>
                          </li><br />
                          <li >
                              <div class="col-xs-12 col-sm-3">
                                  <img src="image/user2.png" alt="Raffaella Veneri" class="img-responsive img-circle" />
                              </div>
                              <div class="col-xs-12 col-sm-9">
                                  <span class="name">Raffaella Veneri</span><br/>
                                  <span class="glyphicon glyphicon-map-marker text-muted c-info" data-toggle="tooltip" title="Mura Anteo Zamboni, 7, 40126 (BO)"></span>
                                  <span class="visible-xs"> <span class="text-muted">Mura Anteo Zamboni, 7, 40126 (BO)</span><br/></span>
                                  <span class="glyphicon glyphicon-envelope text-muted c-info" data-toggle="tooltip" title="raffaella.veneri@studio.unibo.it"></span>
                                  <span class="visible-xs"> <span class="text-muted">raffaella.veneri@studio.unibo.it</span><br/></span>
                              </div>
                              <div class="clearfix"></div>
                          </li>
                      </ul>
                  </div>


    <div class="panel-footer">
        <button type="button" class="btn btn-default btn-close" data-dismiss="modal">Close</button>
    </div>

</div>
</div>
</div>

<!--Logout Modal-->
<div id="LogoutModal" class="modal fade" id="info" tabindex="-1" role="dialog" aria-labelledby="infoLabel" aria-hidden="true">
<div class="modal-dialog modal-sm">
<div class="panel panel-primary">
    <div class="panel-heading">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h1 class="panel-title" id="infoLabel"><i class="glyphicon glyphicon-user"></i><span>User</span>
        </h1>
    </div>
    <div class="modal-body">
        
        <p>Nome <?php echo $_SESSION["given_name"]; ?></p>          
        <p>Cognome <?php echo $_SESSION["family_name"]; ?></p>
        <p>Sesso <?php echo $_SESSION["sex"]; ?></p>
        <p><span class="glyphicon glyphicon-cog button" onclick="ShowHideArea(ChangePass)"></span>Cambia Password</p>
        <form id="ChangePass" style="display:none;" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
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
  <div class="modal-dialog modal-sm">
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
        <h1 class="panel-title" id="infoLabel"><i class="glyphicon glyphicon-pencil"></i><span>Annotation</span></h1>
    </div>
    <div class="modal-body">

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
          <input name="Annotation" type="text" class="form-control" value="" placeholder="Scrivi qui le tua considerazioni" id="Comment">
        </div>

          <input name="Path" type="hidden" class="form-control" id="Path">
          <input name="OffsetFromStart" type="hidden" class="form-control" id="OffsetFromStart">
          <input name="LenghtAnnotation" type="hidden" class="form-control" id="LenghtAnnotation">
          <input name="Data" type="hidden" class="form-control" id="Data">
          <input name="Author" type="hidden" class="form-control" id="Author" value="<?php echo $_SESSION['email']; ?>">
      </div>

        <div class="panel-footer">
        <button onclick="AddAnnotationLocal()" class="btn btn-default">Submit</button>
        <button type="button" class="btn btn-default btn-close" data-dismiss="modal">Close</button>
        </div>
    </div>

  </div>
</div>
<!--- Navigation Bar --->
<nav class="navbar navbar-default navbar-fixed-top">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar" aria-expanded="false" aria-controls="navbar">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" id="homebutton"><img class="img-responsive" id="logo" src="image/logo.png" alt="EasyRush brand"/></a>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav navbar-right">
        <li> <a data-toggle="modal" data-target="#AboutModal" class="pointer">About</a></li>
        <li> <a data-toggle="modal" data-target="#Help" class="pointer">Help</a></li>
        <li class="hiddenuntilAnnotator"><a onclick="AddAnnotation(<?php echo $_SESSION["userrole"] != "Reader" ?>)">Add Annotation</a></li>
        <li class="hiddenuntilAnnotator"><a onclick="SaveAnnotation()"><span id="save" class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span></a><li>
        <li>
           <?php if ($_SESSION["name"] != "Utente") : ?>
           <a data-toggle="modal" data-target="#LogoutModal" class="pointer">
           <?php else: ?>
           <a data-toggle="modal" data-target="#LoginModal" class="pointer">
           <?php endif; ?>
           <?php
               echo "<span id='Name'>".$_SESSION["name"]."</span>";
           ?>
           </a>
       </li>
       <li><a onclick="ChangeMode()"><span id='Role'>Reader</span></a></li>
       <li><a><span id='eventRole'></span></a></li>
       <li>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" id="logout">                           
            <input type="hidden" name="type" value="logout">
        </form>
        <a onclick="$('#logout').submit()"><span class="glyphicon glyphicon-log-out"></span></a>
        </li>
       </ul>
    </div>
  </div>
</nav>
<!--- END Navigation Bar --->

<!---  Main Content  ---
<div class="tab-content">
  <div id="main" class="tab-pane fade in active">-->
        <div class="container">
            <div class="row">

                <!---     Menu - left coloumn    --->
                <div class="col-sm-4">

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
                            <p id="chairjudgmentresume"></p>
                            <ul id="ul-reviewer"></ul>
                            <div id="ul-authors"></div>
                            <ul id="ACM"></ul>
                            <ul id="keyWordsList"></ul>
                       </div>
                    </div>
                </div>

                <!---     Document View - right coloumn     --->
                <div class="col-sm-8">
                    <div id="doc" class="well">
                    </div>
                    <div id="annTabPane" class="panel panel-primary">
                        <table id="Anntable" class="table table-bordered table-responsive">                  
                        </table>
                    </div>

                </div>
            </div>
        </div><!-- END Main Content-->
</body>
</html>
<?php endif; ?>
