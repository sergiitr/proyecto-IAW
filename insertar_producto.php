<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Obtener los datos del formulario
        $nombre = $_POST['nombre'];
        $stock = $_POST['stock'];
        $precio = $_POST['precio'];
        $idcompania = $_POST['idcompania'];
        $plataforma = $_POST['plataforma'];

        // Procesar la imagen
        $imagen_nombre = $_FILES['imagen']['name'];
        $imagen_temp = $_FILES['imagen']['tmp_name'];
        $imagen_ruta = "./imagenes/" . $imagen_nombre;
        move_uploaded_file($imagen_temp, $imagen_ruta);

        // Conexión a la base de datos
        require_once "login.php";
        $conexion = mysqli_connect($host, $user, $pass, $database);

        // Leer el contenido de la imagen
        $imagen_contenido = addslashes(file_get_contents($imagen_ruta));

        // Insertar los datos en la base de datos
        $consulta = "INSERT INTO juegos (nombre, stock, imagen, precio, idcompania, plataforma) VALUES ('$nombre', $stock, '$imagen_contenido', '$precio', $idcompania, '$plataforma')";

        if ($conexion->query($consulta) === TRUE) {
            echo "Producto insertado correctamente.";
        } else {
            echo "Error al insertar el producto: " . $conexion->error;
        }

        // Cerrar la conexión
        $conexion->close();
    }
?>