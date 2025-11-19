<?php
include_once("config.inc.php");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi Carrito de Reservas</title>
    <link rel="stylesheet" href="css/estilos.css">
    <style>
        /* Estilos temporales para la tabla del carrito */
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
                    <th>Subtotal (por noche)</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody id="tabla-carrito">
                </tbody>
            
            <tfoot>
                <tr class="total-row">
                    <td colspan="3" style="text-align:right"><strong>Total por Noche:</strong></td>
                    <td><strong>$<span id="total-noche">0.00</span></strong></td>
                    <td></td>
                </tr>
                <tr class="total-row">
                    <td colspan="3" style="text-align:right">Días de Estancia:</td>
                    <td><span id="dias-estancia">0</span></td>
                    <td></td>
                </tr>
                <tr class="total-row" style="font-size: 1.2em; border-top: 2px solid #333;">
                    <td colspan="3" style="text-align:right"><strong>TOTAL ESTANCIA:</strong></td>
                    <td><strong>$<span id="total-estancia">0.00</span></strong></td>
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

    </body>
<script src="js/carrito.js"></script>
</html>