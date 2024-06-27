<?php
ini_set("SMTP", "smtp.gmail.com");
ini_set("smtp_port", "587");

$paracorreo        ="abigailvila52@gmail.com";
$titulo            ="Test Correo";
$mensaje           ="Hola mundo";
$tucorreo          ="From:wellnessbienestarsalud@gmail.com";

if(mail($paracorreo,$titulo,$mensaje,$tucorreo))
{
    echo "Correo enviado";
}else{
    echo "Error";
}

?>