<?php
if (empty($_GET['code'])) {
    header("Location: index.php");
    exit();
}
header('Content-Type: application/json');
include "svliga.php";
$comando="DELETE FROM marcas WHERE cod_marca='".$_GET['code']."'";
$resultado=mysqli_query($liga,$comando);
mysqli_free_result($resultado);
mysqli_close($liga);
?>