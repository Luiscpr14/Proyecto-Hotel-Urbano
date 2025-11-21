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
    <title>Editar Habitaci&oacute;n - Hotel Urbano</title>
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
        <a href="../funciones/logout.php" onclick="return confirm('¿Cerrar sesión?');">Cerrar Sesi&oacute;n<i class="fas fa-sign-out-alt"></i></a>
    </nav>
    <main>
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
            <h2>Editar Habitación: <?php echo isset($adatos['codigo']) ? $adatos['codigo'] : ''; ?></h2>
            <!-- Botón Volver en el cuerpo -->
            <a href="gestionar_habitaciones.php" class="btn-accion btn-volver"><i class="fa fa-arrow-left"></i> Volver al listado</a>
        </div>

        <form id="editar_form" action="editar_habitacion.php" method="POST" enctype="multipart/form-data">
            <!-- Campo oculto con el ID -->
            <input type="hidden" name="hdn_id" value="<?php echo $adatos['id_habitacion']; ?>">
            
            <table>
                <tr>
                    <td><label for="codigo">C&oacute;digo:</label></td>
                    <td><input type="text" id="codigo" name="txt_codigo" value="<?php echo htmlspecialchars($adatos['codigo']); ?>" required placeholder="Ej: SVJR5" maxlength="5"></td>
                </tr>
                <tr>
                    <td><label for="categoria">Categor&iacute;a:</label></td>
                    <td>
                        <select id="categoria" name="txt_categoria" required>
                            <option value="">Seleccione una categor&iacute;a</option>
                            <option value="Sencilla" <?php echo ($adatos['categoria'] == 'Sencilla') ? 'selected' : ''; ?>>Sencilla</option>
                            <option value="Doble" <?php echo ($adatos['categoria'] == 'Doble') ? 'selected' : ''; ?>>Doble</option>
                            <option value="Suite" <?php echo ($adatos['categoria'] == 'Suite') ? 'selected' : ''; ?>>Suite</option>
                            <option value="Ejecutiva" <?php echo ($adatos['categoria'] == 'Ejecutiva') ? 'selected' : ''; ?>>Ejecutiva</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label for="precio">Precio (MXN):</label></td>
                    <td><input type="number" id="precio" name="txt_precio" step="0.01" min="0" value="<?php echo $adatos['precio']; ?>" required></td>
                </tr>
                <tr>
                    <td><label for="capacidad">Capacidad:</label></td>
                    <td><input type="number" id="capacidad" name="txt_capacidad" min="1" value="<?php echo $adatos['capacidad']; ?>" required></td>
                </tr>
                <tr>
                    <td><label for="disponibles">Disponibles:</label></td>
                    <td>
                        <input type="number" id="disponibles" name="txt_disponibles" min="0" value="<?php echo $adatos['disponibles']; ?>" required>
                    </td>
                </tr>
                <tr>
                    <td><label for="descripcion">Descripci&oacute;n:</label></td>
                    <td><textarea id="descripcion" name="txt_descripcion" rows="4" required><?php echo htmlspecialchars($adatos['descripcion']); ?></textarea></td>
                </tr>
                <tr>
                    <td><label>Imagen actual:</label></td>
                    <td style="padding: 15px 0;">
                        <?php if (!empty($adatos['imagen'])): ?>
                            <div style="border:1px solid #ddd; padding:5px; display:inline-block; border-radius:4px;">
                                <img src="../imagenes/habitaciones/<?php echo ($adatos['imagen']); ?>" width="200" style="display:block;" alt="Imagen actual">
                            </div>
                            <br><small style="color:#666;">Deja el campo de archivo vac&iacute;o para conservar esta imagen.</small>
                        <?php else: ?>
                            <small>No hay imagen asignada actualmente.</small>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <td><label for="imagen">Cambiar imagen:</label></td>
                    <td><input type="file" id="imagen" name="fl_imagen" accept="image/*"></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <hr style="margin: 20px 0; border: 0; border-top: 1px solid #eee;">
                        <input type="submit" name="btn_editar" value="Guardar Cambios">
                    </td>
                </tr>
            </table>
        </form>
    </main>
</body>
<script src="../js/validaciones.js"></script>
</html>