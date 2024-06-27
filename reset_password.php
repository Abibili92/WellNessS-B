<?php
require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$conexion = mysqli_connect("localhost", "root", "", "login");

if (!$conexion) {
    die("Conexión fallida: " . mysqli_connect_error());
}

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    $currentTime = date("U");

    $sql = "SELECT * FROM `reset_password_tokens` WHERE `Token` = ? AND `Fecha` >= ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("si", $token, $currentTime);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $newPassword = $_POST['password'];
            $row = $result->fetch_assoc();
            $Email = $row['Email'];

            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

            $sql = "UPDATE `login` SET `Contra` = ? WHERE `Email` = ?";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("ss", $hashedPassword, $Email);
            $stmt->execute();

            // Eliminar el token después de usarlo
            $sql = "DELETE FROM `reset_password_tokens` WHERE `Email` = ?";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("s", $Email);
            $stmt->execute();

            echo "Tu contraseña ha sido restablecida exitosamente.";
        } else {
            echo '<form action="" method="post">
                    <label for="password">Nueva Contraseña:</label>
                    <input type="password" name="password" required>
                    <input type="submit" value="Restablecer Contraseña">
                  </form>';
        }
    } else {
        echo "El enlace de restablecimiento de contraseña no es válido o ha expirado.";
    }

    $stmt->close();
} else {
    echo "No se ha proporcionado un token válido.";
}

$conexion->close();
?>
