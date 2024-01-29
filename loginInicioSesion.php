<?php 
    session_start();
    require_once "./login.php";
    $conexion = mysqli_connect($host, $user, $pass, $database);

    if ($conexion->connect_error)
        die("Conexión fallida: " . $conexion->connect_error);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $usuario = $_POST["usuario"];
        $contrasena = $_POST["contrasena"];
        $sql = "SELECT * FROM usuarios WHERE idusuario='$usuario'";
        $result = $conexion->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($contrasena, $row["contrasena"])) {
                $_SESSION["usuario"] = $usuario;
                $_SESSION["contrasena"] = $contrasena;
                $_SESSION["cifrada"] = $row["contrasena"];
                $_SESSION['user_logged_in']=true;
                header("Location: index.php");
            } else
                echo "No se ha iniciado sesion";
        } else
            echo "No se ha iniciado sesion (2)";
    }
?>