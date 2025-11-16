<?php
include_once("../config.inc.php");
session_start();
session_unset();
session_destroy();
$curl = "Location: ".$GLOBALS["raiz_sitio"]."index.php";
header($curl);
exit();
?>