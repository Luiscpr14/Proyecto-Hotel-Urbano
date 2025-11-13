<?php
include_once("config.inc.php"); // Asegúrate que este archivo define $servidor, $usuario, etc.
session_start();

// Validar sesión, si no hay usuario, mandar al login
if (!isset($_SESSION['cidusuario'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi Carrito de Reservas</title>
    <link rel="stylesheet" href="css/estilos.css">
    <style>
        /* Estilos simples para la tabla del carrito */
        .contenedor-carrito { width: 80%; margin: 20px auto; }
        table.carrito { width: 100%; border-collapse: collapse; }
        table.carrito th, table.carrito td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .total-row { font-weight: bold; background-color: #f2f2f2; }
        .form-fechas { background: #eee; padding: 15px; margin-top: 20px; border-radius: 5px;}
    </style>
</head>
<body>
    <header>
        <h1>Finalizar Reservación</h1>
        <nav><a href="index.php">Volver al listado</a></nav>
    </header>

    <main class="contenedor-carrito">
        <h2>Habitaciones Seleccionadas</h2>
        
        <table class="carrito">
            <thead>
                <tr>
                    <th>Habitación</th>
                    <th>Precio Unitario</th>
                    <th>Cantidad</th>
                    <th>Subtotal</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody id="tabla-carrito">
                </tbody>
            <tfoot>
                <tr class="total-row">
                    <td colspan="3" style="text-align:right">TOTAL:</td>
                    <td>$<span id="total-carrito">0.00</span></td>
                    <td></td>
                </tr>
            </tfoot>
        </table>

        <form action="funciones/procesar_reserva.php" method="POST" class="form-fechas">
            <h3>Detalles de la estadía</h3>
            
            <label for="checkin">Fecha de Check-in:</label>
            <input type="date" name="fecha_checkin" id="checkin" required min="<?php echo date('Y-m-d'); ?>">
            
            <label for="checkout">Fecha de Check-out:</label>
            <input type="date" name="fecha_checkout" id="checkout" required min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>">

            <input type="hidden" name="datos_reserva" id="datos_reserva_input">
            
            <br><br>
            <button type="submit" style="padding:10px 20px; font-size:16px; cursor:pointer;">Confirmar y Pagar</button>
        </form>
    </main>

    <script>
        // Validación simple de fechas en el cliente
        document.getElementById('checkin').addEventListener('change', function() {
            document.getElementById('checkout').min = this.value;
        });
    </script>
</body>
<script src="js/carrito.js"></script>
</html>