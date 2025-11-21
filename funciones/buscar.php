<?php
include_once("acceso_bd.php");

function buscarHabitaciones(){
    // Procesar búsqueda (acepta tanto GET como POST)
    if (isset($_GET['termino']) || isset($_POST['txt_termino'])) {
        
        $pconexion = abrirConexion();
        seleccionarBaseDatos($pconexion);
        
        // Obtener término de búsqueda
        if (isset($_GET['termino'])) {
            $termino_busqueda = mysqli_real_escape_string($pconexion, $_GET['termino']);
        } else {
            $termino_busqueda = mysqli_real_escape_string($pconexion, $_POST['txt_termino']);
        }
        
        // Dividir el término en palabras clave individuales
        $palabras = array_filter(explode(' ', trim($termino_busqueda)));
        
        if (empty($palabras)) {
            cerrarConexion($pconexion);
            return "<div class='alerta-info'>Por favor ingresa un término de búsqueda válido.</div>";
        }
        
        // Construir condición WHERE con todas las palabras
        $condiciones = array();
        foreach ($palabras as $palabra) {
            $palabra_segura = mysqli_real_escape_string($pconexion, $palabra);
            $condiciones[] = "(LOWER(CONCAT(codigo, ' ', categoria, ' ', descripcion)) LIKE LOWER('%$palabra_segura%'))";
        }
        
        $where = implode(' AND ', $condiciones);
        
        // Construir query con puntuación de relevancia
        $cquery = "SELECT id_habitacion, codigo, categoria, precio, capacidad, disponibles, descripcion, imagen,";
        $cquery .= " CASE";
        $cquery .= " WHEN LOWER(codigo) = LOWER('$termino_busqueda') THEN 100";
        $cquery .= " WHEN LOWER(categoria) LIKE LOWER('$termino_busqueda%') THEN 80";
        $cquery .= " WHEN LOWER(descripcion) LIKE LOWER('$termino_busqueda%') THEN 60";
        $cquery .= " ELSE 1 END as relevancia";
        $cquery .= " FROM habitaciones";
        $cquery .= " WHERE activo = 1 AND ($where)";
        $cquery .= " ORDER BY relevancia DESC, categoria, codigo";
        
        $lresultado_busqueda = mysqli_query($pconexion, $cquery);
        
        if (!$lresultado_busqueda) {
            die("Error en búsqueda: " . mysqli_error($pconexion));
        }
        
        $ccontenido = "";
        
        if (mysqli_num_rows($lresultado_busqueda) > 0){
            $ccontenido .= '<div class="lista-resultados">';

            while ($habitacion = mysqli_fetch_array($lresultado_busqueda, MYSQLI_ASSOC)) {
                
                $id = $habitacion['id_habitacion'];
                $codigo = htmlspecialchars($habitacion['codigo']);
                $categoria = htmlspecialchars($habitacion['categoria']);
                $precio = number_format($habitacion['precio'], 2);
                $capacidad = $habitacion['capacidad'];
                $disponibles = $habitacion['disponibles'];
                $desc = htmlspecialchars($habitacion['descripcion']);
                $imagen = $habitacion['imagen'];
                $ruta_imagen = "imagenes/habitaciones/" . $imagen;
                if (empty($imagen)) { $ruta_imagen = "imagenes/habitaciones/sin_imagen.jpg"; }

                $ccontenido .= '<div class="resultado-item">';
                $ccontenido .= '  <div class="resultado-imagen">';
                $ccontenido .= '    <img src="'.$ruta_imagen.'" alt="'.$codigo.'" onerror="this.src=\'imagenes/habitaciones/sin_imagen.jpg\'">';
                $ccontenido .= '  </div>';
                $ccontenido .= '  <div class="resultado-info">';
                $ccontenido .= '    <h3 class="resultado-titulo">'.$categoria.' - '.$codigo.'</h3>';
                $ccontenido .= '    <div class="resultado-meta">';
                $ccontenido .= '      <span class="resultado-precio">$'.$precio.' MXN</span>';
                $ccontenido .= '      <span class="meta-tag"><i class="fa fa-users"></i> '.$capacidad.' pers.</span>';
                $ccontenido .= '      <span class="meta-tag"><i class="fa fa-door-open"></i> '.$disponibles.' disp.</span>';
                $ccontenido .= '    </div>';
                $ccontenido .= '    <p class="resultado-desc">'.$desc.'</p>';
                $ccontenido .= '    <div class="resultado-acciones">';
                $ccontenido .= '      <button type="button" class="btn-resultado btn-agregar" ';
                $ccontenido .= 'onclick="Carrito.agregar('.$id.', \''.$codigo.'\', '.$habitacion['precio'].', \''.$categoria.'\', \''.$ruta_imagen.'\')">';
                $ccontenido .= '        <i class="fa fa-cart-plus"></i> Agregar al Carrito';
                $ccontenido .= '      </button>';
                $ccontenido .= '    </div>';
                $ccontenido .= '  </div>'; //Fin resultado-info
                $ccontenido .= '</div>'; //Fin resultado-item
            }
            $ccontenido .= '</div>'; //Fin lista-resultados
        }
        else {
            $ccontenido = "<div style='text-align:center; padding:40px;'>";
            $ccontenido .= "<h3>No encontramos coincidencias</h3>";
            $ccontenido .= "<p>Intenta con palabras más generales como 'Doble' o 'Suite'.</p>";
            $ccontenido .= "</div>";
        }

        mysqli_free_result($lresultado_busqueda);
        cerrarConexion($pconexion);
    }
    else {
        $ccontenido = "<div class='alerta-info'>Utiliza el buscador para encontrar habitaciones.</div>";
    }

    return $ccontenido;
}
?>