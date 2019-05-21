<?php
$erros="";
session_start();
include "svliga.php";
if (isset($_POST['btSubmitMarca'])) {
    require "svliga.php";
    $marca=mysqli_real_escape_string($liga,$_POST['marca']);
    if ($marca=="") {
        $erros.="<p>Não pode inserir um campo vazio.</p>";
    }
    if ($erros==="") {
        $comandoAdicionaMarca=sprintf("INSERT INTO marcas(marca) VALUES('%s')",$marca);
        $resultadoAdicionaMarca=mysqli_query($liga,$comandoAdicionaMarca);
            if (!$resultadoAdicionaMarca) {
                echo "<p>Erro: ".mysqli_errno($liga) . " - " . mysqli_error($liga)."</p>";
                exit();
                }
        header('Location: acesso_reservado.php');// para nao voltar a submeter
    }
}
if (isset($_POST['btSubmitModelo'])) {
    require "svliga.php";
    $modelo=mysqli_real_escape_string($liga,$_POST['modelo']);
    $marca1=$_POST['marca1'];
    
    if ($modelo=="") {
        $erros.="<p>Não pode inserir um campo vazio.</p>";
    }
    if ($erros==="") {
    
        $comandoAdicionaModelo=sprintf("INSERT INTO modelos(modelo,cod_marca) VALUES('%s',%d)",$modelo,$marca1);
        $resultadoAdicionaModelo=mysqli_query($liga,$comandoAdicionaModelo);
            if (!$resultadoAdicionaModelo) {
                echo "<p>Erro: ".mysqli_errno($liga) . " - " . mysqli_error($liga)."</p>";
                exit();
                }
        header('Location: acesso_reservado.php');// para nao voltar a submeter
    }
}
?>
<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Acesso Reservado</title>
    <link rel="stylesheet" href="fontawesome-free-5.0.8/web-fonts-with-css/css/fontawesome-all.min.css">
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
        main {
            margin: 0 auto;
        }
        .li {
            display: inline-block;
            padding: 10px;
            border: 1px solid black;
            margin: 0;
        }
        .li a {
            text-decoration: none;
            color: black;
        }
        .utilizadores, .modelos {
            display: none;
        }
        #tabUtilizadores, #tabUtilizadores td, #tabUtilizadores th {
            border: 1px solid #000;
        }
        #tabUtilizadores td, #tabUtilizadores th {
            padding: 0 3px;
        }
        #tabUtilizadores td {
            max-width: 400px;
        }
        .liMarcas, .liModelos, .liUtilizadores {
            cursor: pointer;
        }
        .liMarcas {
            background-color: black;
            color: white;
        }
        .liMarcas a {
            color: white;
        }
        .fa-trash-alt {
            cursor: pointer;
        }
        .marcas {
            text-align: center;
        }
        #div_tabMarca{
            text-align: center;
            display: block;
        }
        #tabMarca{
            display: inline-block;
        }
        .modelos {
            text-align: center;
        }
        #div_tabModelos{
            text-align: center;
            display: block;
        }
        #tabModelos{
            display: inline-block;
        }
        .utilizadores {
            text-align: center;
        }
        #div_tabUtilizadores{
            text-align: center;
            display: block;
        }
        #tabUtilizadores{
            display: inline-block;
        }

    </style>
</head>
<body>
<header>
<?php
if (!isset($_SESSION['email']) || ($_SESSION['email'])!=="admin@admin.pt") {
    ?>
    <p>Apenas o admin pode aceder a esta página. Faça login como admin <a href="login.php">aqui</a>, ou regresse à <a href="index.php">página principal.</a></p>
    <?php    
} else {
    ?>
    <a href="index.php"><img src="imagens/logotipo.png" alt="Logotipo Standvirtual" id="logo"></a>
        <ul id="menu">
            <li class="li liMarcas"><a href="#">Marcas</a></li>
            <li class="li liModelos"><a href="#">Modelos</a></li>
            <li class="li liUtilizadores"><a href="#">Utilizadores</a></li>
        </ul>
    <hr>
</header>
<main>
<?php
    if ($erros!=="") {
    echo $erros;
    }
?>
<div class="div marcas">
    <h1>Marcas</h1>
    <form id="formmarcas" method="post" action="<?=$_SERVER['PHP_SELF']?>" enctype="multipart/form-data">
        <p><label for="marca">Marca:</label> <input type="text" id="marca" name="marca" maxlength="80"></p>
        <p><input type="submit" id="btSubmitMarca" name="btSubmitMarca" value="Inserir"></p>
    </form>
    <div id="div_tabMarca">
    <table id="tabMarca">
    <?php
    $comandoMarcasTotais="SELECT * FROM marcas ORDER BY marca ASC";
    $resultadoMarcasTotais=mysqli_query($liga,$comandoMarcasTotais);
    $totalMarcas=mysqli_num_rows($resultadoMarcasTotais);
    $totalPaginas=ceil($totalMarcas / 10)+1;
    $comandoPrimeiraPagina="SELECT * FROM marcas ORDER BY marca ASC LIMIT 0, 10";
    $resultadoPrimeiraPagina=mysqli_query($liga,$comandoPrimeiraPagina);
        if (!$resultadoMarcasTotais) {
            echo "<p>".mysqli_errno($liga)." - ".mysqli_error($liga)."</p>";
            die();
        }
    ?>
                <thead>
                    <tr>
                        <th>Marca</th>
                        <th>Operações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while($linha=mysqli_fetch_assoc($resultadoPrimeiraPagina)) {

                        ?>
                        <tr>
                            <td><?=$linha["marca"]?></td>
                            <td><a href="#" class="apagar" data-value="<?=$linha["cod_marca"]?>">Apagar</a></td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <?php for($i=1;$i<$totalPaginas;$i++) { 
        ?>
        <a href="#" class="pagina_marca"><?=$i;?></a>
                        <?php } ?>    
</div>
<div class="div modelos">
    <h1>Modelos</h1>
            <form id="formmodelos" method="post" action="<?=$_SERVER['PHP_SELF']?>">
                <p><label for="marca">Marca: </label>
                    <?php
                        $comandoMarca="SELECT * FROM marcas ORDER BY marca ASC";
                        $resultado=mysqli_query($liga,$comandoMarca);
                        ?>
                    <select id="marca1" name="marca1">
                            <option value="0">Seleccione...</option>
                        <?php
                        while($linha=mysqli_fetch_assoc($resultado)) {
                        ?>
                                    <option value="<?=$linha["cod_marca"]?>"><?=$linha["marca"]?></option>
                                    <?php
                                }
                                mysqli_free_result($resultado);
                            ?>
                    </select>
                </p>
               <p><label for="modelo">Modelo:</label> <input type="text" id="modelo" name="modelo" maxlength="80"></p>
                <p><input type="submit" id="btSubmitModelo" name="btSubmitModelo" value="Inserir"></p>
            </form>
    <div id="div_tabModelos">
    <table id="tabModelos">
    <?php
    $comandoModelosTotais="SELECT * FROM modelos INNER JOIN marcas ON modelos.cod_marca=marcas.cod_marca ORDER BY marcas.marca ASC, modelos.modelo ASC;";
    $resultadoModelosTotais=mysqli_query($liga,$comandoModelosTotais);
    $totalModelos=mysqli_num_rows($resultadoModelosTotais);
    $totalPaginasModelos=ceil($totalModelos / 10)+1;
    $comandoPrimeiraPaginaModelos="SELECT * FROM modelos INNER JOIN marcas ON modelos.cod_marca=marcas.cod_marca ORDER BY marcas.marca ASC, modelos.modelo ASC LIMIT 0, 10";
    $resultadoPrimeiraPaginaModelos=mysqli_query($liga,$comandoPrimeiraPaginaModelos);
        if (!$resultadoPrimeiraPaginaModelos) {
            echo "<p>".mysqli_errno($liga)." - ".mysqli_error($liga)."</p>";
            die();
        }
    ?>
                <thead>
                    <tr>
                        <th>Marca</th>
                        <th>Modelo</th>
                        <th>Operações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while($linha1=mysqli_fetch_assoc($resultadoPrimeiraPaginaModelos)) {

                        ?>
                        <tr>
                            <td><?=$linha1["marca"]?></td>
                            <td><?=$linha1["modelo"]?></td>
                            <td><a href="#" class="apagar1" data-value1="<?=$linha1["cod_modelo"]?>">Apagar</a></td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <?php for($i=1;$i<$totalPaginasModelos;$i++) { 
        ?>
        <a href="#" class="pagina_modelo"><?=$i;?></a>
                        <?php } ?> 
</div>
<div class="div utilizadores">
    <h1>Utilizadores</h1>
    <?php
    $comandoUtilizadores="SELECT * FROM utilizadores";
    $resultadoUtilizadores=mysqli_query($liga,$comandoUtilizadores);
        if (!$resultadoUtilizadores) {
            echo "<p>".mysqli_errno($liga)." - ".mysqli_error($liga)."</p>";
            die();
        }
    ?>
    <table id="tabUtilizadores">
                <thead>
                    <tr>
                        <th><a href="#">Nome</a></th>
                        <th><a href="#">Email</a></th>
                        <th>Estado</th>
                        <th><a href="#">Data de registo</a></th>
                        <th>Operações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while($linha=mysqli_fetch_assoc($resultadoUtilizadores)) {

                        ?>
                        <tr>
                            <td><?=$linha["nome"]?></td>
                            <td><?=$linha["email"]?></td>
                            <td>
                                <form id="formestado" method="post">
                                <select class="seletores" id="selectEstado" name="estado">
                                    <?php
                                    if ($linha["estado"]==A) {
                                        ?>
                                    <option value="<?=$linha["estado"]?>">Activado</option>
                                    <option value="R">Registado</option>
                                    <option value="S">Suspenso</option>
                                    <?php
                                    };
                                    if ($linha["estado"]==R) {
                                        ?> 
                                    <option value="<?=$linha["estado"]?>">Registado</option>
                                    <option value="A">Activado</option>
                                    <option value="S">Suspenso</option>
                                    <?php
                                    };
                                    if ($linha["estado"]==S) {
                                        ?>
                                      <option value="<?=$linha["estado"]?>">Suspenso</option>
                                      <option value="A">Activado</option>
                                      <option value="R">Registado</option>
                                    <?php
                                    };
                                    ?>
                                </select>
                                </form>
                            </td>
                            <td><?=$linha["data_registo"]?></td>
                            <td><i class="fas fa-trash-alt" data-utilizador="<?=$linha["cod_utilizador"]?>"></i></td>
                        </tr>
                        <?php
                    }}
                    ?>
                </tbody>
            </table>    
</div>
</main>
</body>
<script src="js/jquery-3.3.1.min.js"></script>
<script>
// paginar modelos:
$(".pagina_modelo").on("click",function() {
        var paginamodelo=$(this).text();
        $.ajax({
        type: "get",
        url: "paginar_modelos.php",
        data: { code: paginamodelo },
        dataType: "json",
        success: function(dados) {
            var resultado1='<table id="tabModelos"><thead><tr><th>Marca</th><th>Modelo</th><th>Operações</th></thead><tbody>';
            dados.forEach(function(pagina){
                resultado1+=`
                            <tr>
                                <td>${pagina.marca}</td>
                                <td>${pagina.modelo}</td>
                                <td><a href="#" class="apagar1" data-value1="${pagina.cod_modelo}">Apagar</a></td>
                            </tr>
                         `;
            });
            resultado1+='</tbody></table>';
            $("#div_tabModelos").html(resultado1);
        },
        error: function(){
            $("#div_tabModelos").html("Ocorreu um erro no pedido");
        }
        });
})
// paginar marcas:
$(".pagina_marca").on("click",function() {
        var pagina=$(this).text();
        $.ajax({
        type: "get",
        url: "paginar_marcas.php",
        data: { code: pagina },
        dataType: "json",
        success: function(dados) {
            var resultado='<table id="tabMarca"><thead><tr><th>Marca</th><th>Operações</th></thead><tbody>';
            dados.forEach(function(pagina){
                resultado+=`
                            <tr>
                                <td>${pagina.marca}</td>
                                <td><a href="#" class="apagar" data-value="${pagina.cod_marca}">Apagar</a></td>
                            </tr>
                         `;
            });
            resultado+='</tbody></table>';
            $("#div_tabMarca").html(resultado);
        },
        error: function(){
            $("#div_tabMarca").html("Ocorreu um erro no pedido");
        }
        });
})
// apagar utilizadores:
$(".fas").on("click",function() {
        var codutilizador=$(this).attr("data-utilizador");
        if (confirm("Tem a certeza que pretende apagar?")) {
            $.ajax({
        type: "get",
        url: "utilizador_apagar.php",
        data: { code: codutilizador },
        dataType: "json",
        success: function() {}
            })    
        alert('O utilizador foi apagado!');
        } 
        window.location.reload();  
        });
// apagar marcas:
$(document).on('click', '.apagar', function() {
        var codMarca=$(this).attr("data-value");
        if (confirm("Tem a certeza que pretende apagar?")) {
            $.ajax({
        type: "get",
        url: "marcas_dados.php",
        data: { code: codMarca },
        dataType: "json",
        success: function() {}
            })    
        alert('A marca foi apagada!');
        } 
        window.location.reload();  
        });
// apagar modelos:
$(document).on('click', '.apagar1', function() {
        var codModelo=$(this).attr("data-value1");
        if (confirm("Tem a certeza que pretende apagar?")) {
            $.ajax({
        type: "get",
        url: "modelos_apagar.php",
        data: { code: codModelo },
        dataType: "json",
        success: function() {}
            })    
        alert('O modelo foi apagado!');
        } 
        window.location.reload();  
        });
// Alterar estado de utilizador:    
$(document).on('change', '.seletores', function() {
        var estadoselec=$(this).val();
        var codigoEmail=$(this).parent().parent().prev().text();
        $.ajax({
        type: "get",
        url: "alterar_estado.php",
        data: { code_estado: estadoselec, code_email: codigoEmail },
        dataType: "json",
        success: function() {}
            })    
        alert('O estado foi alterado!');
        window.location.reload();  
        });
//clicar nas tabs:
$(".liUtilizadores").on("click",function() {
    $(".div").hide();
    $(".utilizadores").show();
    $(".li").css("background-color", "white");
    $(".li a").css("color", "black");
    $(".liUtilizadores a").css("color", "white");
    $(".liUtilizadores").css("background-color", "black");
    });
$(".liMarcas").on("click",function() {
    $(".div").hide();
    $(".marcas").show();
    $(".li").css("background-color", "white");
    $(".li a").css("color", "black");
    $(".liMarcas a").css("color", "white");
    $(".liMarcas").css("background-color", "black");
    });
$(".liModelos").on("click",function() {
    $(".div").hide();
    $(".modelos").show();
    $(".li").css("background-color", "white");
    $(".li a").css("color", "black");
    $(".liModelos a").css("color", "white");
    $(".liModelos").css("background-color", "black");
    });
</script>
</html>