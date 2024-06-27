<?php
session_start();

$conexion = mysqli_connect("localhost", "root", "", "login");

if (!$conexion) {
    die("Conexión fallida: " . mysqli_connect_error());
}

$Email = $_POST['Email'];
$Usuario = $_POST['Usuario'];
$Contraseña = $_POST['Contraseña'];


$query = "SELECT * FROM `login` WHERE `Email` = '$Email' OR `Usuario` = '$Usuario'";
$result = mysqli_query($conexion, $query);

if (mysqli_num_rows($result) > 0) {
    // Hay coincidencias, verificar si es un inicio de sesión o intento de registro duplicado
    $row = mysqli_fetch_assoc($result);

        if ($Email == $row['Email']) {
            echo "<script>alert('El email ya está registrado.');</script>";
        } elseif ($Usuario == $row['Usuario']) {
            echo "<script>alert('El usuario ya está registrado.');</script>";
        } else {
            echo "<script>alert('Datos incorrectos. Verifique su usuario y contraseña.');</script>";
        }
    
} else {
    // No hay coincidencias, proceder con el registro
    $hashed_password = password_hash($Contraseña, PASSWORD_BCRYPT);
    $m = "INSERT INTO `login`(`Email`, `Contra`, `Usuario`) VALUES ('$Email', '$hashed_password', '$Usuario')";

    if (mysqli_query($conexion, $m)) {
        header("Location: principal.html");
    } else {
        echo "Error: " . $m . "<br>" . mysqli_error($conexion);
    }
}
mysqli_close($conexion);
exit();
?>