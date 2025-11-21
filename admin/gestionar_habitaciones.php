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
    <link rel="stylesheet" href="../estilos/admin.css">
    <link rel="stylesheet" href="../estilos/generales.css">
    <link rel="icon" href="../imagenes/favicon.ico" type="image/x-icon">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <header>
        <div style="display:flex; justify-content:space-between; align-items:center;">
            <h1>Hotel Urbano <span style="font-size:0.6em; color:#666;">| Administraci&oacute;n</span></h1>
        </div>
    </header>
    <nav>
        <a href="../index.php">Ir al Sitio Web</a>
        <a href="../funciones/logout.php" onclick="return confirm('¿Cerrar sesión de administrador?');">Cerrar Sesi&oacute;n<i class="fas fa-sign-out-alt"></i></a>
    </nav>
    <main>
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
            <h2>Panel de Control</h2>
            <span>Bienvenido, <b><?php echo $_SESSION["cnombre_usuario"]; ?></b></span>
        </div>

        <div style="margin-bottom: 20px;">
            <a href="crear_habitacion.php" class="btn-accion"><i class="fa fa-plus"></i> Agregar Nueva Habitaci&oacute;n</a>
        </div>

        <h3>Inventario de Habitaciones</h3>
        
        <div style="overflow-x:auto;">
            <table>
                <thead>
                    <tr>
                        <th>C&oacute;digo</th>
                        <th width="10"></th>
                        <th>Categor&iacute;a</th>
                        <th width="10"></th>
                        <th>Precio</th>
                        <th width="10"></th>
                        <th>Capacidad</th>
                        <th width="10"></th>
                        <th>Disponibles</th>
                        <th width="10"></th>
                        <th>Imagen</th>
                        <th width="10"></th>
                        <th>Descripci&oacute;n</th>
                        <th width="10"></th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php echo listarHabitacionesAdmin(); ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>