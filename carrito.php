<?php
include_once("config.inc.php");
include_once("funciones/sesiones.php"); 

session_start();
$sesion_activa = isset($_SESSION['cidusuario']);
$tipo_usuario = $_SESSION['ctipo_usuario'] ?? 'visitante';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Carrito - Hotel Urbano</title>
    
    <!-- Estilos -->
    <link rel="stylesheet" href="estilos/carrito.css">
    <link rel="stylesheet" href="estilos/generales.css">
    <link rel="icon" href="imagenes/favicon.ico" type="image/x-icon">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <header>
        <div style="display:flex; justify-content:space-between; align-items:center;">
            <h1>Hotel Urbano</h1>
        </div>
    </header>
    <nav>
        <a href="index.php">Inicio</a>
        <?php if ($sesion_activa && $tipo_usuario == 'admin'): ?>
            <a href="admin/gestionar_habitaciones.php">Gestionar</a>
        <?php endif; ?>
        
        <a href="carrito.php" class="activo">Mi Carrito <i class="fa fa-shopping-cart"></i></a>
        
        <?php if ($sesion_activa): ?>
            <a href="funciones/logout.php" onclick="return confirm('¿Cerrar sesión?');">Cerrar Sesi&oacute;n<i class="fas fa-sign-out-alt"></i></a>
        <?php else: ?>
            <a href="login.php">Iniciar Sesi&oacute;n</a>
        <?php endif; ?>
    </nav>
    <main class="contenedor-carrito">
        <h2><i class="fa fa-clipboard-check"></i> Finalizar Reservaci&oacute;n</h2>
        <div class="tabla-responsive">
            <table class="carrito">
                <thead>
                    <tr>
                        <th width="100">Imagen</th>
                        <th>Habitación</th>
                        <th>Precio</th>
                        <th style="text-align:center;">Cantidad</th>
                        <th>Subtotal</th>
                        <th style="text-align:center;">Borrar</th>
                    </tr>
                </thead>
                <tbody id="tabla-carrito">
                    <!--Se llena con JS-->
                </tbody>
                
                <tfoot>
                    <tr class="total-row">
                        <td colspan="4" style="text-align:right"><strong>Total por Noche:</strong></td>
                        <td colspan="2"><strong>$<span id="total-noche">0.00</span></strong></td>
                    </tr>
                    <tr class="total-row">
                        <td colspan="4" style="text-align:right">D&iacute;as de Estancia:</td>
                        <td colspan="2"><span id="dias-estancia">0</span></td>
                    </tr>
                    <tr class="total-row total-final">
                        <td colspan="4" style="text-align:right"><strong>TOTAL A PAGAR:</strong></td>
                        <td colspan="2"><strong>$<span id="total-estancia">0.00</span></strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <form action="funciones/procesar_reserva.php" method="POST" class="form-fechas">
            <h3><i class="fa fa-calendar-alt"></i> Detalles de tu estad&iacute;a</h3>
            
            <div class="fechas-flex">
                <div class="grupo-fecha">
                    <label for="checkin">Fecha de Llegada (Check-in):</label>
                    <input type="date" name="fecha_checkin" id="checkin" required min="<?php echo date('Y-m-d'); ?>">
                </div>
                
                <div class="grupo-fecha">
                    <label for="checkout">Fecha de Salida (Check-out):</label>
                    <input type="date" name="fecha_checkout" id="checkout" required min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>">
                </div>
            </div>

            <input type="hidden" name="datos_reserva" id="datos_reserva_input">
            
            <br>
            
            <?php if ($sesion_activa): ?>
                <button type="submit" class="btn-confirmar">Confirmar y Reservar</button>
            <?php else: ?>
                <div class="alerta-login">
                    Para confirmar tu reserva, por favor <a href="login.php">Inicia Sesi&oacute;n</a>.
                </div>
            <?php endif; ?>
            <input type="button" name="btn_cancelar" value="Cancelar" onclick="window.location.href='index.php';">
        </form>
    </main>
</body>
<script src="js/carrito.js"></script>
<script src="js/validaciones.js"></script>
</html>