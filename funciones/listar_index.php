<?php
include_once("acceso_bd.php");

function listarHabitacionesCards() {
    $ccontenido = "";
    $pconexion = abrirConexion();
    seleccionarBaseDatos($pconexion);

    $cquery = "SELECT id_habitacion, codigo, categoria, precio, capacidad, disponibles, descripcion, imagen";
    $cquery .= " FROM habitaciones";
    $cquery .= " WHERE activo = 1";
    $cquery .= " ORDER BY CASE categoria ";
    $cquery .= "    WHEN 'Sencilla' THEN 1 ";
    $cquery .= "    WHEN 'Doble' THEN 2 ";
    $cquery .= "    WHEN 'Suite' THEN 3 ";
    $cquery .= "    WHEN 'Ejecutiva' THEN 4 ";
    $cquery .= "    ELSE 5 END, "; 
    $cquery .= " codigo ASC";

    $lresult = mysqli_query($pconexion, $cquery);

    if (!$lresult){
        $cerror = "No fue posible recuperar la información de la base de datos.<br>";
        $cerror .= "SQL: $cquery <br>";
        $cerror .= "Descripción: ".mysqli_error($pconexion);
        die($cerror);
    }
    else {
        if(mysqli_num_rows($lresult) > 0){
            
            while ($adatos = mysqli_fetch_array($lresult, MYSQLI_ASSOC)){
                
                $id = $adatos["id_habitacion"];
                $codigo = $adatos["codigo"];
                $categoria = $adatos["categoria"];
                $precio = number_format($adatos["precio"], 2);
                $capacidad = $adatos["capacidad"];
                $descripcion = $adatos["descripcion"];
                $imagen = $adatos["imagen"];

                //Validacion de imagen
                $ruta_imagen = "imagenes/habitaciones/" . $imagen;
                if (empty($imagen)) {
                    //Placeholder si no hay imagen en BD
                    $ruta_imagen = "imagenes/habitaciones/sin_imagen.jpg"; 
                }

                //RENDERIZADO DE LA FICHA
                $ccontenido .= '<div class="habitacion-card">';
                
                //Imagen
                $ccontenido .= '    <div class="card-imagen">';
                $ccontenido .= '        <img src="'.$ruta_imagen.'" alt="Habitación '.$codigo.'" onerror="this.src=\'img/sin_imagen.jpg\'">';
                $ccontenido .= '    </div>';
                
                //Informacion
                $ccontenido .= '    <div class="card-info">';
                $ccontenido .= '        <div>';
                $ccontenido .= '            <h4>'.$categoria.' - '.$codigo.'</h4>';
                $ccontenido .= '            <div class="card-precio">$'.$precio.'</div>';
                $ccontenido .= '            <small>Capacidad: '.$capacidad.' personas</small>';
                
                //Descripcion oculta
                $ccontenido .= '            <div class="card-desc" id="desc-'.$id.'">';
                $ccontenido .= '                <p>'.$descripcion.'</p>';
                $ccontenido .= '            </div>';
                $ccontenido .= '        </div>';
                
                //Botones
                $ccontenido .= '        <div class="card-actions">';
                    
                //Boton Ver Detalles
                $ccontenido .= '            <button type="button" class="btn-detalles" onclick="toggleDetalles('.$id.')">';
                $ccontenido .= '                Ver Detalles';
                $ccontenido .= '            </button>';
                    
                //Boton Agregar al Carrito
                $ccontenido .= '            <button type="button" class="btn-carrito" ';
                $ccontenido .= 'onclick="Carrito.agregar('.$id.', \''.$codigo.'\', '.$adatos["precio"].', \''.$categoria.'\')">';
                $ccontenido .= '                Agregar';
                $ccontenido .= '            </button>';
                
                $ccontenido .= '        </div>'; //Fin card-actions
                $ccontenido .= '    </div>'; //Fin card-info
                $ccontenido .= '</div>'; //Fin habitacion-card
            }
        }
        else {
            $ccontenido = '<div style="padding:20px; text-align:center; width:100%;">No hay habitaciones registradas o activas.</div>';
        }
    }

    mysqli_free_result($lresult);
    cerrarConexion($pconexion);
    return $ccontenido;
}
?>