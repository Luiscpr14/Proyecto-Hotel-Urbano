<?php
include_once("acceso_bd.php");
function buscarHabitaciones(){
// Procesar búsqueda (acepta tanto GET como POST)
    if (isset($_GET['termino']) || isset($_POST['txt_termino'])) {
        
        $pconexion = abrirConexion();
        seleccionarBaseDatos($pconexion);
        
        // Obtener término de búsqueda (GET desde index.php o POST desde el mismo buscar.php)
        if (isset($_GET['termino'])) {
            $termino_busqueda = mysqli_real_escape_string($pconexion, $_GET['termino']);
        } else {
            $termino_busqueda = mysqli_real_escape_string($pconexion, $_POST['txt_termino']);
        }
        
        //$mostrar_resultados = true;
        
        // Búsqueda en múltiples campos
        $cquery = "SELECT id_habitacion, codigo, categoria, precio, capacidad, disponibles, descripcion, imagen";
        $cquery .= " FROM habitaciones";
        $cquery .= " WHERE activo = 1";
        $cquery .= " AND (codigo LIKE '%$termino_busqueda%'";
        $cquery .= " OR categoria LIKE '%$termino_busqueda%'";
        $cquery .= " OR descripcion LIKE '%$termino_busqueda%')";
        $cquery .= " ORDER BY categoria";
        
        $lresultado_busqueda = mysqli_query($pconexion, $cquery);
        
        if (!$lresultado_busqueda) {
            die("Error en búsqueda: " . mysqli_error($pconexion));
        }
        
        if (mysqli_num_rows($lresultado_busqueda) > 0){
            while ($habitacion = mysqli_fetch_array($lresultado_busqueda, MYSQLI_ASSOC)) {
                $ccontenido = "<tr>";
                $ccontenido .= "<td align='center'><strong>".htmlspecialchars($habitacion['codigo'])."</strong></td>";
                $ccontenido .= "<td width='10'>&nbsp;</td>";
                $ccontenido .= "<td>". htmlspecialchars($habitacion['categoria'])."</td>";
                $ccontenido .= "<td width='10'>&nbsp;</td>";
                $ccontenido .= "<td><strong>$". number_format($habitacion['precio'], 2)."</strong></td>";
                $ccontenido .= "<td width='10'>&nbsp;</td>";
                $ccontenido .= "<td align='center'>".$habitacion['capacidad']." persona(s)</td>";
                $ccontenido .= "<td width='10'>&nbsp;</td>";
                $ccontenido .= "<td align='center'>".$habitacion['disponibles']."</td>";    
                $ccontenido .= "<td width='10'>&nbsp;</td>";
                $ccontenido .= "<td><img src='imagenes/habitaciones/".htmlspecialchars($habitacion['imagen'])."' width='100' alt='Imagen habitación'></td>";
                $ccontenido .= "<td width='10'>&nbsp;</td>";
                $ccontenido .= "<td>". htmlspecialchars($habitacion['descripcion'])."</td>";
                $ccontenido .= "<td width='10'>&nbsp;</td>";
                $ccontenido .= "<td><button type='button' class='btn-reservar' ";
                $ccontenido .= "onclick=\"Carrito.agregar(".$habitacion['id_habitacion'].", '".$habitacion['codigo']."', ".$habitacion['precio'].", '".$habitacion['categoria']."')\">";
                $ccontenido .= "Agregar al Carrito";
                $ccontenido .= "</button></td>";


                $ccontenido .= "</tr>";
            }
        }
        else {
            $ccontenido = "<tr><td colspan='15' align='center'>No se encontraron habitaciones que coincidan con el término de búsqueda.</td></tr>";
            $ccontenido .= "</td></tr>";
        }

        mysqli_free_result($lresultado_busqueda);
        cerrarConexion($pconexion);
    }
    else {
        $ccontenido = "<div>";
        $ccontenido .= "<h3>Utiliza el buscador de arriba para encontrar habitaciones.</h3>";
        $ccontenido .= "</div>";
    }

    return $ccontenido;
}
?>