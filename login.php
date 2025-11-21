<?php
include_once("config.inc.php");
// Redirigir a index.php si ya hay una sesión activa
session_start();
if (isset($_SESSION["cidusuario"])){
    header("Location:". $GLOBALS["raiz_sitio"]."index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="estilos/login.css">
    <link rel="icon" href="imagenes/favicon.ico" type="image/x-icon">
    <title>Iniciar Sesión</title>
</head>
<body>
    <div class="login-card">
        <h1>Iniciar Sesión</h1>
        <?php if (isset($_GET['error'])): ?>
            <p class="error">Usuario o contraseña incorrectos. Inténtelo de nuevo.</p>
        <?php endif; ?>
        <form action="funciones/autenticar_login.php" method="post">
            <label for="usuario">Usuario:</label>
            <input type="text" id="usuario" name="txt_usuario" required><br><br>
            <label for="contrasena">Contraseña:</label>
            <input type="password" id="contrasena" name="txt_contrasena" required><br><br>
            <input type="submit" name="btn_login" value="Iniciar Sesión">
        </form>
        <!--Boton para regresar al inicio-->
        <a href="index.php" class="btn-volver">Regresar al Inicio</a>
    </div>
</body>
</html>