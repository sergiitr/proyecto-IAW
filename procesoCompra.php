<?php
    session_start();
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $idUsuario = $_POST['idUsuario'];
        $host = "localhost";
        $user = "root";
        $pass = "";
        $database = "tienda_videojuegos";
        $conexion = mysqli_connect($host, $user, $pass, $database);

        if (!$conexion)
            die("Error de conexión a la base de datos: " . mysqli_connect_error());

        // Validar y escapar datos para prevenir inyecciones SQL
        $idUsuario = mysqli_real_escape_string($conexion, $idUsuario);
        $totalGeneral = $_POST['totalGeneral'];
        // Insertar en la tabla 'compran'
        $queryCompran = "INSERT INTO compran (idUsuario, total) VALUES ('$idUsuario', '$totalGeneral')";
        $resultadoCompran = mysqli_query($conexion, $queryCompran);

        if ($resultadoCompran) {
            // Obtener el ID generado automáticamente para la compra
            $idPed = mysqli_insert_id($conexion);
            // Inicializar el total del pedido
            $totalPedido = 0;
            // Iterar sobre los elementos del carrito
            foreach ($_SESSION['carrito'] as $item) {
                $idJuego = $item['id_Juego'];
                $cantStock = $item['cantidad'];

                // Llamar al procedimiento almacenado para actualizar el stock
                $queryActualizarStock = "CALL ActualizarStock('$idJuego', '$cantStock')";
                $resultadoActualizarStock = mysqli_query($conexion, $queryActualizarStock);

                if (!$resultadoActualizarStock) 
                    die("Error al actualizar el stock: " . mysqli_error($conexion));

                // Obtener el precio del juego desde la base de datos
                $queryPrecio = "SELECT precio FROM juegos WHERE idJuego = '$idJuego'";
                $resultadoPrecio = mysqli_query($conexion, $queryPrecio);

                if ($resultadoPrecio) {
                    if ($filaPrecio = mysqli_fetch_assoc($resultadoPrecio)) {
                        $precioJuego = $filaPrecio['precio'];

                        $totalPorJuego = $cantStock * $precioJuego;
                        $totalPedido += $totalPorJuego;

                        $queryDetallesPedido = "INSERT INTO detalle_pedido (idPed, idJuego, cantidad) VALUES ('$idPed', '$idJuego', '$cantStock')";
                        $resultadoDetallesPedido = mysqli_query($conexion, $queryDetallesPedido);

                        if (!$resultadoDetallesPedido)
                            die("Error al insertar detalle_pedido: " . mysqli_error($conexion));
                    } else
                        die("No se encontró el juego en la base de datos. Nombre del juego: $idJuego");
                } else
                    die("Error al ejecutar la consulta para obtener el precio del juego: " . mysqli_error($conexion));
            }

            $queryActualizarTotal = "UPDATE compran SET total = '$totalPedido' WHERE idPed = '$idPed'";
            $resultadoActualizarTotal = mysqli_query($conexion, $queryActualizarTotal);

            if (!$resultadoActualizarTotal)
                die("Error al actualizar el total del pedido: " . mysqli_error($conexion));

            echo '<p>Compra realizada con éxito.</p>';
            unset($_SESSION['carrito']);
        } else
            die("Error al insertar en compran: " . mysqli_error($conexion));
        mysqli_close($conexion);
    }
?>