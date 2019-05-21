<?php
if (empty($_GET['code'])) {
    header("Location: index.php");
    exit();
}
header('Content-Type: application/json');
include "svliga.php";
$inicio=($_GET['code']-1)*10;
$comando="SELECT * FROM marcas ORDER BY marca ASC LIMIT ".$inicio.", 10";
$resultado=mysqli_query($liga,$comando);
$dados=array();
while ($linha=mysqli_fetch_assoc($resultado)) {
    array_push($dados, array("cod_marca"=>$linha['cod_marca'],"marca"=>$linha['marca']));
}
echo json_encode($dados);
mysqli_free_result($resultado);
mysqli_close($liga);
?>