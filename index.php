<?php
include_once("config.inc.php");
include_once("funciones/sesiones.php");
//No se valida sesión en index.php ya que es una de las páginas accesibles sin iniciar sesión
session_start();
$sesion_activa = isset($_SESSION['cidusuario']);
$tipo_usuario = $_SESSION['ctipo_usuario'] ?? 'visitante';
$nombre_usuario = $_SESSION['cnombre_usuario'] ?? 'Visitante';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inicio -- Hotel Urbano</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <header>
        <h1>Bienvenido al Hotel Urbano</h1>
        <nav>
            <a href="index.php">Inicio</a>
            <!-- Mostrar enlaces de admin solo si la sesión está activa y el usuario es admin -->
            <?php if ($sesion_activa && $tipo_usuario == 'admin'): ?>
                <a href="admin/gestionar_habitaciones.php">Gestionar Habitaciones</a>
            <?php endif; ?>
            <!-- Alternar entre cierre de sesión y acceso a login/registro dependiendo de estado de sesión -->
            <?php if ($sesion_activa): ?>
                <a href="funciones/logout.php">Cerrar Sesión</a>
            <?php else: ?>
                <a href="login.php">Iniciar Sesión</a>
            <?php endif; ?>
        </nav>
    </header>
    
    <main>
        <h2>Hola, <?php echo $nombre_usuario; ?>!</h2>
        <!-- Alertar a visitantes de funciones restringidas -->
        <?php if (!$sesion_activa): ?>
            <div class="alerta-info">
                <p>Estás navegando como <b>visitante</b>. <a href="login.php">Inicia sesión</a> para poder hacer reservaciones.</p>
            </div>
        <?php endif; ?>

        <h3>Habitaciones Disponibles</h3>
        <!-- Las habitaciones se generarán dinámicamente desde la base de datos -->
        
        <?php if ($sesion_activa): ?>
            <!-- Solo usuarios logueados ven el botón de reservar -->
        <?php endif; ?>
    </main>
</body>
</html>