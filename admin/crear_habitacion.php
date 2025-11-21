<?php
include_once("../config.inc.php");
include_once("../funciones/sesiones.php");
include_once("../funciones/acceso_bd.php");
include_once("../funciones/crear.php");
//Página solo accesible para administradores
validarSesion();
validarAdmin();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear</title>
    <link rel="stylesheet" href="../estilos/admin.css">
    <link rel="stylesheet" href="../estilos/generales.css">
    <link rel="icon" href="../imagenes/favicon.ico" type="image/x-icon">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <header>
        <h1>Hotel Urbano <span style="font-size:0.6em; color:#666;">| Administraci&oacute;n</span></h1>
    </header>
    <nav>
        <a href="../index.php">Ir al Sitio Web</a>
        <a href="../funciones/logout.php" onclick="return confirm('¿Cerrar sesión?');">Cerrar Sesi&oacute;n <i class="fa fa-sign-out-alt" style="margin-left:5px;"></i></a>
    </nav>
    <main>
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
            <h2>Agregar Nueva Habitaci&oacute;n</h2>
            <!-- Botón Volver en el cuerpo -->
            <a href="gestionar_habitaciones.php" class="btn-accion btn-volver"><i class="fa fa-arrow-left"></i> Volver al listado</a>
        </div>
        
        <form id="crear_form" name="frm_agregar" action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST" enctype="multipart/form-data">
            <?php 
            $mensaje = agregarHabitacion();
            if($mensaje): ?>
                <p class="estado"><?php echo $mensaje; ?></p>
            <?php endif; ?>

            <table>
                <tr>
                    <td><label for="codigo">C&oacute;digo:</label></td>
                    <td><input type="text" id="codigo" name="txt_codigo" required placeholder="Ej: SVJR5" maxlength="5"></td>
                </tr>
                <tr>
                    <td><label for="categoria">Categor&iacute;a:</label></td>
                    <td>
                        <select id="categoria" name="slct_categoria" required>
                            <option value="">-- Seleccionar --</option>
                            <option value="Sencilla">Sencilla</option>
                            <option value="Doble">Doble</option>
                            <option value="Suite">Suite</option>
                            <option value="Ejecutiva">Ejecutiva</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label for="precio">Precio (MXN):</label></td>
                    <td><input type="number" id="precio" name="txt_precio" step="0.01" min="0" required placeholder="0.00"></td>
                </tr>
                <tr>
                    <td><label for="capacidad">Capacidad (personas):</label></td>
                    <td><input type="number" id="capacidad" name="txt_capacidad" min="1" required></td>
                </tr>
                <tr>
                    <td><label for="disponibles">Cuartos disponibles:</label></td>
                    <td>
                        <input type="number" id="disponibles" name="txt_disponibles" min="0" required>
                    </td>
                </tr>
                <tr>
                    <td><label for="descripcion">Descripci&oacute;n:</label></td>
                    <td><textarea id="descripcion" name="txt_descripcion" rows="4" required placeholder="Detalles de la habitación..."></textarea></td>
                </tr>
                <tr>
                    <td><label for="imagen">Imagen:</label></td>
                    <td><input type="file" id="imagen" name="fl_imagen" accept="image/*"></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <hr style="margin: 20px 0; border: 0; border-top: 1px solid #eee;">
                        <input type="submit" name="btn_agregar" value="Guardar Habitación">
                    </td>
                </tr>
            </table>
        </form>
    </main>
</body>
<script src="../js/validaciones.js"></script>
</html>