<?php
    session_start();
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $string = file_get_contents("./Dataset/project-files/users.json");
        $json_a = json_decode($string,true);
        $_SESSION["userrole"] = "Reader";
        $_SESSION["name"] = "Utente";
        foreach ($json_a as $person_name) {
            if($person_name['email'] == $_POST['email']){
                if($person_name['pass'] == $_POST['password']){
                    $_SESSION["userrole"] = "Annotator";
                    $_SESSION["name"] = $person_name["given_name"];
                    $_SESSION["famname"] = $person_name["family_name"];
                    $_SESSION["userrole"] = "Annotator";
                }
            }
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
<title>EasyRASH</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">


<link href="css/style.css" rel="stylesheet">

<script  src="https://code.jquery.com/jquery-3.1.1.min.js"   integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8="   crossorigin="anonymous"></script>
    
    
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

<script src="js/script.js" type="text/javascript"></script>
    
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
               <?php if(isset($_SESSION['userrole']) && $_SESSION["userrole"] != null){
                        echo $_SESSION["name"]." : ".$_SESSION["userrole"]; 
                }else{
                        echo "Utente : Reader";
                }
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
                <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 fixed">
                   <div class="panel panel-default">
                       <div class="panel-heading pointer" onclick="ShowHideArea('#DocArea')">
                           DocArea
                       </div>
                       <div class="panel-body" id="DocArea">
                           <ul id="DocAreaBody">
                           </ul>
                       </div>
                    </div>
                    
                    <div class="panel panel-default">
                       <div class="panel-heading pointer" onclick="ShowHideArea('#EventArea')">
                           EventArea
                       </div>
                       <div class="panel-body" id="EventArea">
                           <ul id="EventAreaBody">
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
                <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8 col-xs-offset-4 col-xs-offset-4 col-sm-offset-4 col-md-offset-4 col-lg-offset-4">
                    <ul class="nav nav-tabs">
                      <li class="active"><a data-toggle="tab" href="#guide">Guida</a></li>
                      <li><a data-toggle="tab" href="#doc">Documento caricato</a></li>
                    </ul>

                    <div class="tab-content">
                      <div id="guide" class="tab-pane fade in active">
                        <h3>Guida</h3>
                        <p>Bacon ipsum dolor amet bacon prosciutto brisket, beef pancetta filet mignon alcatra meatloaf shoulder boudin pig shank. Pork strip steak turducken pork belly salami shank flank fatback capicola. Jowl beef ribs bresaola, drumstick short ribs andouille hamburger capicola tongue short loin kevin. Leberkas chuck beef turkey chicken. Doner ground round burgdoggen, frankfurter ribeye bresaola meatball. Chicken strip steak frankfurter swine kevin short ribs alcatra shoulder jerky hamburger short loin sausage jowl beef salami.

        Alcatra t-bone meatball, pancetta hamburger strip steak ground round landjaeger tenderloin. T-bone frankfurter alcatra rump brisket, corned beef leberkas tenderloin. Short ribs meatloaf strip steak ham spare ribs bacon shank chicken kielbasa. Sirloin picanha beef ribs pastrami chuck rump venison beef shankle ball tip. Beef pancetta landjaeger jerky beef ribs tenderloin. Landjaeger ham hock meatloaf ball tip leberkas porchetta pork belly corned beef capicola. Turkey shoulder fatback, beef ribs tongue meatloaf landjaeger pork belly drumstick ribeye ham.

        Cow pork leberkas drumstick sausage, jerky pastrami ham beef pork loin. Ham hock beef ribs spare ribs pancetta cupim flank, beef ribeye tail drumstick. Tenderloin tongue corned beef jerky biltong tri-tip sausage ball tip kevin. Picanha drumstick cow jowl turducken swine fatback sausage cupim. Strip steak tri-tip shank meatloaf. Meatball pancetta frankfurter sausage corned beef pig shoulder, kielbasa pastrami shankle boudin.

        Drumstick pork loin frankfurter pastrami cupim boudin. Prosciutto chuck pork belly ham shankle ball tip t-bone shoulder biltong meatball landjaeger. Strip steak kevin picanha, jowl beef ribs ground round sirloin doner bacon jerky. Venison landjaeger fatback pig tongue, corned beef shoulder alcatra boudin andouille spare ribs burgdoggen tail bacon cow. Ribeye t-bone drumstick biltong bacon ham, ball tip pork belly.

        Doner shankle swine pork chop pancetta meatball shoulder corned beef cow. Beef ribs jerky sausage capicola bacon jowl brisket sirloin. Porchetta ball tip ribeye, biltong filet mignon tongue prosciutto swine capicola. Chicken tail beef, frankfurter short ribs ham hock jerky tri-tip bacon. Pork loin tri-tip ball tip chuck cupim.

        Pork belly leberkas cow cupim pork loin. Short ribs ribeye pork loin chuck, jerky ham hock chicken capicola pork belly t-bone beef ribs venison meatball picanha. Cupim swine fatback pastrami burgdoggen meatloaf jowl pork capicola boudin ribeye landjaeger rump meatball. Brisket shankle meatloaf turducken, meatball pork belly boudin shoulder salami pig sirloin andouille burgdoggen tail t-bone. Ham doner tail frankfurter andouille tri-tip shank flank. Filet mignon ribeye cow andouille meatball, doner hamburger picanha capicola biltong swine jowl cupim pancetta landjaeger.

        Venison meatball beef ribs pork alcatra doner short loin rump andouille turducken prosciutto brisket shoulder. Doner jowl meatball meatloaf turkey, tail hamburger capicola shank. Filet mignon cupim kevin, bresaola short loin leberkas beef ribs burgdoggen prosciutto. Brisket salami beef ribs cupim pancetta. Shoulder ham hock kevin pork shank short ribs, pork loin beef ribs sausage ball tip salami. Kevin pancetta ground round beef brisket. Salami pig prosciutto tongue spare ribs leberkas porchetta picanha jerky.

        Ham hock pork pork chop, tongue meatloaf andouille meatball filet mignon turkey spare ribs. Frankfurter kevin leberkas, sirloin cow doner ground round shank meatball landjaeger sausage t-bone fatback salami. Ball tip kevin chuck meatball pig andouille rump doner shoulder drumstick spare ribs bacon. Biltong cupim hamburger kevin flank, swine ball tip shoulder fatback. Ribeye tri-tip venison shank ham kielbasa alcatra meatloaf ball tip tenderloin swine kevin.

        Filet mignon tongue frankfurter venison, capicola strip steak beef ribs andouille. Pork loin meatball pork swine, frankfurter brisket pancetta drumstick sausage beef ribs short loin ball tip shankle ham. Capicola doner jerky swine chicken sausage. Turducken tri-tip shoulder pork loin. Jerky ham shankle chicken turkey tongue. Sausage jerky boudin short ribs, doner beef beef ribs fatback prosciutto pork belly pig.

        Capicola pancetta kevin chuck flank drumstick salami meatball turducken picanha ribeye pork chop. Pastrami spare ribs ball tip biltong, pig doner ham shankle boudin. Ribeye kevin porchetta, burgdoggen pork alcatra pork loin biltong hamburger. Beef jerky pastrami salami leberkas, pork prosciutto short loin tri-tip kevin. Kevin leberkas sausage t-bone, landjaeger prosciutto brisket chuck flank shoulder salami beef hamburger. Cow picanha prosciutto filet mignon, pig meatloaf short ribs sirloin ham hock shankle pancetta tenderloin kielbasa doner.

        Tongue drumstick pork belly pancetta sausage kevin tenderloin alcatra bresaola ribeye ball tip. Venison andouille sirloin, pancetta landjaeger chuck pork chop flank ground round ham hock. Shankle sirloin cupim pastrami, leberkas pork boudin landjaeger kevin salami tri-tip cow meatloaf short loin. Turducken swine meatloaf flank sausage leberkas bacon. Shank biltong landjaeger ribeye picanha venison bacon doner. Kevin biltong tongue burgdoggen pork loin, brisket venison.

        Cupim biltong hamburger flank. Kielbasa boudin brisket beef ribs chuck pork loin shank. Venison turkey andouille leberkas spare ribs sirloin shank, ball tip brisket beef ribs ham hock pork picanha pork loin kielbasa. Bacon cupim swine frankfurter porchetta, prosciutto strip steak chicken pork jowl jerky tongue leberkas shankle burgdoggen. Short loin biltong tenderloin jerky rump kevin ribeye corned beef boudin meatball alcatra tri-tip venison.

        Beef pork loin boudin, pig alcatra filet mignon burgdoggen bacon ham hock landjaeger pork meatloaf porchetta. Meatball bresaola fatback, biltong short ribs pork loin turkey alcatra. Ham turducken swine short ribs ground round andouille tail meatloaf pastrami biltong kevin landjaeger salami pork short loin. Alcatra meatloaf kielbasa rump cow chicken pancetta. Pork belly short loin landjaeger beef jowl, alcatra doner sausage picanha shoulder kielbasa shankle bresaola kevin tongue. Jowl pig chicken boudin shankle pancetta chuck short ribs porchetta burgdoggen landjaeger ham pastrami bacon. Ham prosciutto pork fatback pork belly tenderloin flank shankle kevin ribeye chicken frankfurter.

        Leberkas ground round rump filet mignon t-bone meatloaf shoulder kevin corned beef pastrami tenderloin. Rump tongue beef ribs ham, chicken brisket tail pork loin shoulder turkey pork tenderloin kevin prosciutto doner. Tail shankle sausage venison, burgdoggen pork loin fatback beef ribs drumstick. Filet mignon leberkas pancetta burgdoggen ground round, salami short ribs pork belly pork chop strip steak boudin.

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