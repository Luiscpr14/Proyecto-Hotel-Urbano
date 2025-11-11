<?php
include_once("../config.inc.php");
include_once("../funciones/sesiones.php");
include_once("../funciones/acceso_bd.php");
//Página solo accesible para administradores
validarSesion();
validarAdmin();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear</title>
</head>
<body>
    <h1>Agregar Nueva Habitación</h1>
    
    <nav>
        <a href="gestionar_habitaciones.php">Volver a gestión</a>
        <a href="../index.php">Inicio</a>
        <a href="../funciones/logout.php">Cerrar Sesión</a>
    </nav>
    
    <hr>
    <main>
        <form action="../funciones/crear_habitacion.php" method="POST" enctype="multipart/form-data">
            <table>
                <tr>
                    <td><label for="numero">Número de habitación:</label></td>
                    <td><input type="text" id="numero" name="txt_numero" required></td>
                </tr>
                <tr>
                    <td><label for="categoria">Categoría:</label></td>
                    <td>
                        <select id="categoria" name="txt_categoria" required>
                            <option value="">-- Seleccionar uno --</option>
                            <option value="sencilla">Sencilla</option>
                            <option value="doble">Doble</option>
                            <option value="suite">Suite</option>
                            <option value="ejecutiva">Ejecutiva</option>
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
                    <td><label for="disponibles">Habitaciones disponibles:</label></td>
                    <td><input type="number" id="disponibles" name="txt_disponibles" min="0" required></td>
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
                        <input type="button" value="Cancelar" onclick="window.location.href='gestionar_habitaciones.php'">
                    </td>
                </tr>
            </table>
        </form>
    </main>
</body>
</html>