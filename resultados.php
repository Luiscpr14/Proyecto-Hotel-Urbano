<?php 
include_once("config.inc.php");
include_once("funciones/sesiones.php");
include_once("funciones/buscar.php");
//No se valida sesión en resultados.php ya que es una de las páginas accesibles sin iniciar sesión
session_start();
$sesion_activa = isset($_SESSION['cidusuario']);
$tipo_usuario = $_SESSION['ctipo_usuario'] ?? 'visitante';
$nombre_usuario = $_SESSION['cnombre_usuario'] ?? 'Visitante';

if (isset($_GET['termino']) || isset($_POST['txt_termino'])) {
    $termino_busqueda = $_GET['termino'] ?? $_POST['txt_termino'];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados: <?php echo htmlspecialchars($termino_busqueda ?? ''); ?> - Hotel Urbano</title>
    
    <!-- Estilos -->
    <link rel="stylesheet" href="estilos/generales.css">
    <link rel="stylesheet" href="estilos/index.css"> <!--Para el buscador-->
    <link rel="stylesheet" href="estilos/resultados.css">
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
        <a href="index.php">Inicio</a>
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
        <h2>Resultados de búsqueda</h2>        
        <?php if (!$sesion_activa): ?>
            <div class="alerta-info" style="margin-bottom: 15px; color:#666;">
                <p>Recuerda <a href="login.php" style="color:var(--color-primario);">iniciar sesión</a> para completar tu reserva.</p>
            </div>
        <?php endif; ?>
        <div class="buscador-principal">
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" id="form_busqueda">
                <input type="text" class="txt_busqueda" name="txt_termino" value="<?php echo htmlspecialchars($termino_busqueda ?? ''); ?>" placeholder="Busca por código, categoría..." required>
                <button type="submit" class="btn_buscar">Buscar de nuevo</button>
            </form>
        </div>

        <hr>
        <?php echo buscarHabitaciones(); ?>
    </main>
</body>
<script src="js/carrito.js"></script>
</html>