<?php
include_once("../config.inc.php");
include_once("../funciones/sesiones.php");
include_once("../funciones/acceso_bd.php");

//Escuchar el envío del formulario de edición
if (isset($_POST["btn_editar"]) && $_POST["btn_editar"] == "Guardar Cambios"){
    
    $pconexion = abrirConexion();
    seleccionarBaseDatos($pconexion);

    $cid_habitacion = $_POST["hdn_id"];
    $ccodigo = $_POST["txt_codigo"];
    $ccategoria = $_POST["txt_categoria"];
    $nprecio = $_POST["txt_precio"];
    $ncapacidad = $_POST["txt_capacidad"];
    $ndisponibles = $_POST["txt_disponibles"];
    $cdescripcion = $_POST["txt_descripcion"];

    // Verificar si se subió una nueva imagen
    if(is_uploaded_file($_FILES["fl_imagen"]["tmp_name"])) {
        // Obtener la imagen antigua para eliminarla
        $cquery_img = "SELECT imagen FROM habitaciones WHERE id_habitacion = $cid_habitacion";
        $adatos_img = extraerRegistro($pconexion, $cquery_img);
    
        // Eliminar la imagen antigua del servidor
        if (!empty($adatos_img['imagen']) && $adatos_img['imagen'] != 'sin imagen asociada') {
            $ruta_antigua = "../imagenes/habitaciones/" . $adatos_img['imagen'];
            if (file_exists($ruta_antigua)) {
                unlink($ruta_antigua);
            }
        }
        // Procesar la nueva imagen
        $cnombre_imagen = $_FILES["fl_imagen"]["name"];
        
        // Mover la imagen a la carpeta de destino
        $carpeta_destino = "../imagenes/habitaciones/";
        $extension = pathinfo($cnombre_imagen, PATHINFO_EXTENSION);
        $cnombre_imagen = "hab_" . $ccodigo . "_" . time() . "." . $extension;
        move_uploaded_file($_FILES["fl_imagen"]["tmp_name"], $carpeta_destino . $cnombre_imagen);

        $cquery = "UPDATE habitaciones";
        $cquery .= " SET codigo = '$ccodigo',";
        $cquery .= " categoria = '$ccategoria',";
        $cquery .= " precio = $nprecio,";
        $cquery .= " capacidad = $ncapacidad,";
        $cquery .= " disponibles = $ndisponibles,";
        $cquery .= " descripcion = '$cdescripcion',";
        $cquery .= " imagen = '$cnombre_imagen'";
        $cquery .= " WHERE id_habitacion = $cid_habitacion";
    }
    else{
        // No se subió imagen nueva
        $cquery = "UPDATE habitaciones";
        $cquery .= " SET codigo = '$ccodigo',";
        $cquery .= " categoria = '$ccategoria',";
        $cquery .= " precio = $nprecio,";
        $cquery .= " capacidad = $ncapacidad,";
        $cquery .= " disponibles = $ndisponibles,";
        $cquery .= " descripcion = '$cdescripcion'";
        $cquery .= " WHERE id_habitacion = $cid_habitacion";
    }

    if (editarDatos($pconexion, $cquery)) {
        $curl = "Location:".$GLOBALS["raiz_sitio"]."admin/gestionar_habitaciones.php";
    }
    else{
        $curl = "Location:".$GLOBALS["raiz_sitio"]."admin/editar_habitacion.php?id=$cid_habitacion";
    }
    //Seria bueno mover
    cerrarConexion($pconexion);
    header($curl);
    exit();

}

