<?php 
include_once("../funciones/acceso_bd.php");
include_once("../config.inc.php");
include_once("../funciones/sesiones.php");
include_once("../funciones/editar.php");
//Página solo accesible para administradores
validarSesion();
validarAdmin();
$adatos = recuperarInfoHabitacion($_GET['id_habitacion']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edición</title>
</head>
<body>
    <header>
        <h1>Editar habitación</h1>
    </header>
    <nav>
        <a href="gestionar_habitaciones.php">Volver a gestión</a>
        <a href="../index.php">Inicio</a>
        <a href="../funciones/logout.php" onclick="return confirm('¿Estás seguro de que deseas cerrar sesión?');">Cerrar Sesión</a>
    </nav>
    <main>
        <h2>Editar habitaciones código: <?php echo $adatos['codigo']; ?></h2>

        <form action="editar_habitacion.php" method="POST" enctype="multipart/form-data">
        <!-- Campo oculto con el ID -->
            <input type="hidden" name="hdn_id" value="<?php echo $adatos['id_habitacion']; ?>">
            
            <table>
                <tr>
                    <td><label for="codigo">Código de habitaciones:</label></td>
                    <td><input type="text" id="codigo" name="txt_codigo" value="<?php echo ($adatos['codigo']); ?>" required></td>
                </tr>
                <tr>
                    <td><label for="categoria">Categoría:</label></td>
                    <td>
                        <select id="categoria" name="txt_categoria" required>
                            <option value="">Seleccione una categoría</option>
                            <option value="Sencilla" <?php echo ($adatos['categoria'] == 'Sencilla') ? 'selected' : ''; ?>>Sencilla</option>
                            <option value="Doble" <?php echo ($adatos['categoria'] == 'Doble') ? 'selected' : ''; ?>>Doble</option>
                            <option value="Suite" <?php echo ($adatos['categoria'] == 'Suite') ? 'selected' : ''; ?>>Suite</option>
                            <option value="Ejecutiva" <?php echo ($adatos['categoria'] == 'Ejecutiva') ? 'selected' : ''; ?>>Suite Ejecutiva</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label for="precio">Precio por noche (MXN):</label></td>
                    <td><input type="number" id="precio" name="txt_precio" step="0.01" min="0" value="<?php echo $adatos['precio']; ?>" required></td>
                </tr>
                <tr>
                    <td><label for="capacidad">Capacidad (personas):</label></td>
                    <td><input type="number" id="capacidad" name="txt_capacidad" min="1" value="<?php echo $adatos['capacidad']; ?>" required></td>
                </tr>
                <tr>
                    <td><label for="disponibles">Cuartos disponibles:</label></td>
                    <td>
                        <input type="number" id="disponibles" name="txt_disponibles" min="0" value="<?php echo $adatos['disponibles']; ?>" required>
                    </td>
                </tr>
                <tr>
                    <td><label for="descripcion">Descripción:</label></td>
                    <td><textarea id="descripcion" name="txt_descripcion" rows="4" cols="40" required><?php echo ($adatos['descripcion']); ?></textarea></td>
                </tr>
                <tr>
                    <td><label>Imagen actual:</label></td>
                    <td>
                        <?php if (!empty($adatos['imagen'])): ?>
                            <img src="../imagenes/habitaciones/<?php echo ($adatos['imagen']); ?>" width="200" alt="Imagen actual">
                            <br><small>Deja el campo vacío para mantener esta imagen</small>
                        <?php else: ?>
                            <small>Sin imagen</small>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <td><label for="imagen">Nueva imagen (opcional):</label></td>
                    <td><input type="file" id="imagen" name="fl_imagen" accept="image/*"></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <hr>
                        <input type="submit" name="btn_editar" value="Guardar Cambios">
                    </td>
                </tr>
            </table>
        </form>
    </main>
</body>
</html>