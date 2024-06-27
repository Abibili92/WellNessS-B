<?php
session_start();

$conexion = mysqli_connect("localhost", "root", "", "login");

if (!$conexion) {
    die("Conexión fallida: " . mysqli_connect_error());
}

$Usuario = $_POST['Usuario'];
$Contraseña = $_POST['Contraseña'];

$query = "SELECT * FROM `login` WHERE `Usuario` = '$Usuario'";
$result = mysqli_query($conexion, $query);

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);

    if (password_verify($Contraseña, $row['Contra'])) {
        if ($Usuario == $row['Usuario']) {
            header("Location: principal.html");
            exit();
        } else {
            echo "<script>alert('Datos incorrectos. Verifique su usuario.');</script>";
        }
    }  else {
        echo "<script>alert('Datos incorrectos. Verifique su contraseña.');</script>";
    }

} else {
    echo "<script>alert('Usuario no encontrado.');</script>";
}
mysqli_close($conexion);
exit();
?>