<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Email = $_POST['Email'];

    $conexion = mysqli_connect("localhost", "root", "", "login");

    if (!$conexion) {
        die("Conexión fallida: " . mysqli_connect_error());
    }
        
    $sql = "SELECT * FROM `login` WHERE `Email` = ? ";
        
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("s", $Email);
    $stmt->execute();
    $result = $stmt->get_result();
        
    if ($result->num_rows > 0) {
        $token = bin2hex(random_bytes(50));
        $Expire = date("U") + 1800;
       
        $sql = "INSERT INTO `reset_password_tokens`(`Email`, `Token`, `Fecha`) VALUES (?, ?, ?)";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("sss", $Email, $token, $Expire);
        $stmt->execute();

        $mail = new PHPMailer(true);
        $mail->SMTPDebug = 2;
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.office365.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'WellNessBS1234@hotmail.com';
            $mail->Password = 'NessWell123';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('WellNessBS1234@hotmail.com', 'Soporte');
            $mail->addAddress($Email);

            $mail->isHTML(true);
            $mail->Subject = 'Restablecimiento de Contraseña';
            $mail->Body    = "<p>Haga clic en el siguiente enlace para restablecer su contraseña:</p>";
            $mail->Body   .= "<a href='http://localhost/PROGRAMACION/LOGIN/reset_password.php?token=$token'>Restablecer contraseña</a>";

            $mail->send();
            echo "Enlace de recuperación enviado a tu correo electrónico.";
        } catch (Exception $e) {
            echo "Error al enviar el correo: {$mail->ErrorInfo}";
        }

        $stmt->close();
        $conexion->close();
    } else {
        echo "No hay correo";
    }
} else {
    echo "Hubo algún otro problema";
}
?>
