<?php
session_start();
$cod_auto=$_GET['cod_auto'];
include "svliga.php";
$comando="SELECT * FROM automoveis WHERE cod_auto='".$cod_auto."'";
$resultado=mysqli_query($liga,$comando);
$linha=mysqli_fetch_assoc($resultado);
if (!isset($_SESSION['id']) || $_SESSION['estado']=="R" 
    || //caso o anuncio não pertença ao utilizador com o login feito: 
    $linha['cod_utilizador']!==$_SESSION['id']) {
    $_SESSION['pag']="perfil.php";
    header("Location: login.php");
}
?>
<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Anúncio</title>
    <style>
        * {
            box-sizing: border-box;
        }
        html {
            font-size: 16px;
            font-family: Arial;
            margin: 0 auto;
        }
        #logo {
            width: 15rem;
        }
        #menu {
            padding: 0;           
        }
        #menu li {
            display: inline-block;
            padding: 12px 12px;
            margin: 0;
            border: 1px solid black;
        }
        #menu li:hover {
            background-color: black;
            color: white;
        }
        #menu li:hover a {
            color: white;
        }
        #menu li a {
            text-decoration: none; 
            color: black;
        }
        #tabela1 {
            min-width: 300px;
            min-height: 525px;
        }
        #tabela1 tr td:first-of-type {
            text-align: center;
            font-weight: bold;
        }
        #tabela1 td, #tabela1 th, #tabela1 tr {
            padding: 0;
            margin: 0;
        }
        #tabela1 p {
            margin: 0;
        }
        #tabela1 img {
            width: 100px;
        }
        #tdCar p {
            margin: 10px 0;
        }
        .tabela, .tabela td, .tabela th {
            border: 1px solid #000;
        }
        .tabela td, .tabela th {
            padding: 10px;
        }
        .tabela td {
            max-width: 400px;
        }
    </style>
</head>
<body>
<header>
    <a href="index.php"><img src="imagens/logotipo.png" alt="Logotipo Standvirtual" id="logo"></a>
    <ul id="menu">
        <li><a href="perfil.php">Perfil</a></li><li><a href="anuncios.php">Anúncios Activos</a></li><li><a href="logout.php">Sair</a></li>
    </ul>   
    <hr>
</header>
<h1><?=$linha['titulo']?></h1>
<?php
//corresponder modelo
$comandoModelos="SELECT modelo FROM modelos WHERE cod_modelo='".$linha['cod_modelo']."'";
$resultadoModelo=mysqli_query($liga,$comandoModelos);
$linhaModelo=mysqli_fetch_assoc($resultadoModelo);
//corresponder marca
$comandoMarcas="SELECT marca FROM marcas WHERE cod_marca='".$linha['cod_marca']."'";
$resultadoMarca=mysqli_query($liga,$comandoMarcas);
$linhaMarca=mysqli_fetch_assoc($resultadoMarca);
//corresponder caracteristicas
$comandoCaracteristicas="SELECT * FROM caracteristicas WHERE linkanuncio='".$linha['linkanuncio']."'";
$resultadoCaracteristicas=mysqli_query($liga,$comandoCaracteristicas);
$linhaCaracteristicas=mysqli_fetch_assoc($resultadoCaracteristicas);
?>
<table id="tabela1">
   <tbody>
       <tr>
        <td><p>Marca:</p></td>   
        <td><p><?=$linhaMarca['marca']?></p></td>   
       </tr>
       <tr>
        <td><p>Modelo:</p></td>   
        <td><p><?=$linhaModelo['modelo']?></p></td>   
       </tr>
       <tr>
        <td><p>Registo:</p></td>   
        <td><p><?=$linha['mes']?>/<?=$linha['ano']?></p></td>   
       </tr>
       <tr>
        <td><p>Cilindrada:</p></td>   
        <td><p><?=$linha['cilindrada']?>cc (<?=$linha['potencia']?>cv)</p></td>   
       </tr>
       <tr>
        <td><p>Combustível:</p></td>   
        <td><p>
        <?php
        if($linha['combustivel']=="D") {
            echo "Diesel";
        } else {
            echo "Gasolina";
        }?>
        </p></td>   
       </tr>
       <tr>
        <td><p>Nº Kms:</p></td>   
        <td><p><?=$linha['kms']?></p></td>   
       </tr>
       <tr>
        <td><p>Cor:</p></td>   
        <td><p><?=$linha['cor']?></p></td>   
       </tr>
       <tr>
        <td><p>Número de portas:</p></td>   
        <td><p><?=$linha['nportas']?></p></td>   
       </tr>
       <tr>
        <td><p>Descrição:</p></td>   
        <td><p><?=$linha['descricao']?></p></td>   
       </tr>
       <tr>
        <td><p>Características:</p></td>   
        <td id="tdCar">
            <?php
        if ($linhaCaracteristicas['jantes']=="s"){
            echo "<p>Jantes de liga leve</p>";
        }
        if ($linhaCaracteristicas['direccao']=="s"){
            echo "<p>Direcção assistida</p>";
        }
        if ($linhaCaracteristicas['fecho']=="s"){
            echo "<p>Fecho central</p>";
        }
        if ($linhaCaracteristicas['ac']=="s"){
            echo "<p>Ar condicionado</p>";
        }
        if ($linhaCaracteristicas['esp']=="s"){
            echo "<p>ESP</p>";
        }
        if ($linhaCaracteristicas['vidros']=="s"){
            echo "<p>Vidros eléctricos</p>";
        }
        if ($linhaCaracteristicas['computador']=="s"){
            echo "<p>Computador</p>";
        }
        if ($linhaCaracteristicas['livro']=="s"){
            echo "<p>Livro de revisões</p>";
        }
        if ($linhaCaracteristicas['farois']=="s"){
            echo "<p>Faróis de nevoeiro</p>";
        }

            ?>

        </td>   
       </tr>
       <tr>
        <td><p>Preço:</p></td>   
        <td><p><?=$linha['preco']?>.00€</p></td>   
       </tr>
       <tr>
        <td><p>Fotos:</p></td>   
        <td><p><img src="fotos/<?=$linha['foto1']?>" alt="Foto carro"> <img src="fotos/<?=$linha['foto2']?>" alt="Foto carro"> <img src="fotos/<?=$linha['foto3']?>" alt="Foto carro"> <img src="fotos/<?=$linha['foto4']?>" alt="Foto carro"> <img src="fotos/<?=$linha['foto5']?>" alt="Foto carro"></p></td>   
       </tr>
    </tbody>
</table>
<h2>Ofertas</h2>
  <?php   
    $comandoOfertasCarro="SELECT * FROM ofertas WHERE cod_auto='".$cod_auto."'";
    $resultadoOfertasCarro=mysqli_query($liga,$comandoOfertasCarro);
        if (!$resultadoOfertasCarro) {
            echo "<p>".mysqli_errno($liga)." - ".mysqli_error($liga)."</p>";
            die();
        }
        $totalOfertas=mysqli_num_rows($resultadoOfertasCarro);
        if ($totalOfertas>0) {
    ?>
    <table class="tabela">
                <thead>
                    <tr>
                        <th>Oferta</th>
                        <th>Mensagem</th>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Telefone</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                while($linhaOfertas=mysqli_fetch_assoc($resultadoOfertasCarro)) {
                        ?>
                        <tr>
                            <td><?=$linhaOfertas["oferta"]?> €</td>
                            <td><?=$linhaOfertas["texto"]?></td>
                            <td><?=$linhaOfertas["nome"]?></td>
                            <td><?=$linhaOfertas["email"]?></td>
                            <td><?=$linhaOfertas["telefone"]?></td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
 <?php
                    } else {
                    ?>
        <p>Ainda não tem ofertas.</p>
        <?php
                    }
?>
<p><a href="index.php">Página principal</a></p>