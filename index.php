<?php
include_once("config.inc.php");
include_once("funciones/sesiones.php");
include_once("funciones/listar_index.php");
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
    <title>Inicio -- Hotel Urbano</title>
    <link rel="stylesheet" href="estilos/index.css">
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
        <a href="carrito.php">Mi Carrito <i class="fa fa-shopping-cart"></i></a>
        
        <?php if ($sesion_activa): ?>
            <a href="funciones/logout.php" onclick="return confirm('¿Estás seguro?');">Cerrar Sesión</a>
        <?php else: ?>
            <a href="login.php">Iniciar Sesión</a>
        <?php endif; ?>
    </nav>
        
    <main>
        <h2>Hola, <?php echo htmlspecialchars($nombre_usuario); ?>!</h2>
        <!-- Alertar a visitantes de funciones restringidas -->
        <?php if (!$sesion_activa): ?>
            <div class="alerta-info" style="margin-bottom: 15px; color:#666;">
                <p>Estás navegando como <b>visitante</b>. <a href="login.php" style="color:var(--color-primario);">Inicia sesión</a> para reservar.</p>
            </div>
        <?php endif; ?>

        <!-- Form para buscar habitaciones -->
        <div class="buscador-principal">
            <h3>Encuentra tu habitación ideal</h3>
            <form id="form_busqueda" method="GET" action="resultados.php">
                <input type="text" class="txt_busqueda" name="termino" placeholder="Buscar por categoría..." required>
                <button type="submit" class="btn_buscar">Buscar</button>
            </form>
        </div>

        <hr style="border:0; border-top:1px solid #eee; margin:20px 0;">

        <h3>Nuestras Habitaciones</h3>
        
        <div class="carrusel-wrapper">
            <button class="carrusel-btn btn-prev" id="prevBtn">&#10094;</button>
            
            <div class="carrusel-track-container">
                <div class="carrusel-track" id="track">
                    <?php echo listarHabitacionesCards(); ?>
                </div>
            </div>
            
            <button class="carrusel-btn btn-next" id="nextBtn">&#10095;</button>
        </div>

    </main>
</body>
<script src="js/carrito.js"></script>
<script src="js/carrusel.js"></script>
</html>