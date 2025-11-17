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
    
    // --- NUEVO: Calcular días de estancia (noches) ---
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
    // --- Fin cálculo de días ---


    //Abrir conexión usando tu función
    $conn = abrirConexion();
    seleccionarBaseDatos($conn);

    try {
        //Iniciar Transacción
        $conn->begin_transaction();

        // --- MODIFICADO: Calcular total en base a los días de estancia ---
        $total_por_noche = 0;
        foreach ($carrito as $item) {
            $total_por_noche += ($item['precio'] * $item['cantidad']);
        }
        $total_reserva = $total_por_noche * $dias_estancia;
        // --- Fin cálculo total ---

        //Insertar en tabla 'reservaciones'
        $sql_reserva = "INSERT INTO reservaciones (id_usuario, fecha_checkin, fecha_checkout, total, estado) 
                        VALUES (?, ?, ?, ?, 'aceptada')";
        
        $stmt = $conn->prepare($sql_reserva);
        $stmt->bind_param("issd", $id_usuario, $checkin, $checkout, $total_reserva);
        
        if (!$stmt->execute()) {
            throw new Exception("Error al crear la reservación: " . $stmt->error);
        }
        $id_reserva = $conn->insert_id;
        $stmt->close();

        //Procesar cada item del carrito
        $sql_detalle = "INSERT INTO detalle_reservacion (id_reservacion, id_habitacion, cantidad, precio_unitario) 
                        VALUES (?, ?, ?, ?)";
        $stmt_detalle = $conn->prepare($sql_detalle);

        //Query para descontar stock
        $sql_update = "UPDATE habitaciones SET disponibles = disponibles - ? 
                       WHERE id_habitacion = ? AND disponibles >= ?";
        $stmt_update = $conn->prepare($sql_update);

        foreach ($carrito as $item) {
            $id_hab = $item['id'];
            $cantidad = $item['cantidad'];
            $precio = $item['precio']; // Este sigue siendo el precio unitario por noche

            // a) Insertar detalle
            $stmt_detalle->bind_param("iiid", $id_reserva, $id_hab, $cantidad, $precio);
            if (!$stmt_detalle->execute()) {
                throw new Exception("Error al guardar detalles.");
            }

            // b) Descontar disponibilidad
            // NOTA: Esto descuenta la habitación para todo el rango de fechas.
            // Una implementación más avanzada requeriría verificar la disponibilidad
            // por día, pero para este proyecto, descontar el "stock" general funciona.
            $stmt_update->bind_param("iii", $cantidad, $id_hab, $cantidad);
            $stmt_update->execute();

            if ($stmt_update->affected_rows === 0) {
                // Si (disponibles < cantidad), la transacción falla.
                throw new Exception("No hay suficiente disponibilidad para la habitación: " . $item['numero']);
            }
        }

        $conn->commit();
        
        //Limpiamos la cookie del carrito (Nombre debe coincidir con JS)
        setcookie('carrito_urbano', '', time() - 3600, '/');

        // --- MODIFICADO: Mensaje de éxito con detalles ---
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
        $conn->rollback();
        echo "<h1>Error en la reserva</h1>";
        echo "<p>Se ha producido un error y tu reserva no pudo ser completada. No se ha realizado ningún cargo.</p>";
        echo "<p><strong>Detalle:</strong> " . $e->getMessage() . "</p>";
        echo "<a href='../carrito.php'>Volver al carrito</a>";
    }

    cerrarConexion($conn);

} else {
    header("Location: ../index.php");
}
?>