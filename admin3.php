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
                                        <option value="admin4">Copia de seguridad</option>
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

        <script src="opciones.js"></script>
        <script>
            <?php if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] == true) { ?>
                // Si el usuario ha iniciado sesión
                var logoutLink = document.getElementById('logoutLink');
                logoutLink.addEventListener('click', function () {
                    window.location.href = './cerrarSesion.php';
                });
            <?php } ?>
        </script>
        <div class="container">
            <h2>Formulario de Producto</h2>
            <form action="insertar_producto.php" method="POST" enctype="multipart/form-data">
                <label for="nombre">Nombre:</label><br>
                <input type="text" id="nombre" name="nombre" required><br><br>

                <label for="stock">Stock:</label><br>
                <input type="number" id="stock" name="stock" required><br><br>

                <label for="precio">Precio:</label><br>
                <input type="text" id="precio" name="precio" required><br><br>
                <label for="plataforma">Plataforma:</label><br>
                <select id="plataforma" name="plataforma">
                    <option value="xbox">Xbox</option>
                    <option value="pc">PC</option>
                    <option value="switch">Switch</option>
                    <option value="ps5">PS5</option>
                </select><br><br>
                <label for="idcompania">Compañía:</label><br>
                <select id="idcompania" name="idcompania">
                <?php
                    // Conexión a la base de datos
                    require_once "login.php";
                    $conexion = mysqli_connect($host, $user, $pass, $database);

                    // Verificar la conexión
                    if (mysqli_connect_error()) {
                        die("Error de conexión: " . mysqli_connect_error());
                    }

                    // Consultar las compañías desde la base de datos
                    $consulta_companias = "SELECT idCompania, nombreCompania FROM compania";
                    $resultado_companias = mysqli_query($conexion, $consulta_companias);

                    // Mostrar las opciones en el select
                    while ($fila = mysqli_fetch_assoc($resultado_companias)) {
                        echo "<option value='" . $fila['idCompania'] . "'>" . $fila['nombreCompania'] . "</option>";
                    }

                    // Cerrar la conexión
                    mysqli_close($conexion);
                ?>

                </select><br><br>
                <label for="imagen">Subir Imagen:</label><br>
                <label class="custum-file-upload" for="imagen">
                    <div class="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="" viewBox="0 0 24 24"><g stroke-width="0" id="SVGRepo_bgCarrier"></g><g stroke-linejoin="round" stroke-linecap="round" id="SVGRepo_tracerCarrier"></g><g id="SVGRepo_iconCarrier"> <path fill="" d="M10 1C9.73478 1 9.48043 1.10536 9.29289 1.29289L3.29289 7.29289C3.10536 7.48043 3 7.73478 3 8V20C3 21.6569 4.34315 23 6 23H7C7.55228 23 8 22.5523 8 22C8 21.4477 7.55228 21 7 21H6C5.44772 21 5 20.5523 5 20V9H10C10.5523 9 11 8.55228 11 8V3H18C18.5523 3 19 3.44772 19 4V9C19 9.55228 19.4477 10 20 10C20.5523 10 21 9.55228 21 9V4C21 2.34315 19.6569 1 18 1H10ZM9 7H6.41421L9 4.41421V7ZM14 15.5C14 14.1193 15.1193 13 16.5 13C17.8807 13 19 14.1193 19 15.5V16V17H20C21.1046 17 22 17.8954 22 19C22 20.1046 21.1046 21 20 21H13C11.8954 21 11 20.1046 11 19C11 17.8954 11.8954 17 13 17H14V16V15.5ZM16.5 11C14.142 11 12.2076 12.8136 12.0156 15.122C10.2825 15.5606 9 17.1305 9 19C9 21.2091 10.7909 23 13 23H20C22.2091 23 24 21.2091 24 19C24 17.1305 22.7175 15.5606 20.9844 15.122C20.7924 12.8136 18.858 11 16.5 11Z" clip-rule="evenodd" fill-rule="evenodd"></path> </g></svg>
                    </div>
                    <div class="text">
                    <span>Click to upload image</span>
                    </div>
                    <input type="file" id="imagen" name="imagen" accept="image/png, image/jpeg, image/gif, image/jpg, image/webp">
                </label><br>
                <input type="submit" value="Guardar Producto" required>
            </form>
        </div>
        <div class="item mt-2">
            <div class="row">
                <div class="izq">
                    <h2>SERGIITR GAMES</h2>
                    <h3>Tus marcas favorias, a tu alcance</h3>
                </div>
                <div class="der">
                    <div class="carta item mt-4 mr-2">
                        <a class="social-link1" href="http://www.instagram.com/sergiitr11">
                            <svg style="color: white" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-instagram" viewBox="0 0 16 16">
                            <path d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.917 3.917 0 0 0-1.417.923A3.927 3.927 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.916 3.916 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.926 3.926 0 0 0-.923-1.417A3.911 3.911 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0h.003zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599.28.28.453.546.598.92.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.47 2.47 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.478 2.478 0 0 1-.92-.598 2.48 2.48 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233 0-2.136.008-2.388.046-3.231.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92.28-.28.546-.453.92-.598.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045v.002zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92zm-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217zm0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334z" fill="white">
                            </path>
                            </svg>
                        </a>
                        <a class="social-link2" href="https://github.com/sergiitr/">
                            <svg viewBox="0 0 496 512" height="1em" fill="#fff" xmlns="http://www.w3.org/2000/svg">
                            <path d="M165.9 397.4c0 2-2.3 3.6-5.2 3.6-3.3.3-5.6-1.3-5.6-3.6 0-2 2.3-3.6 5.2-3.6 3-.3 5.6 1.3 5.6 3.6zm-31.1-4.5c-.7 2 1.3 4.3 4.3 4.9 2.6 1 5.6 0 6.2-2s-1.3-4.3-4.3-5.2c-2.6-.7-5.5.3-6.2 2.3zm44.2-1.7c-2.9.7-4.9 2.6-4.6 4.9.3 2 2.9 3.3 5.9 2.6 2.9-.7 4.9-2.6 4.6-4.6-.3-1.9-3-3.2-5.9-2.9zM244.8 8C106.1 8 0 113.3 0 252c0 110.9 69.8 205.8 169.5 239.2 12.8 2.3 17.3-5.6 17.3-12.1 0-6.2-.3-40.4-.3-61.4 0 0-70 15-84.7-29.8 0 0-11.4-29.1-27.8-36.6 0 0-22.9-15.7 1.6-15.4 0 0 24.9 2 38.6 25.8 21.9 38.6 58.6 27.5 72.9 20.9 2.3-16 8.8-27.1 16-33.7-55.9-6.2-112.3-14.3-112.3-110.5 0-27.5 7.6-41.3 23.6-58.9-2.6-6.5-11.1-33.3 2.6-67.9 20.9-6.5 69 27 69 27 20-5.6 41.5-8.5 62.8-8.5s42.8 2.9 62.8 8.5c0 0 48.1-33.6 69-27 13.7 34.7 5.2 61.4 2.6 67.9 16 17.7 25.8 31.5 25.8 58.9 0 96.5-58.9 104.2-114.8 110.5 9.2 7.9 17 22.9 17 46.4 0 33.7-.3 75.4-.3 83.6 0 6.5 4.6 14.4 17.3 12.1C428.2 457.8 496 362.9 496 252 496 113.3 383.5 8 244.8 8zM97.2 352.9c-1.3 1-1 3.3.7 5.2 1.6 1.6 3.9 2.3 5.2 1 1.3-1 1-3.3-.7-5.2-1.6-1.6-3.9-2.3-5.2-1zm-10.8-8.1c-.7 1.3.3 2.9 2.3 3.9 1.6 1 3.6.7 4.3-.7.7-1.3-.3-2.9-2.3-3.9-2-.6-3.6-.3-4.3.7zm32.4 35.6c-1.6 1.3-1 4.3 1.3 6.2 2.3 2.3 5.2 2.6 6.5 1 1.3-1.3.7-4.3-1.3-6.2-2.2-2.3-5.2-2.6-6.5-1zm-11.4-14.7c-1.6 1-1.6 3.6 0 5.9 1.6 2.3 4.3 3.3 5.6 2.3 1.6-1.3 1.6-3.9 0-6.2-1.4-2.3-4-3.3-5.6-2z">
                            </path>
                            </svg>
                        </a>
                        <a class="social-link4" href="https://es.linkedin.com/in/sergiitr11">
                            <svg fill="#fff" viewBox="0 0 448 512" height="1em" xmlns="http://www.w3.org/2000/svg">
                            <path d="M100.28 448H7.4V148.9h92.88zM53.79 108.1C24.09 108.1 0 83.5 0 53.8a53.79 53.79 0 0 1 107.58 0c0 29.7-24.1 54.3-53.79 54.3zM447.9 448h-92.68V302.4c0-34.7-.7-79.2-48.29-79.2-48.29 0-55.69 37.7-55.69 76.7V448h-92.78V148.9h89.08v40.8h1.3c12.4-23.5 42.69-48.3 87.88-48.3 94 0 111.28 61.9 111.28 142.3V448z">
                            </path>
                            </svg>
                        </a>
                        <a class="social-link5" href="https://stackoverflow.com/users/22172718/sergio-trillo">
                            <svg viewBox="0 0 16 16" class="bi bi-stack-overflow" fill="currentColor" height="16" width="16" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12.412 14.572V10.29h1.428V16H1v-5.71h1.428v4.282h9.984z"></path>
                            <path d="M3.857 13.145h7.137v-1.428H3.857v1.428zM10.254 0 9.108.852l4.26 5.727 1.146-.852L10.254 0zm-3.54 3.377 5.484 4.567.913-1.097L7.627 2.28l-.914 1.097zM4.922 6.55l6.47 3.013.603-1.294-6.47-3.013-.603 1.294zm-.925 3.344 6.985 1.469.294-1.398-6.985-1.468-.294 1.397z"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
