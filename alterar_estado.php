<?php
if (empty($_GET['code_estado'])) {
    header("Location: index.php");
    exit();
}
header('Content-Type: application/json');
include "svliga.php";
$comando="UPDATE utilizadores SET estado='".$_GET['code_estado']."' WHERE email='".$_GET['code_email']."'";
$resultado=mysqli_query($liga,$comando);
mysqli_free_result($resultado);
mysqli_close($liga);
?>