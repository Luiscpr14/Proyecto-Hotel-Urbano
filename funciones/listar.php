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

    //Primero obtener todas las categorías con un orden específico
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

    //Para cada categoría, obtener las habitaciones correspondientes
    while ($ccategoria = mysqli_fetch_array($cresultado_categorias, MYSQLI_ASSOC)) {
        $cnombre_categoria = mysqli_real_escape_string($pconexion, $ccategoria['categoria']);

        //Header de la categoria
        $ccontenido .= "<tr class='header-categoria'>";
        $ccontenido .= "<td colspan='15'><h3>".$cnombre_categoria."s</h3></td></tr>";

        //Obtener las habitaciones pertenecientes a la categoría
        $cquery = "SELECT id_habitacion, codigo, precio, capacidad, disponibles, descripcion, imagen";
        $cquery .= " FROM habitaciones WHERE activo = 1 AND categoria = '$cnombre_categoria'";

        $lresultado = mysqli_query($pconexion, $cquery);
        if(!$lresultado) {
            $cerror  = "No fue posible recuperar las habitaciones. <br>";
            $cerror .= "Descripci&oacute;n: ".mysqli_error($pconexion);
            die($cerror);
        }

        //Listar habitaciones de la categoria
        if(mysqli_num_rows($lresultado) > 0) {
            while ($adatos = mysqli_fetch_array($lresultado, MYSQLI_ASSOC)) {
                $cid_habitacion = $adatos['id_habitacion'];

                $ccontenido .= "<tr>";
                $ccontenido .= "<td align='center'>".$adatos['codigo']."</td>";
                $ccontenido .= "<td width='10'>&nbsp;</td>";
                $ccontenido .= "<td>".$cnombre_categoria."</td>";
                $ccontenido .= "<td width='10'>&nbsp;</td>";
                $ccontenido .= "<td>\$".number_format($adatos['precio'], 2)."</td>";
                $ccontenido .= "<td width='10'>&nbsp;</td>";
                $ccontenido .= "<td align='center'>".$adatos['capacidad']."</td>";
                $ccontenido .= "<td width='10'>&nbsp;</td>";
                $ccontenido .= "<td align='center'>".$adatos['disponibles']."</td>";
                $ccontenido .= "<td width='10'>&nbsp;</td>";
                $ccontenido .= "<td><img src='imagenes/habitaciones/".$adatos["imagen"]."' alt=\"Imagen de la habitación\" width=\"100\"></td>";
                $ccontenido .= "<td width='10'>&nbsp;</td>";
                $ccontenido .= "<td>".$adatos['descripcion']."</td>";
                $ccontenido .= "<td width='10'>&nbsp;</td>";
                $ccontenido .= "<td><button type='button' class='btn-reservar' ";
                $ccontenido .= "onclick=\"Carrito.agregar(".$adatos['id_habitacion'].", '".$adatos['numero']."', ".$adatos['precio'].", '".$cnombre_categoria."')\">";
                $ccontenido .= "Agregar al Carrito";
                $ccontenido .= "</button></td>";

                $ccontenido .= "</tr>";
            }
        }
        else {
            $ccontenido .= "<tr><td colspan='15' align='center'>No hay habitaciones disponibles en esta categoría.</td></tr>";
        }

        mysqli_free_result($lresultado);

        //Espacio entre categorias
        $ccontenido .= "<tr><td colspan='15'>&nbsp;</td></tr>";
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
                $ccontenido .= "<a href=\"../funciones/eliminar.php?id_habitacion=$cid_habitacion\">";
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