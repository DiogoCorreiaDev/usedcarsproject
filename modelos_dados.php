<?php
if (empty($_GET['code'])) {
    header("Location: index.php");
    exit();
}
header('Content-Type: application/json');
include "svliga.php";
$comando="SELECT * FROM modelos WHERE cod_marca='".$_GET['code']."'";
$resultado=mysqli_query($liga,$comando);
$dados=array();
while ($linha=mysqli_fetch_assoc($resultado)) {
    array_push($dados, array("modelo"=>$linha['modelo'],"cod_modelo"=>$linha['cod_modelo']));
}
echo json_encode($dados);
mysqli_free_result($resultado);
mysqli_close($liga);
?>