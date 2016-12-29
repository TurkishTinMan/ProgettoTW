<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>EasyRASH</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <link rel="shortcut icon" type="image/png" href="image/favicon.png"/>

    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto+Slab">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto">
    <link href='https://fonts.googleapis.com/css?family=Philosopher' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Muli' rel='stylesheet' type='text/css'>
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet">
    <script  src="https://code.jquery.com/jquery-3.1.1.min.js"   integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script>

    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

    <script src="js/script.js" type="text/javascript"></script>
    <script src="js/scriptlog.js" type="text/javascript"></script>
</head>
<body>
<?php
if(isset($_GET['error_user']))
{
echo '<span style="color:white; font-size:18px;text-align:center;">Errore ,utente non registrato!</span>';
}
if(isset($_GET['error_pass']))
{
echo '<span style="color:whit; font-size:18px;text-align:center;">Errore ,la password non è valida!</span>';
}
?>
<div class="container">

  <div class="row">

    <div class="col-md-4"></div>
    <div class="col-md-4">


      <div class="flip">
    <div class="card">
      <div class="face front">



        <div class="panel panel-default">
            <br>
            <div align="center">
            <div class="image">
            <img src="image/logo.png"></>
            </div>
            <br>
            </div>
<form   method="post" action="login3.php">
<input type="hidden" name="type" value="login">
<tr>
<td>Email</td>
</tr>
<tr>
<td><input type="email" name="email" id="email" class="form-control" placeholder="Email" /></td>
</tr>
<tr>
<td>Password</td>

</tr>
<tr>
<td><input  type="password" name="password" id="pass" class="form-control" id="pwd" placeholder="Password"/></td>



</tr>
<tr>
<td><button class="btn btn-primary btn-block" type="submit">LOG IN</button></td>
</tr>
<p class="text-center">
  <a href="#" class="fliper-btn">Create new account?</a>
</p>
</form>

</div>


</div>
<div class="face back">


<div class="panel panel-default">

<form action="register.php" method="post">
<br>
<div align="center">
<div class="image">
<img src="image/logo.png"></>
</div>

<br>


<input type="text" class="form-control" placeholder="Name" name="name"/>
<input type="password" class="form-control" placeholder="Password" name="password1"/>
<input type="password" class="form-control" placeholder="Confirm password" name="password2"/>
<input type="text" class="form-control" placeholder="Email" name="email"/>
<select name="gender" class="form-control" id="gender">
        <option value="male">Male</option>
        <option value="female">Female</option>
</select>
<button class="btn btn-primary btn-block">SIGN UP</button>


<p class="text-center">
  <a href="#" class="fliper-btn">Already have an account?</a>
</p>
</form>

</form>

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

</form>
</body>
</html>
