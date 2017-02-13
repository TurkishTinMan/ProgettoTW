<?php
require_once('./PHP/class.phpmailer.php');

function SendMail(){
    $GUID = getGUID();
    $new_person['given_name'] = $_POST['given_name'];
    $new_person['family_name'] = $_POST['family_name'];
    $new_person['email'] = $_POST['email'];
    $new_person['pass'] = $_POST['pass1'];
    $new_person['sex'] = $_POST['sex'];
    $new_person['GUID'] = $GUID;
    /*TsU{7pCCePneBtAT*/
    
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

    if($mail->Send()) {
        $new_key = $_POST['given_name']." ".$_POST['family_name']." <".$_POST['email'].">";
        $json_a[$new_key] = $new_person;
        write($GLOBALS['TempUserFileUrl'],$json_a);
        return true;
    }
    return false;
}

?>