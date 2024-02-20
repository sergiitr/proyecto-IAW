<?php 
    session_start();
    if (!isset($_SESSION["usuario"]) || $_SESSION["administrador"] !== 1) {
        header('Location: index.php');
        exit;
    }
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
        <title>Proyecto</title>
        <link rel="stylesheet" href="styles.css">
        <link rel="shortcut icon" href="./imagenes/logo.jpeg"/>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    </head>
    <body>
        <div id="psup" class="container-fluid mt-2">
            <table id="tablaSecciones">
                <tr class="align-middle">
                    <td class="tdDatos">
                        <p class="principal"><a class="enlacePaginaActual" href="./index.php">PAGINA PRINCIPAL</a></p>
                    </td>
                    <td class="tdDatos">
                        <p class="sobreNos"><a class="enlacePaginaActual" href="./nosotros.php">SOBRE NOSOTROS</a></p>
                    </td>
                    <?php
                        if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] == true) {
                            // Verificar si el usuario no es root
                            if ($_SESSION["administrador"] != 1) {
                                echo '
                                    <td class="tdDatos">
                                        <select aria-label="Default select example" onchange="redirectPage(this.value)">
                                            <option selected disabled>SELECCIONE CARRITO</option>
                                            <option value="carrito">CARRITO VENTA</option>
                                            <option value="alquiler">CARRITO ALQUILER</option>
                                        </select>
                                    </td>
                                ';
                            }
                            echo '
                                <td class="tdDatos">
                                    <div class="user-info">
                                        <p class="username">¡Hola, ',$_SESSION["usuario"],'!</p>';
                            // Verificar si el usuario es administrador
                            if ($_SESSION["administrador"] == 1) {
                                echo '
                                    <select aria-label="Default select example" onchange="redirectPage2(this.value)">
                                        <option selected disabled>Seleccione una opción</option>
                                        <option value="admin">Administrar Usuarios</option>
                                        <option value="admin2">Administrar Stock</option>
                                        <option value="admin3">Añadir Videojuegos</option>
                                        <option value="cerrarSesion">Cerrar sesión</option>
                                    </select>
                                    <a id="logoutLink" class="logout-link" style="display: none;" onclick="cerrarSesion()">Cerrar sesión</a>';
                            } else {
                                echo '
                                    <select aria-label="Default select example" onchange="redirectPage2(this.value)">
                                        <option selected disabled>Seleccione una opción</option>
                                        <option value="pedidos">Mis pedidos</option>
                                        <option value="cerrarSesion">Cerrar sesión</option>
                                        <option value="borrarUsuario">Borrar Usuario</option>
                                    </select>
                                    <a id="logoutLink" class="logout-link" style="display: none;" onclick="cerrarSesion()">Cerrar sesión</a>';
                            }
                            echo '
                                    </div>
                                </td>
                            ';
                        }else {
                            echo '
                                <td class="tdDatos">
                                    <p class="sobreNos"><a class="enlacePaginaActual" href="./crearUsuario.php">Crear Usuario</a></p>
                                </td>
                                <td class="tdDatos">
                                    <p class="carrito"><a class="enlacesPaginas" href="./formInicioSesion.php">Inicio Sesion</a></p>
                                </td>
                            ';
                        }
                    ?>
                </tr>
            </table>
        </div>

        <script>
            var logoutLink = document.getElementById("logout-link");
            function redirectPage(value) {
                if (value === "carrito")
                    window.location.href = "./carrito.php";
                else if (value === "alquiler")
                    window.location.href = "./alquiler.php";
                
            }
            function redirectPage2(value) {
                if (value === "pedidos")
                    window.location.href = "./cliente.php";
                else if (value === "cerrarSesion") {
                    console.log("Cerrando sesión...");
                    logoutLink.style.display = "block";
                    cerrarSesion();
                }  else if (value === "borrarUsuario") {
                    // Confirmar antes de borrar
                    var confirmar = confirm("¿Está seguro de que desea borrar su usuario? Esta acción no se puede deshacer.");
                    if (confirmar) 
                        window.location.href = "./borrarUsuario.php";
                } else if (value == "admin")
                    window.location.href = "./admin.php";
                else if (value == "admin2")
                    window.location.href = "./admin2.php";
                else if (value == "admin3")
                    window.location.href = "./admin3.php";
            }
            
            function cerrarSesion() {
                window.location.href = './cerrarSesion.php';
            }
        </script>
        <script>
            <?php if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] == true) { ?>
                // Si el usuario ha iniciado sesión
                var logoutLink = document.getElementById('logoutLink');
                logoutLink.addEventListener('click', function () {
                    window.location.href = './cerrarSesion.php';
                });
            <?php } ?>
        </script>
        <div class="ms-3">
            <h2>Formulario de Producto</h2>
            <form action="insertar_producto.php" method="POST" enctype="multipart/form-data">
                <label for="nombre">Nombre:</label><br>
                <input type="text" id="nombre" name="nombre"><br><br>

                <label for="stock">Stock:</label><br>
                <input type="number" id="stock" name="stock"><br><br>

                <label for="precio">Precio:</label><br>
                <input type="text" id="precio" name="precio"><br><br>
                <label for="plataforma">Plataforma:</label><br>
                <select id="plataforma" name="plataforma">
                    <option value="xbox">Xbox</option>
                    <option value="pc">PC</option>
                    <option value="switch">Switch</option>
                    <option value="ps5">PS5</option>
                </select><br><br>
                <label for="idcompania">Compañía:</label><br>
                <select id="idcompania" name="idcompania">
                    <!-- Aquí se cargarán las opciones de compañías desde la base de datos usando PHP -->
                    <?php
                        // Conexión a la base de datos
                        require_once "login.php";
                        $conexion = mysqli_connect($host, $user, $pass, $database);
                        
                        // Verificar la conexión
                        if ($conexion->connect_error) {
                            die("Error de conexión: " . $conexion->connect_error);
                        }

                        // Consultar las compañías desde la base de datos
                        $consulta_companias = "SELECT idCompania, nombreCompania FROM compania";
                        $resultado_companias = $conexion->query($consulta_companias);

                        // Mostrar las opciones en el select
                        while ($fila = $resultado_companias->fetch_assoc()) {
                            echo "<option value='" . $fila['idCompania'] . "'>" . $fila['nombreCompania'] . "</option>";
                        }

                        // Cerrar la conexión
                        $conexion->close();
                    ?>
                </select><br><br>
                <label for="stock">Subir Imagen:</label><br>
                <label class="custum-file-upload" for="imagen">
                    <div class="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="" viewBox="0 0 24 24"><g stroke-width="0" id="SVGRepo_bgCarrier"></g><g stroke-linejoin="round" stroke-linecap="round" id="SVGRepo_tracerCarrier"></g><g id="SVGRepo_iconCarrier"> <path fill="" d="M10 1C9.73478 1 9.48043 1.10536 9.29289 1.29289L3.29289 7.29289C3.10536 7.48043 3 7.73478 3 8V20C3 21.6569 4.34315 23 6 23H7C7.55228 23 8 22.5523 8 22C8 21.4477 7.55228 21 7 21H6C5.44772 21 5 20.5523 5 20V9H10C10.5523 9 11 8.55228 11 8V3H18C18.5523 3 19 3.44772 19 4V9C19 9.55228 19.4477 10 20 10C20.5523 10 21 9.55228 21 9V4C21 2.34315 19.6569 1 18 1H10ZM9 7H6.41421L9 4.41421V7ZM14 15.5C14 14.1193 15.1193 13 16.5 13C17.8807 13 19 14.1193 19 15.5V16V17H20C21.1046 17 22 17.8954 22 19C22 20.1046 21.1046 21 20 21H13C11.8954 21 11 20.1046 11 19C11 17.8954 11.8954 17 13 17H14V16V15.5ZM16.5 11C14.142 11 12.2076 12.8136 12.0156 15.122C10.2825 15.5606 9 17.1305 9 19C9 21.2091 10.7909 23 13 23H20C22.2091 23 24 21.2091 24 19C24 17.1305 22.7175 15.5606 20.9844 15.122C20.7924 12.8136 18.858 11 16.5 11Z" clip-rule="evenodd" fill-rule="evenodd"></path> </g></svg>
                    </div>
                    <div class="text">
                    <span>Click to upload image</span>
                    </div>
                    <input type="file" id="imagen" name="imagen">
                </label><br>
                <input type="submit" value="Guardar Producto">
            </form>
        </div>
    </body>
</html>
