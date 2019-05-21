<?php
session_start();
$cod_auto=$_GET['cod_auto'];
if (!isset($cod_auto)) {
    header("Location: index.php");
}
include "svliga.php";
    $comando="SELECT * FROM automoveis WHERE cod_auto='".$cod_auto."'";
    $resultado=mysqli_query($liga,$comando);
    $linha=mysqli_fetch_assoc($resultado);
?>
<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title>automovel</title>
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
            vertical-align: top;

        }
        #tabela1 {
            width: 100%;
            min-width: 300px;
/*            border: 1px solid black;*/
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
        #tdCar p {
            margin: 10px 0;
        }
        #div-foto-principal {
            width: 650px;
            height: 450px;
        }
        #foto-principal {
            max-width: 570px;
            max-height: 450px;
        }
        #div-fotos-secundarias {
            max-width: 570px;
            height: 100px;
        }
        #div-fotos-secundarias img {
            width: 20%;
            padding: 0;
            margin: 0;
            cursor: pointer;
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
            <li><a href="registo.php">Registo de utilizador</a></li><li><a href="login.php">Login</a></li><li><a href="acesso_reservado.php">Acesso reservado</a></li>
            <?php
    } else {
        ?>
        <li><a href="perfil.php">Perfil</a></li><li><a href="anuncios.php">Anúncios activos</a></li><li><a href="logout.php">Sair</a></li>
        <?php
    }
    ?>
        </ul>
    <hr>
</header>
<main>
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
<div class="row">
        <div class="col-1-of-2">
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
               </tbody>
           </table>
        </div>
        <div class="col-1-of-2">
                <div id="div-foto-principal">
                   <img id="foto-principal" src="fotos/<?=$linha['foto1']?>">
                </div>
                <div id="div-fotos-secundarias">
                      <img class="foto-secundaria primeira" src="fotos/<?=$linha['foto1']?>"><img class="foto-secundaria" src="fotos/<?=$linha['foto2']?>" ><img class="foto-secundaria" src="fotos/<?=$linha['foto3']?>"><img class="foto-secundaria" src="fotos/<?=$linha['foto4']?>"><img class="foto-secundaria" src="fotos/<?=$linha['foto5']?>"> 
                </div>
        </div>
</div>
<?php
if (isset($_SESSION['id'])) {
          $erros=""; 
$comandoInfoVendedor="SELECT nome,email,telefone FROM utilizadores WHERE cod_utilizador='".$linha['cod_utilizador']."'";
$resultadoInfoVendedor=mysqli_query($liga,$comandoInfoVendedor);
$linhaInfoVendedor=mysqli_fetch_assoc($resultadoInfoVendedor);
?>
<div id="oferta">
    <h2>Entre em contacto com o vendedor</h2>
    <p><strong>Nome: </strong><?=$linhaInfoVendedor['nome']?></p>
    <p><strong>Email: </strong><?=$linhaInfoVendedor['email']?></p>
    <p><strong>Telefone: </strong><?=$linhaInfoVendedor['telefone']?></p>
    <form id="formoferta" method="post">
    <p><strong><label for="Texto">Texto:</label></strong> <textarea id="texto" name="texto" rows="4" cols="50"></textarea></p>
    <p><strong><label for="oferta">Oferta: </label></strong><input type="text" id="oferta" name="oferta" maxlength="20" value="<?=($erros!=="") ? $oferta : ''?>">€</p>
    <p><input type="submit" id="btSubmit" name="btSubmit" value="Enviar"></p>
    </form>
</div>

<?php


if (isset($_POST['btSubmit'])) {
    //    echo "<pre>".print_r($_POST,TRUE)."</pre>";
    $oferta=mysqli_real_escape_string($liga,$_POST['oferta']);
    $texto=mysqli_real_escape_string($liga,$_POST['texto']);
    
    foreach($_POST as $chave=>$valor)  {
        if (empty($_POST[$chave])) {
            $erros.="<p>Todos os campos são de preenchimento obrigatório.</p>";
            break;
        }
    }
    if ($erros==="") {
        if (!is_numeric($oferta) || $oferta<0) {
            $erros.="<p>O campo 'oferta' só pode conter algarismos!</p>";
        }
        if ($erros==="") {  
            // enviar oferta:  
            $comandoEnviarOferta=sprintf("INSERT INTO ofertas(cod_utilizador,cod_auto,nome,email,telefone,texto,oferta) VALUES(%d,%d,'%s','%s',%d,'%s',%d)",$_SESSION['id'],$cod_auto,$_SESSION['nome'],$_SESSION['email'],$_SESSION['telefone'],$texto,$oferta);
                
                if (!mysqli_query($liga,$comandoEnviarOferta)) {
                    echo "<p>Erro: ".mysqli_errno($liga) . " - " . mysqli_error($liga)."</p>";
                    exit();
                }
                if (mysqli_affected_rows($liga)===0) {
                    $erros.="<p>Erro no envio da oferta.</p>";
                } else {
                    ?>
                    <p id="sucesso">A sua oferta foi enviada com sucesso.</p>
                    <?php
                }
        }}
}         
        if ($erros!=="") {
    echo $erros;
    }
}

?>
</main>
<script src="js/jquery-3.3.1.min.js"></script>
<script>
//caso seja enviado com sucesso:
if ($("#sucesso").length!==0) {
    $("#oferta").hide();
}
//mudar foto com hover
$(".foto-secundaria").mouseover(function(){
    var source=$(this).attr("src");
    console.log(source);
    $("#foto-principal").attr("src", source);
        });
$(".foto-secundaria").mouseout(function(){
    var source=$(".primeira").attr("src");
    console.log(source);
    $("#foto-principal").attr("src", source);
        });
</script>
</body>
</html>