<?php
include_once("../config.inc.php");
include_once("acceso_bd.php");
include_once("sesiones.php");
//$curl = '';
$curl = "Location:".$GLOBALS["raiz_sitio"]."login.php";
if (isset($_POST['btn_login']) && $_POST['btn_login'] == 'Iniciar Sesión'){
    $pconexion = abrirConexion();
    seleccionarBaseDatos($pconexion);

    $cusuario = $_POST['txt_usuario'];
    $ccontrasena = $_POST['txt_contrasena'];

    $cquery = "SELECT id_usuario, nombre_usuario, contrasena, tipo_usuario FROM usuarios"; 
    $cquery .= " WHERE (nombre_usuario='$cusuario')";

    $adatos = extraerRegistro($pconexion, $cquery);

    if (!empty($adatos) && $adatos['nombre_usuario'] == $cusuario && $adatos['contrasena'] == $ccontrasena){
	   iniciarSesion($adatos);
	   $curl = "Location:".$GLOBALS["raiz_sitio"]."index.php";
     }
     else {
        $curl = "Location:".$GLOBALS["raiz_sitio"]."login.php?error=1";
     }
     cerrarConexion($pconexion);
}

header($curl);
exit();
?>