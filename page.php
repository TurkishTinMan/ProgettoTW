<!DOCTYPE html>
<html>
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>EasyRASH</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <link rel="shortcut icon" type="image/png" href="image/favicon.png"/>

    <link rel="stylesheet" href="css/stylehome.css">
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
?>


<nav class="navbar navbar-default navbar-inverse" role="navigation">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand"><img class="img-responsive" src="image/logo.png"></a>
    </div>



    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li class="active"><a data-toggle="tab" href="#main" id="homebutton">Home</a></li>
        <li>
        <a href="#" data-toggle="modal" data-target="#info" data-original-title>Help</a>
        </li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle " data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Contact<span class="caret"></span><i class="fa fa-caret-down"></i></a>
          <ul class="dropdown-menu">
            <li class=" dropdown">
               <a href="https://www.facebook.com/riccardo.t.salladini?fref=ts">Riccardo Salladini </a>
            </li>
            <li>
              <a href="https://www.facebook.com/martina.bergonzoni.3">Martina Bergonzoni</a>
            </li>
            <li>
              <a href="https://www.facebook.com/leonardo.disagio?fref=ts">Manuel "inseriscicogn"</a>
            </li>
            <li>
              <a href="https://www.facebook.com/LellaRaffa?fref=ts">Raffaella Veneri</a>
            </li>
          </ul>
        </li>

      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li>
            <a data-toggle="#" data-target="#" class="pointer">
            <?php
            echo "<span id='Name'>".$_SESSION["name"]."</span> : <span id='Role'>".$_SESSION["userrole"]."</span> : <span id='eventRole'>".$_SESSION["eventrole"]."</span>";
                        ?>
        </li>
      </ul>
    </div>
    <!-- /.navbar-collapse -->
  </div>
  <!-- /.container-fluid -->
</nav>
<div id="notification"></div>
<div class="tab-content">
  <!---  MAIN  -->
  <div id="main" class="tab-pane fade in active">
        <div class="container">
            <div class="row">

                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                   <div class="panel panel-default">
                       <div class="panel-heading pointer" onclick="ShowHideArea('#DocArea')">
                           <div class="glyphicon glyphicon-folder-open"></div>
                       </div>
                       <div class="panel-body" id="DocArea">
                           <ul class="list-unstyled" id="DocAreaBody">
                           </ul>
                       </div>
                    </div>

                    <div class="panel panel-default">
                       <div class="panel-heading pointer" onclick="ShowHideArea('#EventArea')">
                          <div class="glyphicon glyphicon-calendar"></div>
                       </div>
                       <div class="panel-body" id="EventArea">
                           <ul class="list-unstyled" id="EventAreaBody">
                           </ul>
                       </div>
                    </div>

                    <div class="panel panel-default">
                       <div class="panel-heading pointer" onclick="ShowHideArea('#MetaArea')">
                      <div class="glyphicon glyphicon-asterisk"></div>
                       </div>
                       <div class="panel-body" id="MetaArea">
                           <div id="div-metaarea-events">
                                <h4>Evento</h4>
                                <ul id="ul-metaarea-events">
                                -
                                </ul>
                           </div>
                           <div id="div-metaarea-documents">
                                <h4>Documento</h4>
                                <ul id="ul-metaarea-documents">
                                -
                                </ul>
                           </div>

                       </div>
                    </div>
                     <button type="button" class="btn btn-default" onclick="AddAnnotation()">
                       Aggiungi Annotazione
                   </button>
                </div>
                <div class="content-container" id="top">
                <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                    <ul class="nav nav-tabs">
                      <li>
                      <a data-toggle="tab" href="#doc"  id="docClick">Documento caricato</a>
                      </li>
                       </li>
                    </ul>
                 <div class="well">
                      <div id="doc" class="tab-pane fade">
                        <h3>Documento</h3>
                        <p>----</p>
                      </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </div>
</div>

    <!---  ABOUT  -->





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


<!--Info Modal-->
<div class="modal fade" id="info" tabindex="-1" role="dialog" aria-labelledby="infoLabel" aria-hidden="true"></li>
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


<!-- Annotation Modal -->
<div id="AnnotationModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Annotation</h4>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label for="Annotation-content">Annotation:</label>
          <textarea class="form-control" rows="5" id="Annotation-content"></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </form>
      </div>
    </div>


   </div>
</div>

</body>
</html>
