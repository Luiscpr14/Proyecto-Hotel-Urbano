<?php
function validarSesion(){
    //La función session_start() carga las variables registradas en el arreglo $_SESSION
    session_start();

    if (!isset($_SESSION["cidusuario"])){
        $cdestino="Location:".$GLOBALS["raiz_sitio"]."login.php";
        header($cdestino);
        exit();
    }
}

function validarAdmin(){
    //La función session_start() carga las variables registradas en el arreglo $_SESSION
    //No es necesario llamar sesion_start, ValidarAdmin siempre se llama después de validarSesion
    if ($_SESSION["ctipo_usuario"] != "admin"){
        $cdestino="Location:".$GLOBALS["raiz_sitio"]."index.php";
        header($cdestino);
        exit();
    }
}

function iniciarSesion($avar){

    session_start();
    $_SESSION["cidusuario"] = $avar["id_usuario"];
    $_SESSION["cnombre_usuario"] = $avar["nombre_usuario"];
    $_SESSION["ctipo_usuario"] = $avar["tipo_usuario"];
}
?>