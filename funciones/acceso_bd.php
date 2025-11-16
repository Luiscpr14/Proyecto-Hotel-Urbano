<?php
function abrirConexion() {
    //funcion para abrir la conexion a la base de datos
    $pconexion = mysqli_connect($GLOBALS["servidor"], $GLOBALS["usuario"], $GLOBALS["contrasena"]) or die(mysqli_connect_error());
    return $pconexion;
}
//--------------------------------------------------------------
function seleccionarBaseDatos($pconexion) {
    //funcion para seleccionar la base de datos
    mysqli_select_db($pconexion, $GLOBALS["base_datos"]) or die(mysqli_connect_error($pconexion));
}
//--------------------------------------------------------------
function cerrarConexion($pconexion) {
    //funcion para cerrar la conexion a la base de datos
    mysqli_close($pconexion);
}
//--------------------------------------------------------------
function extraerRegistro($pconector, $cquery){

    /*Lee información solicitada (a través de una sentencia SQL) de la base de datos y la almacena
    en un arreglo que devuelve como parámetro de salida.
    Advertencia: utilizar esta función únicamente cuando se espere un sólo registro como resultado */

    $aregistro = array();
    $lresult = mysqli_query($pconector, $cquery);
    if (!$lresult){
        $cerror = "No fue posible recuperar la información de la base de datos. <br>";
        $cerror .= "SQL: $cquery <br>";
        $cerror .= "Descripción:".mysqli_connect_error($pconector);
        die($cerror);
    }
    else{
        if(mysqli_num_rows($lresult) > 0){
            $aregistro = mysqli_fetch_assoc($lresult);
        }
    }

    mysqli_free_result($lresult);
    reset($aregistro);

    return $aregistro;
}
//--------------------------------------------------------------
function existeRegistro($pconector, $cquery){

    //Verifica la existencia de la información solicitada (a través de una sentencia SQL) en la base de datos
    $lexiste_referencia = true;
    $lresult = mysqli_query($pconector, $cquery);

    if (!$lresult){
        $cerror = "No fue posible recuperar la información de la base de datos.<br>";
        $cerror .= "SQL: $cquery <br>";
        $cerror .= mysqli_connect_error($pconector);
        die($cerror);
    }
    else {
        //Verifica que no existe un registro igual al que se va a insertar
        if (mysqli_num_rows($lresult) == 0){
            $lexiste_referencia = false;
        }
    }

    //Libera la memoria asociada al resultado de la consulta
    mysqli_free_result($lresult);

    return $lexiste_referencia;
}
//--------------------------------------------------------------
function insertarDatos($pconector, $cquery){

    //Inserta un registro en la base de datos
    $lentrada_creada = false;
    $lresult = mysqli_query($pconector, $cquery);
    if (!$lresult){
        $cerror = "Ocurrió un error al acceder a la base de datos. <br>";
        $cerror .= "SQL: $cquery <br>";
        $cerror .= "Descripción: ".mysqli_connect_error();
        die($cerror);
    }
    else{
        if (mysqli_affected_rows($pconector) > 0){
            $lentrada_creada = true;
        }
    }
    
    return $lentrada_creada;

}
//--------------------------------------------------------------
function editarDatos($pconector, $cquery){
    //Modifica. edita o actualiza uno o más registros de la base de datos
    $ledicion_completada = false;
    $lresult = mysqli_query($pconector,$cquery);
    if (!$lresult){
        $cerror = "Ocurrió un error al acceder a la base de datos. <br>";
        $cerror .= "SQL: $cquery <br>";
        $cerror .= "Descripción: ".mysqli_connect_error($pconector);
        die($cerror);
    }
    else{
        $ledicion_completada = true;
    }
    return $ledicion_completada;
}
//--------------------------------------------------------------
function borrarDatos($pconector,$cquery){
    //Elimina uno o más registros de la base de datos
    $laccion_completada = false;
    $lresult = mysqli_query($pconector,$cquery);
     if (!$lresult){
        $cerror = "Ocurrió un error al acceder a la base de datos. <br>";
        $cerror .= "SQL: $cquery <br>";
        $cerror .= "Descripción: ".mysqli_connect_error($pconector);
        die($cerror);
    }
    else{
        $laccion_completada = true;
    }
    return $laccion_completada;
}
//--------------------------------------------------------------
function recuperarInfoHabitacion($cid_habitacion){

    $adatos = array();

    $pconexion = abrirConexion();
    seleccionarBaseDatos($pconexion);

    $cquery = "SELECT id_habitacion, codigo, categoria, descripcion, imagen, precio, capacidad, disponibles FROM habitaciones";
    $cquery .= " WHERE id_habitacion = $cid_habitacion";

    $adatos = extraerRegistro($pconexion, $cquery);
    cerrarConexion($pconexion);

    return $adatos;
}
?>