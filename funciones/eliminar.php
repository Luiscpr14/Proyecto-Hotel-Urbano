<?php
include_once("../config.inc.php");
include_once("../funciones/sesiones.php");
include_once("../funciones/acceso_bd.php");
validarSesion();
validarAdmin();

$curl = "Location: ".$GLOBALS["raiz_sitio"]."admin/gestionar_habitaciones.php";

if (isset($_GET["id_habitacion"])) {

    $pconexion = abrirConexion();
    seleccionarBaseDatos($pconexion);
    $cid_habitacion = $_GET["id_habitacion"];
    
    //Se obtiene el nombre de la imagen asociada a la habitación
    $cquery = "SELECT imagen FROM habitaciones WHERE id_habitacion = $cid_habitacion";
    $adatos = extraerRegistro($pconexion, $cquery);
    
    // Si tiene imagen asociada, eliminarla del servidor
    if (!empty($adatos) && !empty($adatos['imagen'])) {
        $ruta_imagen = "../imagenes/habitaciones/" . $adatos['imagen'];
        if (file_exists($ruta_imagen)) {
            unlink($ruta_imagen);
            //Unlink elimina el archivo de la carpeta
        }
    }

    //Eliminar el registro de la base de datos
    $cquery = "DELETE FROM habitaciones";
    $cquery .= " WHERE id_habitacion = $cid_habitacion";
    borrarDatos($pconexion, $cquery);
    cerrarConexion($pconexion);
}

header($curl);
exit();
?>