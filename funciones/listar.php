<?php
include_once("acceso_bd.php");
//Listado general de habitaciones(en desuso, se está usando listarPorCategoria en index.php)
/*function listarHabitaciones(){

    $ccontenido="";
    //Conexión con el servidor de base de datos
    $pconexion = abrirConexion();
    //Selección de la base de datos
    seleccionarBaseDatos($pconexion);
    //Construcción de la sentencia SQL
    $cquery = "SELECT id_habitacion, codigo, categoria, precio, capacidad, disponibles, descripcion, imagen";
    $cquery .= " FROM habitaciones";
    $cquery .= " WHERE activo = 1";
    $cquery .= " ORDER BY categoria";

    //Se ejecuta la sentencia SQL
    $lresult = mysqli_query($pconexion, $cquery);

    if (!$lresult){
        $cerror = "No fue posible recuperar la información de la base de datos.<br>";
        $cerror .= "SQL: $cquery <br>";
        $cerror .= "Descripcion: ".mysqli_connect_error($pconexion);
        die($cerror);
    }
    else{
        //Verifica que la consulta haya devuelto por lo menos un registro
        if(mysqli_num_rows($lresult) > 0){
            //Recorre los registros arrojados por la consulta SQL
            while ($adatos = mysqli_fetch_array($lresult, MYSQLI_ASSOC)){
                $cid_habitacion = $adatos["id_habitacion"];
                $ccontenido .= "<tr>";
                $ccontenido .= "<td align=\"center\">".$adatos["codigo"]."</td>";
                $ccontenido .= "<td width=\"10\">&nbsp;</td>";
                $ccontenido .= "<td>".$adatos["categoria"]."</td>";
                $ccontenido .= "<td width=\"10\">&nbsp;</td>";
                $ccontenido .= "<td>\$".number_format($adatos["precio"], 2)."</td>";
                $ccontenido .= "<td width=\"10\">&nbsp;</td>";
                $ccontenido .= "<td align=\"center\">".$adatos["capacidad"]."</td>";
                $ccontenido .= "<td width=\"10\">&nbsp;</td>";
                $ccontenido .= "<td align=\"center\">".$adatos["disponibles"]."</td>";
                $ccontenido .= "<td width=\"10\">&nbsp;</td>";
                $ccontenido .= "<td><img src=\"imagenes/habitaciones/".$adatos["imagen"]."\" alt=\"Imagen de la habitación\" width=\"100\"></td>";
                $ccontenido .= "<td width=\"10\">&nbsp;</td>";
                $ccontenido .= "<td>".$adatos["descripcion"]."</td>";
                $ccontenido .= "<td width=\"10\">&nbsp;</td>";

                if (isset($_SESSION["cidusuario"])){
                    $ccontenido .= "<td><a href=\"reservar_habitacion.php?id_habitacion=".$cid_habitacion."\">";
                    $ccontenido .= "<button>Reservar</button></a></td>";
                }

                $ccontenido .= "</tr>";
            }
        }
        else{
            $ccontenido = "<tr><td colspan=\"15\" align=\"center\">No hay habitaciones registradas.</td></tr>";
        }
    }
    // Liberar resultado
    mysqli_free_result($lresult);
    // Cerrar conexión
    cerrarConexion($pconexion);

    return $ccontenido;
}
*/
//Listado de habitaciones por categoría
function listarPorCategoria() {
    $ccontenido = "";

    $pconexion = abrirConexion();
    seleccionarBaseDatos($pconexion);

    // Obtener todas las categorías con orden específico
    $cquery_categoria = "SELECT DISTINCT categoria FROM habitaciones ORDER BY CASE categoria ";
    $cquery_categoria .= "WHEN 'Sencilla' THEN 1 ";
    $cquery_categoria .= "WHEN 'Doble' THEN 2 ";
    $cquery_categoria .= "WHEN 'Suite' THEN 3 ";
    $cquery_categoria .= "WHEN 'Ejecutiva' THEN 4 ";
    $cquery_categoria .= "ELSE 5 END";
    
    $cresultado_categorias = mysqli_query($pconexion, $cquery_categoria);

    if(!$cresultado_categorias) {
        $cerror = "No fue posible recuperar las categorías. <br>";
        $cerror .= "Descripci&oacute;n: ".mysqli_error($pconexion);
        die($cerror);
    }

    // Nos vamos imprimiendo habitaciones categoría por categoría
    while ($ccategoria = mysqli_fetch_array($cresultado_categorias, MYSQLI_ASSOC)) {
        $cnombre_categoria = mysqli_real_escape_string($pconexion, $ccategoria['categoria']);

        // Obtener las habitaciones pertenecientes a la categoría
        $cquery = "SELECT id_habitacion, codigo, precio, capacidad, disponibles, descripcion, imagen";
        $cquery .= " FROM habitaciones WHERE activo = 1 AND categoria = '$cnombre_categoria'";

        $lresultado = mysqli_query($pconexion, $cquery);
        if(!$lresultado) {
            $cerror  = "No fue posible recuperar las habitaciones. <br>";
            $cerror .= "Descripci&oacute;n: ".mysqli_error($pconexion);
            die($cerror);
        }

        // Listar habitaciones de la categoría
        if(mysqli_num_rows($lresultado) > 0) {
            // Contenedor de categoría
            $ccontenido .= '<section class="categoria-section">';
            $ccontenido .= '<h3 class="categoria-titulo">' . htmlspecialchars($cnombre_categoria) . 's</h3>';
            $ccontenido .= '<div class="carrusel-wrapper">';
            $ccontenido .= '<button class="carrusel-btn btn-prev">&#10094;</button>';
            $ccontenido .= '<div class="carrusel-track-container">';
            $ccontenido .= '<div class="carrusel-track">';

            // Renderizar tarjetas
            while ($adatos = mysqli_fetch_array($lresultado, MYSQLI_ASSOC)) {
                $id = $adatos['id_habitacion'];
                $codigo = $adatos['codigo'];
                $precio = number_format($adatos['precio'], 2);
                $capacidad = $adatos['capacidad'];
                $descripcion = $adatos['descripcion'];
                $imagen = $adatos['imagen'];

                // Validación de imagen
                $ruta_imagen = "imagenes/habitaciones/" . $imagen;
                if (empty($imagen)) {
                    $ruta_imagen = "imagenes/habitaciones/sin_imagen.jpg";
                }

                // RENDERIZADO DE LA TARJETAxd
                $ccontenido .= '<div class="habitacion-card">';
                // Imagen
                $ccontenido .= '<div class="card-imagen">';
                $ccontenido .= '<img src="' . htmlspecialchars($ruta_imagen) . '" alt="Habitación ' . htmlspecialchars($codigo) . '" onerror="this.src=\'imagenes/habitaciones/sin_imagen.jpg\'">';
                $ccontenido .= '</div>';
                // Información
                $ccontenido .= '<div class="card-info">';
                $ccontenido .= '<div>';
                $ccontenido .= '<h4>' . htmlspecialchars($cnombre_categoria) . ' - ' . htmlspecialchars($codigo) . '</h4>';
                $ccontenido .= '<div class="card-precio">$' . $precio . '</div>';
                $ccontenido .= '<small>Capacidad: ' . htmlspecialchars($capacidad) . ' personas</small>';
                // Descripción oculta
                $ccontenido .= '<div class="card-desc" id="desc-' . $id . '">';
                $ccontenido .= '<p>' . htmlspecialchars($descripcion) . '</p>';
                $ccontenido .= '</div>';
                $ccontenido .= '</div>';
                // Botones
                $ccontenido .= '<div class="card-actions">';
                // Botón Ver Detalles
                $ccontenido .= '<button type="button" class="btn-detalles" onclick="abrirModalDetalles(' . $id . ', \'' . htmlspecialchars($codigo) . '\', \'' . htmlspecialchars($cnombre_categoria) . '\', ' . $adatos['precio'] . ', ' . $capacidad . ', \'' . $descripcion . '\', \'' . htmlspecialchars($ruta_imagen) . '\')">';
                $ccontenido .= 'Ver Detalles';
                $ccontenido .= '</button>';
                // Botón Agregar al Carrito
                $ccontenido .= '<button type="button" class="btn-carrito" ';
                $ccontenido .= 'onclick="Carrito.agregar(' . $id . ', \'' . htmlspecialchars($codigo) . '\', ' . $adatos['precio'] . ', \'' . htmlspecialchars($cnombre_categoria) . '\')">';
                $ccontenido .= 'Agregar';
                $ccontenido .= '</button>';
                $ccontenido .= '</div>';
                $ccontenido .= '</div>';
                $ccontenido .= '</div>';
            }

            $ccontenido .= '</div>';
            $ccontenido .= '</div>';
            $ccontenido .= '<button class="carrusel-btn btn-next">&#10095;</button>';
            $ccontenido .= '</div>';
            $ccontenido .= '</section>';

            mysqli_free_result($lresultado);
        }
    }

    mysqli_free_result($cresultado_categorias);
    cerrarConexion($pconexion);

    return $ccontenido;
}

// Función específica para el panel de administración (con enlaces de edición y eliminación)
function listarHabitacionesAdmin(){
    $ccontenido = "";
    
    $pconexion = abrirConexion();
    seleccionarBaseDatos($pconexion);
    
    $cquery = "SELECT id_habitacion, codigo, categoria, precio, capacidad, disponibles, descripcion, imagen";
    $cquery .= " FROM habitaciones";
    $cquery .= " WHERE activo = 1";
    //$cquery .= " ORDER BY codigo";
    
    $lresult = mysqli_query($pconexion, $cquery);
    
    if (!$lresult){
        $cerror = "No fue posible recuperar la información de la base de datos.<br>";
        $cerror .= "SQL: $cquery <br>";
        $cerror .= "Descripción: ".mysqli_error($pconexion);
        die($cerror);
    }
    else{
        if(mysqli_num_rows($lresult) > 0){
            while ($adatos = mysqli_fetch_array($lresult, MYSQLI_ASSOC)){
                $cid_habitacion = $adatos["id_habitacion"];
                $ccontenido .= "<tr>";
                $ccontenido .= "<td align=\"center\">".$adatos["codigo"]."</td>";
                $ccontenido .= "<td width=\"10\">&nbsp;</td>";
                $ccontenido .= "<td>".$adatos["categoria"]."</a></td>";
                $ccontenido .= "<td width=\"10\">&nbsp;</td>";
                $ccontenido .= "<td>\$".number_format($adatos["precio"], 2)."</td>";
                $ccontenido .= "<td width=\"10\">&nbsp;</td>";
                $ccontenido .= "<td align=\"center\">".$adatos["capacidad"]."</td>";
                $ccontenido .= "<td width=\"10\">&nbsp;</td>";
                $ccontenido .= "<td align=\"center\">".$adatos["disponibles"]."</td>";
                $ccontenido .= "<td width=\"10\">&nbsp;</td>";
                $ccontenido .= "<td><img src=\"../imagenes/habitaciones/".$adatos["imagen"]."\" alt=\"Imagen de la habitación\" width=\"100\"></td>";
                $ccontenido .= "<td width=\"10\">&nbsp;</td>";
                $ccontenido .= "<td>".$adatos["descripcion"]."</td>";
                $ccontenido .= "<td width=\"10\">&nbsp;</td>";
                $ccontenido .= "<td><a href=\"../admin/editar_habitacion.php?id_habitacion=$cid_habitacion\">";
                $ccontenido .= "<img src=\"../imagenes/editar.svg\" id=\"editar_icono\" border=\"0\" alt=\"Editar\"></a>";
                $ccontenido .= "<a href=\"../funciones/eliminar.php?id_habitacion=$cid_habitacion\" onclick=\"return confirm('¿Estás seguro de que deseas eliminar esta habitación? Esta acción no se puede deshacer.');\">";
                $ccontenido .= "<img src=\"../imagenes/borrar.svg\" id=\"eliminar_icono\" border=\"0\" alt=\"Eliminar\"></a></td>";
                $ccontenido .= "</tr>";
            }
        }
        else{
            $ccontenido = "<tr><td colspan=\"15\" align=\"center\">No hay habitaciones registradas.</td></tr>";
        }
    }
    
    mysqli_free_result($lresult);
    cerrarConexion($pconexion);
    
    return $ccontenido;
}

?>