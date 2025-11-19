<?php
include_once("../config.inc.php");
include_once("acceso_bd.php");
include_once("sesiones.php");
validarSesion();

//Validar datos POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $checkin = $_POST['fecha_checkin'];
    $checkout = $_POST['fecha_checkout'];
    $json_carrito = $_POST['datos_reserva'];
    $id_usuario = $_SESSION['cidusuario'];

    $carrito = json_decode($json_carrito, true);

    if (empty($carrito)) {
        die("Error: El carrito está vacío.");
    }
    
    //Calcular días de estancia (noches)
    try {
        $date_checkin = new DateTime($checkin);
        $date_checkout = new DateTime($checkout);
        
        if ($date_checkout <= $date_checkin) {
             throw new Exception("La fecha de Check-out debe ser posterior a la de Check-in.");
        }
        
        $intervalo = $date_checkin->diff($date_checkout);
        $dias_estancia = $intervalo->days;
        
        if ($dias_estancia <= 0) {
            throw new Exception("La estancia debe ser de al menos 1 noche.");
        }

    } catch (Exception $e) {
        die("Error en las fechas: " . $e->getMessage());
    }

    //Abrir conexión 
    $conn = abrirConexion();
    seleccionarBaseDatos($conn);

    try {
        //Iniciar Transacción
        mysqli_begin_transaction($conn);

        //Calcular total en base a los días de estancia
        $total_por_noche = 0;
        foreach ($carrito as $item) {
            $total_por_noche += ($item['precio'] * $item['cantidad']);
        }
        $total_reserva = $total_por_noche * $dias_estancia;

        //Insertar en tabla 'reservaciones'
        $sql_reserva = "INSERT INTO reservaciones (id_usuario, fecha_checkin, fecha_checkout, total, estado) 
                        VALUES (?, ?, ?, ?, 'aceptada')";
        
        $stmt = mysqli_prepare($conn, $sql_reserva);
        mysqli_stmt_bind_param($stmt, "issd", $id_usuario, $checkin, $checkout, $total_reserva);
        
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Error al crear la reservación: " . mysqli_error($conn));
        }
        $id_reserva = mysqli_insert_id($conn);
        mysqli_stmt_close($stmt);

        //Procesar cada item del carrito
        $sql_detalle = "INSERT INTO detalle_reservacion (id_reservacion, id_habitacion, cantidad, precio_unitario) 
                        VALUES (?, ?, ?, ?)";
        $stmt_detalle = mysqli_prepare($conn, $sql_detalle);

        //Query para descontar stock
        $sql_update = "UPDATE habitaciones SET disponibles = disponibles - ? 
                       WHERE id_habitacion = ? AND disponibles >= ?";
        $stmt_update = mysqli_prepare($conn, $sql_update);

        foreach ($carrito as $item) {
            $id_hab = $item['id'];
            $cantidad = $item['cantidad'];
            $precio = $item['precio'];

            // a) Insertar detalle
            mysqli_stmt_bind_param($stmt_detalle, "iiid", $id_reserva, $id_hab, $cantidad, $precio);
            if (!mysqli_stmt_execute($stmt_detalle)) {
                throw new Exception("Error al guardar detalles: " . mysqli_error($conn));
            }

            // b) Descontar disponibilidad
            mysqli_stmt_bind_param($stmt_update, "iii", $cantidad, $id_hab, $cantidad);
            mysqli_stmt_execute($stmt_update);

            if (mysqli_affected_rows($conn) === 0) {
                throw new Exception("No hay suficiente disponibilidad para la habitación: " . $item['numero']);
            }
        }

        // Confirmar transacción
        mysqli_commit($conn);
        
        //Limpiamos la cookie del carrito
        setcookie('carrito_urbano', '', time() - 3600, '/');

        // Mensaje de éxito
        echo "<h1>¡Reserva Exitosa!</h1>";
        echo "<p>Tu reserva ha sido procesada.</p>";
        echo "<ul>";
        echo "<li>Check-in: $checkin</li>";
        echo "<li>Check-out: $checkout</li>";
        echo "<li>Noches de estancia: $dias_estancia</li>";
        echo "<li>Total por noche: $$total_por_noche</li>";
        echo "<li><strong>Total pagado: $$total_reserva</strong></li>";
        echo "</ul>";
        echo "<a href='../index.php'>Volver al inicio</a>";

    } catch (Exception $e) {
        //Si algo falla, deshacemos todo
        mysqli_rollback($conn);
        echo "<h1>Error en la reserva</h1>";
        echo "<p>Se ha producido un error y tu reserva no pudo ser completada. No se ha realizado ningún cargo.</p>";
        echo "<p><strong>Detalle:</strong> " . $e->getMessage() . "</p>";
        echo "<a href='../carrito.php'>Volver al carrito</a>";
    }

    cerrarConexion($conn);

} else {
    header("Location: ".$GLOBALS["raiz-sitio"]."index.php");
}
?>