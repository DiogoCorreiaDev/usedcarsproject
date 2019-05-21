<?php
session_start();
?>
<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Página inicial - StandVirtual</title>
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
        .row {
            display: table;
            width: 100%;
        }
        .col-1-of-2 {
            display: table-cell;
            width: 50%;
        }
        #formPesquisa {
            border: 2px solid black;
            padding: 10px;
        }
        #tabelaPesquisa, #tabelaPesquisa td, #tabelaPesquisa th {
            border: 1px solid #000;
        }
        #tabelaPesquisa td, #tabelaPesquisa th {
            padding: 10px;
        }
        #tabelaPesquisa td {
            max-width: 400px;
        }
        #tabelaPesquisa img {
           width: 100%;
        }
        #anchor_destaques {
            position: relative;
        }
        #anchor_destaques h3 {
            position: absolute;
            margin: 0;
            width: 100%;
            font-size: 1.5rem;
            top: -0.8rem;
            color: rgba(206, 206, 206, 0.9);
            background-color:rgba(75, 75, 75, 0.8);
        }
        #fotoDestaque {
            max-width: 38rem;/*600px*/
        }
    </style>
</head>
<body>
<header>
    <?php
    include "svliga.php";
    ?>
    <a href="index.php"><img src="imagens/logotipo.png" alt="Logotipo Standvirtual" id="logo"></a>
        <ul id="menu">
           <?php
    if(!isset($_SESSION['id'])) {
        ?>
            <li><a href="registo.php">Registo de utilizador</a></li><li><a href="login.php">Login</a></li>
            <?php
    } else {
        ?>
        <li><a href="perfil.php">Perfil</a></li><li><a href="logout.php">Sair</a></li>
        <?php
    }
    ?>
        </ul>
    <hr>
</header>
<main>
    <div class="row">
        <div class="col-1-of-2">
            <h2>Pesquisa</h2>
            <form id="formPesquisa" method="post" action="<?=$_SERVER['PHP_SELF']?>">
                <p><label for="marca">Marca: </label>
                    <select id="marca" name="marca">
                            <option value="0">Seleccione...</option>
                            <?php
                                $comandoMarca="SELECT * FROM marcas ORDER BY marca ASC";
                                $resultado=mysqli_query($liga,$comandoMarca);
                                while($linha=mysqli_fetch_assoc($resultado)) {
                                    ?>
                                    <option value="<?=$linha["cod_marca"]?>"><?=$linha["marca"]?></option>
                                    <?php
                                }
                                mysqli_free_result($resultado);
                            ?>
                    </select>
                </p>
                <p><label for="modelo">Modelo: </label>
                    <select id="modelo" name="modelo">
                    </select>
                </p>
                <p><label for="combustivel">Combustível: </label>
                    <select id="combustivel" name="combustivel">
                        <option value="0">Seleccione...</option>
                        <option value="G">Gasolina</option>
                        <option value="D">Diesel</option>
                    </select>
                </p>
                <p><input type="submit" id="btSubmit" name="btSubmit" value="Pesquisar"></p>
            </form>
        </div>
        <div class="col-1-of-2">
            <?php
            //coloca o último anuncio em destaque
            $comandoDestaque="SELECT foto1, cod_auto, titulo FROM automoveis WHERE destaque='s' ORDER BY cod_auto DESC LIMIT 1";
            $resultadoDestaque=mysqli_query($liga,$comandoDestaque);
            $linhaDestaque=mysqli_fetch_assoc($resultadoDestaque);
            if (!$resultadoDestaque) {
            echo "<p>".mysqli_errno($liga)." - ".mysqli_error($liga)."</p>";
            die();
        }
            ?>
                <h2>Destaques</h2>
                <a id="anchor_destaques" href="automovel.php?cod_auto=<?=$linhaDestaque["cod_auto"]?>">
                    <img id="fotoDestaque" src="fotos/<?=$linhaDestaque["foto1"]?>" alt="<?=$linhaDestaque["titulo"]?>">
                    <h3><?=$linhaDestaque["titulo"]?></h3>
                </a>
        </div>
    </div>
    <?php
        $erros="";
    if (isset($_POST['btSubmit'])) {
//            echo "<pre>".print_r($_POST,TRUE)."</pre>";
        $cod_marca=$_POST['marca'];
        $cod_modelo=$_POST['modelo'];
        $combustivel=$_POST['combustivel'];

        $comandoPesquisa="SELECT *  FROM automoveis WHERE cod_marca='".$cod_marca."' AND combustivel='".$combustivel."' AND cod_modelo='".$cod_modelo."'";

        $resultadoPes=mysqli_query($liga,$comandoPesquisa);
        if (!$resultadoPes) {
            echo "<p>".mysqli_errno($liga)." - ".mysqli_error($liga)."</p>";
            die();
        }
        $total=mysqli_num_rows($resultadoPes);
        if ($total===0) {
            echo "<p>Não existe nenhum veículo correspondente!</p>";
        } else {
            //corresponder modelo
            $comandoModelos="SELECT modelo FROM modelos WHERE cod_modelo='".$cod_modelo."'";
            $resultadoModelo=mysqli_query($liga,$comandoModelos);
            $linhaModelo=mysqli_fetch_assoc($resultadoModelo);
            //corresponder marca
            $comandoMarcas="SELECT marca FROM marcas WHERE cod_marca='".$cod_marca."'";
            $resultadoMarca=mysqli_query($liga,$comandoMarcas);
            $linhaMarca=mysqli_fetch_assoc($resultadoMarca);
            
            if ($combustivel=="D") {
            
            echo "<p><strong>Pesquisa por: </strong>".$linhaMarca["marca"]." ".$linhaModelo["modelo"]." a Diesel </p>";} else {
                echo "<p><strong>Pesquisa por: </strong>".$linhaMarca["marca"]." ".$linhaModelo["modelo"]." a Gasolina </p>";
            }
            echo "<p><strong>Total de resultados: </strong>".$total."</p>";
            ?>
            <table id="tabelaPesquisa">
                <thead>
                    <tr>
                        <th>Foto</th>
                        <th>Título do anúncio</th>
                        <th>Mês/Ano</th>
                        <th>Preço</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while($linha=mysqli_fetch_assoc($resultadoPes)) {
                        ?>
                        <tr>
                            <td><a href="automovel.php?cod_auto=<?=$linha["cod_auto"]?>"><img src="fotos/<?=$linha["foto1"]?>"></a></td>
                            <td><a href="automovel.php?cod_auto=<?=$linha["cod_auto"]?>"><?=$linha["titulo"]?></a></td>
                            <td><?=$linha["mes"]?>/<?=$linha["ano"]?></td>
                            <td><?=$linha["preco"]?>.00€</td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
            <?php
        }
        mysqli_free_result($resultadoPes);
    }
    mysqli_close($liga);
    ?>
</main>
<script src="js/jquery-3.3.1.min.js"></script>
<script>
// Modelo mudar dinamicamente:    
$(document).on('change', '#marca', function() {
    
        var codMarca=$("#marca").val();
        
        $.ajax({
        type: "get",
        url: "modelos_dados.php",
        data: { code: codMarca },
        dataType: "json",
        success: function(dados) {
            if (dados.length!==0) {
                var resultado='<option value="0">Seleccione...</option>';
                dados.forEach(function(marca){
                    resultado+='<option value="'+marca.cod_modelo+'">'+marca.modelo+'</option>';
                });
                $("#modelo").html(resultado);

            } else {
                $("#modelo").html("<option value='0'>-- Não existem modelos --</option>");
            }}})
        });
</script>
</body>
</html>