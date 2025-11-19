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
    <title>Inicio -- Hotel Urbano</title>
    <link rel="stylesheet" href="estilos/generales.css">
</head>
<body>
    <header>
        <h1>Bienvenido al Hotel Urbano</h1>
    </header>

    <nav>
        <a href="index.php">Inicio</a> 
        <a href="carrito.php">Mi Carrito</a>
        <!-- Mostrar enlaces de admin solo si la sesión está activa y el usuario es admin -->
        <?php if ($sesion_activa && $tipo_usuario == 'admin'): ?>
            <a href="admin/gestionar_habitaciones.php">Gestionar Habitaciones</a>
        <?php endif; ?>
        <!-- Alternar entre cierre de sesión y acceso a login/registro dependiendo de estado de sesión -->
        <?php if ($sesion_activa): ?>
            <a href="funciones/logout.php" onclick="return confirm('¿Estás seguro de que deseas cerrar sesión?');">Cerrar Sesión</a>
        <?php else: ?>
            <a href="login.php">Iniciar Sesión</a>
        <?php endif; ?>
    </nav>
        
    <main>
        <h2>Hola, <?php echo htmlspecialchars($nombre_usuario); ?>!</h2>
        <!-- Alertar a visitantes de funciones restringidas -->
        <?php if (!$sesion_activa): ?>
            <div class="alerta-info">
                <p>Estás navegando como <b>visitante</b>. <a href="login.php">Inicia sesión</a> para poder hacer reservaciones.</p>
            </div>
        <?php endif; ?>

        <!-- Form para buscar habitaciones -->
        <div class="buscador-principal">
            <h3>Buscar Habitaciones</h3>
            <form id="form_busqueda" method="GET" action="resultados.php">
                <input type="text" class="txt_busqueda" name="termino" placeholder="Busca por código, categoría o descripción..." required>
                <button type="submit" class="btn_buscar">Buscar</button>
            </form>
        </div>

        <hr>

        <h3>Habitaciones</h3>
        <table border="1" cellpadding="10">
        <thead>
            <tr>
                <th>Código</th>
                <th width="10">&nbsp;</th>
                <th>Categoría</th>
                <th width="10">&nbsp;</th>
                <th>Precio</th>
                <th width="10">&nbsp;</th>
                <th>Capacidad</th>
                <th width="10">&nbsp;</th>
                <th>Disponibles</th>
                <th width="10">&nbsp;</th>
                <th>Imagen</th>
                <th width="10">&nbsp;</th>
                <th>Descripción</th>
                <th width="10">&nbsp;</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            <?php echo listarPorCategoria(); ?>
        </tbody>
    </table>
    </main>
</body>
<script src="js/carrito.js"></script>
</html>