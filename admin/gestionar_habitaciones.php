<?php
include_once("../config.inc.php");
include_once("../funciones/sesiones.php");
include_once("../funciones/listar.php");
//Página solo accesible para administradores
validarSesion();
validarAdmin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de habitaciones</title>
    <link rel="stylesheet" href="../estilos/generales.css">
</head>
<body>
    <header>
        <h1>Gestión de habitaciones</h1>
    </header>
    <nav>
        <a href="../index.php">Volver al inicio</a>
        <a href="../funciones/logout.php" >Cerrar Sesión</a>
    </nav>
    <main>
        <h2>Bienvenido, administrador <?php echo $_SESSION["cnombre_usuario"]; ?>!</h2>
    
        <a href="crear_habitacion.php">Agregar Habitación</a>

        <h3>Habitaciones</h3>
        <!-- Diseño en tabla temporal para debbugging -->
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
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php echo listarHabitacionesAdmin(); ?>
            </tbody>
        </table>
    </main>
</body>
</html>