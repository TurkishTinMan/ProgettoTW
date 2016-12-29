<?php
session_start();
$name = $_POST['name'];
$email = $_POST['email'];
$password1 = $_POST['password1'];
$password2 = $_POST['password2'];
// verifica dell'email
$verifica1 = strpos( $email, "@" );
// verifica presenza account uguale
$verifica2 = is_dir( "../Dataset/project-files/users.json/$name" );
// cripting password
$pwCripted = sha1($password1);
// PER EVITARE CHE UN CAMPO RIMANGA VUOTO
if( $name == '' or $password1 == '' or $password2 == '' or $email == '' ){
   echo 'Devi compilare tutti i campi';
}
else{
   // COMPARAZIONE DELLE PASSWORD
   if( $password1 == $password2 ){
      if( $verifica1 === false ){
         echo "Devi immettere un indirizzo email reale";
      }
      else{
         if( $verifica2 == '' ){
            // inizio procedimento
            $string = file_get_contents("./Dataset/project-files/users.json/$name");
            $urlPw = "Dataset/project-files/users.json/$name/password.php";
            // SCRITTURA PASSWORD
            $writePword = file_put_contents( $urlPw, $pwCripted );
            $urlEm = "../Dataset/project-files/users.json/$name/email.php";
            // SCRITTURA EMAIL
            $writeEm = file_put_contents( $urlEm, $email );
            header("Location: login.php");
         }
         else{
            echo "Questo nome é giá utilizzato.";
         }
      }
   }
   else{
      echo "Le password devono coincidere";
   }
}
?>
