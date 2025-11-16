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
    <link rel="stylesheet" href="../estilos/generales.css">
</head>
<body>
    <h1>Agregar Nueva Habitación</h1>
    
    <nav>
        <a href="gestionar_habitaciones.php">Volver a gestión</a>
        <a href="../index.php">Inicio</a>
        <a href="../funciones/logout.php" onclick="return confirm('¿Estás seguro de que deseas cerrar sesión?');">Cerrar Sesión</a>
    </nav>
    
    <hr>
    <main>
        <form name="frm_agregar" action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST" enctype="multipart/form-data">
            <p align="center" class="estado"><?php echo agregarHabitacion(); ?></p>
            <table>
                <tr>
                    <td><label for="codigo">Código de habitaciones:</label></td>
                    <td><input type="text" id="codigo" name="txt_codigo" required></td>
                </tr>
                <tr>
                    <td><label for="categoria">Categoría:</label></td>
                    <td>
                        <select id="categoria" name="slct_categoria" required>
                            <option value="">-- Seleccionar uno --</option>
                            <option value="Sencilla">Sencilla</option>
                            <option value="Doble">Doble</option>
                            <option value="Suite">Suite</option>
                            <option value="Ejecutiva">Ejecutiva</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label for="precio">Precio (MXN por noche):</label></td>
                    <td><input type="number" id="precio" name="txt_precio" step="0.01" min="0" required></td>
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
                    <td><label for="descripcion">Descripción:</label></td>
                    <td><textarea id="descripcion" name="txt_descripcion" rows="4" cols="40" required></textarea></td>
                </tr>
                <tr>
                    <td><label for="imagen">Imagen:</label></td>
                    <td><input type="file" id="imagen" name="fl_imagen" accept="image/*"></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <hr>
                        <input type="submit" name="btn_agregar" value="Agregar Habitación">
                    </td>
                </tr>
            </table>
        </form>
    </main>
</body>
</html>