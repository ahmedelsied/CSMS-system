<?php
namespace lib\vendor;
trait mailer{
    public function mailer($ourEmail,$email,$content){
        $headers  = 'From: '.$ourEmail."\r\n" ;
        $headers .="Reply-To:  <". $email . ">\r\n" ;
        $headers .='X-Mailer: PHP/' . phpversion();
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=iso-8859-1";
        $subject  = 'New Topic';
        $content  = $content;
        mail($email,$subject,$content,$headers);
    }
}