<?php
session_start();
if (!isset($_SESSION['id']) || $_SESSION['estado']=="R") {
    $_SESSION['pag']="perfil.php";
    header("Location: login.php");
}
require "svliga.php";
$erros="";
?>
<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Anúncios</title>
    <style>
        * {
            box-sizing: border-box;
        }
        html {
            font-size: 16px;
            max-width: 72rem;
            font-family: Arial;
            margin: 0 auto;
        }
        header {
            text-align: center;
        }
        #logo {
            width: 25rem;
            margin: 0 auto;
        }
        main {
            text-align:center;
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
        .tabela, .tabela td, .tabela th {
            border: 1px solid #000;
        }
        .tabela td, .tabela th {
            padding: 10px;
        }
        .tabela td {
            max-width: 400px;
        }
        .tabela img {
            width: 320px;
        }
    </style>
</head>
<body>
<header>
    <a href="index.php"><img src="imagens/logotipo.png" alt="Logotipo Standvirtual" id="logo"></a>
    <ul id="menu">
        <li><a href="perfil.php">Perfil</a></li><li><a href="anuncios.php">Anúncios</a></li><li><a href="logout.php">Sair</a></li>
    </ul>   
    <hr>
</header>
<main>
<h1>Anúncios activos</h1>
<p><a href="inseriranuncio.php">Inserir anúncio</a></p>
  <?php   
    $comandoAnuncio="SELECT *  FROM automoveis WHERE cod_utilizador='".$_SESSION['id']."'";
    $resultadoAnuncio=mysqli_query($liga,$comandoAnuncio);
        if (!$resultadoAnuncio) {
            echo "<p>".mysqli_errno($liga)." - ".mysqli_error($liga)."</p>";
            die();
        }
        $totalAnuncios=mysqli_num_rows($resultadoAnuncio);
        if ($totalAnuncios>0) {
    ?>
    <table class="tabela">
                <thead>
                    <tr>
                        <th>Foto</th>
                        <th>Título</th>
                        <th>Marca/Modelo</th>
                        <th>Mês/Ano</th>
                        <th>Preço</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                while($linha=mysqli_fetch_assoc($resultadoAnuncio)) {
                    //corresponder modelo
                    $comandoModelos="SELECT modelo FROM modelos WHERE cod_modelo='".$linha["cod_modelo"]."'";
                    $resultadoModelo=mysqli_query($liga,$comandoModelos);
                    $linhaModelo=mysqli_fetch_assoc($resultadoModelo);
                    //corresponder marca
                    $comandoMarcas="SELECT marca FROM marcas WHERE cod_marca='".$linha["cod_marca"]."'";
                    $resultadoMarca=mysqli_query($liga,$comandoMarcas);
                    $linhaMarca=mysqli_fetch_assoc($resultadoMarca);
                        ?>
                        <tr>
                            <td><a href="mostraanuncio.php?cod_auto=<?=$linha["cod_auto"]?>"><img src="fotos/<?=$linha["foto1"]?>"></a></td>
                            <td><a href="mostraanuncio.php?cod_auto=<?=$linha["cod_auto"]?>"><?=$linha["titulo"]?></a></td>
                            <td><?=$linhaMarca["marca"]?> <?=$linhaModelo["modelo"]?></td>
                            <td><?=$linha["mes"]?>/<?=$linha["ano"]?></td>
                            <td><?=$linha["preco"]?>.00 €</td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
 <?php
                    } else {
                    ?>
        <p>Ainda não inseriu nenhum anúncio.</p>
        <?php
                    }
                    ?>
<h2>Ofertas</h2>
  <?php   
    $comandoOfertas="SELECT * FROM (SELECT utilizadores.cod_utilizador, automoveis.titulo, ofertas.oferta, ofertas.texto, ofertas.nome, ofertas.email, ofertas.telefone FROM utilizadores INNER JOIN automoveis ON utilizadores.cod_utilizador=automoveis.cod_utilizador INNER JOIN ofertas ON ofertas.cod_auto=automoveis.cod_auto) AS ofertasTotais WHERE cod_utilizador='".$_SESSION['id']."'";
    $resultadoOfertas=mysqli_query($liga,$comandoOfertas);
        if (!$resultadoOfertas) {
            echo "<p>".mysqli_errno($liga)." - ".mysqli_error($liga)."</p>";
            die();
        }
        $totalOfertas=mysqli_num_rows($resultadoOfertas);
        if ($totalOfertas>0) {
    ?>
    <table class="tabela">
                <thead>
                    <tr>
                        <th>Anúncio</th>
                        <th>Oferta</th>
                        <th>Mensagem</th>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Telefone</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                while($linhaOfertas=mysqli_fetch_assoc($resultadoOfertas)) {
                        ?>
                        <tr>
                            <td><?=$linhaOfertas["titulo"]?></td>
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
</main>
</body>
</html>