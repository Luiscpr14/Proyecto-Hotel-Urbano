<?php
include_once("acceso_bd.php");
function listarHabitaciones(){

    $ccontenido="";
    //Conexión con el servidor de base de datos
    $pconexion = abrirConexion();
    //Selección de la base de datos
    seleccionarBaseDatos($pconexion);
    //Construcción de la sentencia SQL
    $cquery = "SELECT id_habitacion, numero, categoria, precio, capacidad, disponibles, descripcion, imagen";
    $cquery .= " FROM habitaciones";
    $cquery .= " WHERE activo = 1";
    $cquery .= " ORDER BY categoria, precio";

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
                $ccontenido .= "<td align=\"center\">".$adatos["numero"]."</td>";
                $ccontenido .= "<td width=\"10\">&nbsp;</td>";
                $ccontenido .= "<td>".$adatos["categoria"]."</td>";
                $ccontenido .= "<td width=\"10\">&nbsp;</td>";
                $ccontenido .= "<td>\$".number_format($adatos["precio"], 2)."</td>";
                $ccontenido .= "<td width=\"10\">&nbsp;</td>";
                $ccontenido .= "<td align=\"center\">".$adatos["capacidad"]."</td>";
                $ccontenido .= "<td width=\"10\">&nbsp;</td>";
                $ccontenido .= "<td align=\"center\">".$adatos["disponibles"]."</td>";
                $ccontenido .= "<td width=\"10\">&nbsp;</td>";
                $ccontenido .= "<td><img src=\"imagenes/".$adatos["imagen"]."\" alt=\"Imagen de la habitación\" width=\"100\"></td>";
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

// Función específica para el panel de administración (con enlaces de edición y eliminación)
function listarHabitacionesAdmin(){
    $ccontenido = "";
    
    $pconexion = abrirConexion();
    seleccionarBaseDatos($pconexion);
    
    $cquery = "SELECT id_habitacion, numero, categoria, precio, capacidad, disponibles, descripcion, imagen";
    $cquery .= " FROM habitaciones";
    $cquery .= " WHERE activo = 1";
    $cquery .= " ORDER BY categoria, precio";
    
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
                $ccontenido .= "<td align=\"center\">".$adatos["numero"]."</td>";
                $ccontenido .= "<td width=\"10\">&nbsp;</td>";
                $ccontenido .= "<td>".$adatos["categoria"]."</a></td>";
                $ccontenido .= "<td width=\"10\">&nbsp;</td>";
                $ccontenido .= "<td>\$".number_format($adatos["precio"], 2)."</td>";
                $ccontenido .= "<td width=\"10\">&nbsp;</td>";
                $ccontenido .= "<td align=\"center\">".$adatos["capacidad"]."</td>";
                $ccontenido .= "<td width=\"10\">&nbsp;</td>";
                $ccontenido .= "<td align=\"center\">".$adatos["disponibles"]."</td>";
                $ccontenido .= "<td width=\"10\">&nbsp;</td>";
                $ccontenido .= "<td><img src=\"../imagenes/".$adatos["imagen"]."\" alt=\"Imagen de la habitación\" width=\"100\"></td>";
                $ccontenido .= "<td width=\"10\">&nbsp;</td>";
                $ccontenido .= "<td>".$adatos["descripcion"]."</td>";
                $ccontenido .= "<td width=\"10\">&nbsp;</td>";
                $ccontenido .= "<td><a href=\"../funciones/eliminar_habitacion.php?id=$cid_habitacion\">";
                $ccontenido .= "<img src=\"../imagenes/borrar.gif\" border=\"0\" alt=\"Eliminar\"></a>&nbsp;";
                $ccontenido .= "<a href=\"editar_habitacion.php?id=$cid_habitacion\">";
                $ccontenido .= "<img src=\"../imagenes/editar.gif\" border=\"0\" alt=\"Editar\"></a></td>";
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