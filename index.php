<?php
include_once("config.inc.php");
include_once("funciones/sesiones.php");
include_once("funciones/listar.php");
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio - Hotel Urbano</title>
    <link rel="stylesheet" href="estilos/index.css">
    <link rel="stylesheet" href="estilos/generales.css">
    <link rel="icon" href="imagenes/favicon.ico" type="image/x-icon">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <header>
        <div class="header-container">
            <h1>Hotel Urbano</h1>
        </div>
    </header>

    <nav>
        <a href="index.php" class="activo" style="background-color: var(--color-secundario);">Inicio</a>
        <?php if ($sesion_activa && $tipo_usuario == 'admin'): ?>
            <a href="admin/gestionar_habitaciones.php">Gestionar</a>
        <?php endif; ?>
        <a href="carrito.php">Mi Carrito <i class="fa fa-shopping-cart"></i></a>
        
        <?php if ($sesion_activa): ?>
            <a href="funciones/logout.php" onclick="return confirm('¿Estás seguro?');">Cerrar Sesi&oacute;n<i class="fas fa-sign-out-alt"></i></a>
        <?php else: ?>
            <a href="login.php">Iniciar Sesi&oacute;n</a>
        <?php endif; ?>
    </nav>
        
    <main>
        <h2>Hola, <?php echo htmlspecialchars($nombre_usuario); ?>!</h2>
        <!-- Alertar a visitantes de funciones restringidas -->
        <?php if (!$sesion_activa): ?>
            <div class="alerta-info" style="margin-bottom: 15px; color:#666;">
                <p>Est&aacute;s navegando como <b>visitante</b>. <a href="login.php" style="color:var(--color-primario);">Inicia sesi&oacute;n</a> para reservar.</p>
            </div>
        <?php endif; ?>

        <!-- Form para buscar habitaciones -->
        <div class="buscador-principal">
            <h3>Encuentra tu habitaci&oacute;n ideal</h3>
            <form id="form_busqueda" method="GET" action="resultados.php">
                <input type="text" class="txt_busqueda" name="termino" placeholder="Buscar por categoría..." required>
                <button type="submit" class="btn_buscar">Buscar</button>
            </form>
        </div>

        <hr>

        <h3>Nuestras Habitaciones</h3>
        
        <div id="habitaciones-container">
            <?php echo listarPorCategoria(); ?>
        </div>

    </main>
</body>
<script src="js/modal.js"></script>
<script src="js/validaciones.js"></script>
<script src="js/carrito.js"></script>
<script src="js/carrusel.js"></script>
</html>