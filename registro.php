<?php
    $db_hostname = "localhost";
    $db_database = "tienda_videojuegos";
    $db_username = "root";
    $db_password = "";
    $conexion = mysqli_connect($db_hostname, $db_username, $db_password, $db_database);
    if ($conexion->connect_error)
        die("Conexión fallida: " . $conexion->connect_error);
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $usuario = $_POST["usuarios"];
        $contrasena = $_POST["contrasenas"];
        $tlfn = $_POST["tlfn"];
        $direccion = $_POST["direccion"];
        $nombre = $_POST["nombre"];
        $contrasena_cifrada = password_hash($contrasena, PASSWORD_DEFAULT);
        $sql = "INSERT INTO usuarios VALUES ('$usuario', '$nombre', '$direccion', '$tlfn','$contrasena_cifrada')";
    
        if ($conexion->query($sql) === TRUE) {
            echo "Usuario registrado correctamente";
            header("Location: formInicioSesion.php");
        } else
            echo "Error al registrar el usuario: " . $conexion->error;
    }
    $conexion->close();
?>
