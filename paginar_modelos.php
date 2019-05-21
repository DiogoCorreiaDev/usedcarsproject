<?php
if (empty($_GET['code'])) {
    header("Location: index.php");
    exit();
}
header('Content-Type: application/json');
include "svliga.php";
$inicio=($_GET['code']-1)*10;
$comando="SELECT * FROM modelos INNER JOIN marcas ON modelos.cod_marca=marcas.cod_marca ORDER BY marcas.marca ASC, modelos.modelo ASC LIMIT ".$inicio.", 10";
$resultado=mysqli_query($liga,$comando);
$dados=array();
while ($linha=mysqli_fetch_assoc($resultado)) {
    array_push($dados, array("marca"=>$linha['marca'],"cod_modelo"=>$linha['cod_modelo'],"modelo"=>$linha['modelo']));
}
echo json_encode($dados);
mysqli_free_result($resultado);
mysqli_close($liga);
?>