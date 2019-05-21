<?php
session_start();
if (!isset($_SESSION['id']) || $_SESSION['estado']=="R") {
    $_SESSION['pag']="perfil.php";
    header("Location: login.php");
}
require "svliga.php";
$erros="";
if (isset($_POST['btSubmit'])) {
//    echo "<pre>".print_r($_POST,TRUE)."</pre>";
//    echo "<pre>".print_r($_FILES,TRUE)."</pre>";
//    print_r($linkanuncio);
    
    foreach($_POST as $chave=>$valor)  {
        if (empty($_POST[$chave])) {
            $erros.="<p>Todos os campos são de preenchimento obrigatório.</p>";
            break;
        }
    } 

        //tabela automoveis
        $titulo=mysqli_real_escape_string($liga,$_POST['titulo']);
        $cod_marca=$_POST['marca'];
        $cod_utilizador=$_SESSION['id'];
        $mes=mysqli_real_escape_string($liga,$_POST['dataregisto1']);
        $ano=mysqli_real_escape_string($liga,$_POST['dataregisto2']);
        $cilindrada=mysqli_real_escape_string($liga,$_POST['cilindrada']);
        $potencia=mysqli_real_escape_string($liga,$_POST['potencia']);
        $combustivel=$_POST['combustivel'];
        $kms=(int)$_POST['kms'];
        $preco=(int)$_POST['preco'];
        $cor=mysqli_real_escape_string($liga,$_POST['cor']);
        $nportas=(int)$_POST['nportas'];
        $descricao=mysqli_real_escape_string($liga,$_POST['descricao']);
        $linkanuncio=mt_rand().date('YmdHis');
    

        //tabela características:
        if (isset($_POST['jantes'])) {
            $jantes=$_POST['jantes'];} else {
            $jantes="n";
        }
        if (isset($_POST['direccao'])) {
            $direccao=$_POST['direccao'];} else {
            $direccao="n";
        }
        if (isset($_POST['fecho'])) {
            $fecho=$_POST['fecho'];} else {
            $fecho="n";
        }
        if (isset($_POST['ac'])) {
            $ac=$_POST['ac'];} else {
            $ac="n";
        }
        if (isset($_POST['esp'])) {
            $esp=$_POST['esp'];} else {
            $esp="n";
        }
        if (isset($_POST['vidros'])) {
            $vidros=$_POST['vidros'];} else {
            $vidros="n";
        }
        if (isset($_POST['computador'])) {
            $computador=$_POST['computador'];} else {
            $computador="n";
        }
        if (isset($_POST['livro'])) {
            $livro=$_POST['livro'];} else {
            $livro="n";
        }
        if (isset($_POST['farois'])) {
            $farois=$_POST['farois'];} else {
            $farois="n";
        }
        if (isset($_POST['modelo'])) {
            $cod_modelo=$_POST['modelo'];} else {
            $cod_modelo=null;
        }
        if (isset($_POST['destaque'])) {
            $destaque=$_POST['destaque'];} else {
            $destaque="n";
        }


    
    /* Upload de ficheiros - foto 1 */
    if ($_FILES['foto']['error']!==0) {
        switch($_FILES['foto']['error']) {
            case '1':
                $erros.='<p>O ficheiro transferido excede a directiva upload_max_filesize do php.ini.</p>';
                break;
            case '2':
                $erros.='<p>O ficheiro transferido excede a directiva MAX_FILE_SIZE do formulário.</p>';
                break;
            case '3':
                $erros.='<p>O ficheiro foi parcialmente transferido.</p>';
                break;
            case '4':
                $erros.='<p>A 1ª foto não foi transferida.</p>';
                break;
            case '6':
                $erros.='<p>Não existe pasta temporária.</p>';
                break;
            case '7':
                $erros.='<p>Não foi possível escrever o ficheiro em disco.</p>';
                break;
            case '8':
                $erros.='<p>Problemas com a extensão do ficheiro.</p>';
                break;
            /*case '999':*/
            default:
                $erros.='<p>'.$_FILES['foto']['error'].' - Código de erro não disponível.</p>';
        }
    } else {
        if (empty($_FILES['foto']['tmp_name']) || $_FILES['foto']['tmp_name'] == 'none') {
            $erros.="<p>Não foi possível transferir o ficheiro.</p>";
        } else {
            switch ($_FILES['foto']['type']) {
                case 'image/jpeg':
                case 'image/jpg':
                case 'image/gif':
                case 'image/png':
                    /*  image/svg+xml */
                    $extvalida=true;
                    break;
                default:
                    $extvalida=false;
            }
            if (!$extvalida) {
                $erros.="<p>O formato do ficheiro não é permitido.</p>";
            } else {
                $aleatorio=mt_rand();
                $nome_temp=date('YmdHis').$aleatorio;							
                $extensao=strtolower(strrchr($_FILES['foto']['name'],'.'));
                $foto=$nome_temp.$extensao;
                if (!move_uploaded_file($_FILES['foto']['tmp_name'], "fotos/" . $foto)) {
                    $erros.='<p>Ocorreu um erro no envio do ficheiro, por favor altere o registo.</p>';
                }
            }
        }
    }
    /* Upload de ficheiros - foto 2 */
    if ($_FILES['foto2']['error']!==0) {
        switch($_FILES['foto2']['error']) {
            case '1':
                $erros.='<p>O ficheiro transferido excede a directiva upload_max_filesize do php.ini.</p>';
                break;
            case '2':
                $erros.='<p>O ficheiro transferido excede a directiva MAX_FILE_SIZE do formulário.</p>';
                break;
            case '3':
                $erros.='<p>O ficheiro foi parcialmente transferido.</p>';
                break;
            case '4':
                $erros.='<p>A 2ª foto não foi transferida.</p>';
                break;
            case '6':
                $erros.='<p>Não existe pasta temporária.</p>';
                break;
            case '7':
                $erros.='<p>Não foi possível escrever o ficheiro em disco.</p>';
                break;
            case '8':
                $erros.='<p>Problemas com a extensão do ficheiro.</p>';
                break;
            /*case '999':*/
            default:
                $erros.='<p>'.$_FILES['foto2']['error'].' - Código de erro não disponível.</p>';
        }
    } else {
        if (empty($_FILES['foto2']['tmp_name']) || $_FILES['foto2']['tmp_name'] == 'none') {
            $erros.="<p>Não foi possível transferir o ficheiro.</p>";
        } else {
            switch ($_FILES['foto2']['type']) {
                case 'image/jpeg':
                case 'image/jpg':
                case 'image/gif':
                case 'image/png':
                    /*  image/svg+xml */
                    $extvalida=true;
                    break;
                default:
                    $extvalida=false;
            }
            if (!$extvalida) {
                $erros.="<p>O formato do ficheiro não é permitido.</p>";
            } else {
                $aleatorio=mt_rand();
                $nome_temp=date('YmdHis').$aleatorio;							
                $extensao=strtolower(strrchr($_FILES['foto2']['name'],'.'));
                $foto2=$nome_temp.$extensao;
                if (!move_uploaded_file($_FILES['foto2']['tmp_name'], "fotos/" . $foto2)) {
                    $erros.='<p>Ocorreu um erro no envio do ficheiro, por favor altere o registo.</p>';
                }
            }
        }
    }
//    /* Upload de ficheiros - foto 3 */
    if ($_FILES['foto3']['error']!==0) {
        switch($_FILES['foto3']['error']) {
            case '1':
                $erros.='<p>O ficheiro transferido excede a directiva upload_max_filesize do php.ini.</p>';
                break;
            case '2':
                $erros.='<p>O ficheiro transferido excede a directiva MAX_FILE_SIZE do formulário.</p>';
                break;
            case '3':
                $erros.='<p>O ficheiro foi parcialmente transferido.</p>';
                break;
            case '4':
                $erros.='<p>A 3ª foto não foi transferida.</p>';
                break;
            case '6':
                $erros.='<p>Não existe pasta temporária.</p>';
                break;
            case '7':
                $erros.='<p>Não foi possível escrever o ficheiro em disco.</p>';
                break;
            case '8':
                $erros.='<p>Problemas com a extensão do ficheiro.</p>';
                break;
            /*case '999':*/
            default:
                $erros.='<p>'.$_FILES['foto3']['error'].' - Código de erro não disponível.</p>';
        }
    } else {
        if (empty($_FILES['foto3']['tmp_name']) || $_FILES['foto3']['tmp_name'] == 'none') {
            $erros.="<p>Não foi possível transferir o ficheiro.</p>";
        } else {
            switch ($_FILES['foto3']['type']) {
                case 'image/jpeg':
                case 'image/jpg':
                case 'image/gif':
                case 'image/png':
                    /*  image/svg+xml */
                    $extvalida=true;
                    break;
                default:
                    $extvalida=false;
            }
            if (!$extvalida) {
                $erros.="<p>O formato do ficheiro não é permitido.</p>";
            } else {
                $aleatorio=mt_rand();
                $nome_temp=date('YmdHis').$aleatorio;							
                $extensao=strtolower(strrchr($_FILES['foto3']['name'],'.'));
                $foto3=$nome_temp.$extensao;
                if (!move_uploaded_file($_FILES['foto3']['tmp_name'], "fotos/" . $foto3)) {
                    $erros.='<p>Ocorreu um erro no envio do ficheiro, por favor altere o registo.</p>';
                }
            }
        }
    }
    /* Upload de ficheiros - foto 4 */
    if ($_FILES['foto4']['error']!==0) {
        switch($_FILES['foto4']['error']) {
            case '1':
                $erros.='<p>O ficheiro transferido excede a directiva upload_max_filesize do php.ini.</p>';
                break;
            case '2':
                $erros.='<p>O ficheiro transferido excede a directiva MAX_FILE_SIZE do formulário.</p>';
                break;
            case '3':
                $erros.='<p>O ficheiro foi parcialmente transferido.</p>';
                break;
            case '4':
                $erros.='<p>A 4ª foto não foi transferida.</p>';
                break;
            case '6':
                $erros.='<p>Não existe pasta temporária.</p>';
                break;
            case '7':
                $erros.='<p>Não foi possível escrever o ficheiro em disco.</p>';
                break;
            case '8':
                $erros.='<p>Problemas com a extensão do ficheiro.</p>';
                break;
            /*case '999':*/
            default:
                $erros.='<p>'.$_FILES['foto4']['error'].' - Código de erro não disponível.</p>';
        }
    } else {
        if (empty($_FILES['foto4']['tmp_name']) || $_FILES['foto4']['tmp_name'] == 'none') {
            $erros.="<p>Não foi possível transferir o ficheiro.</p>";
        } else {
            switch ($_FILES['foto4']['type']) {
                case 'image/jpeg':
                case 'image/jpg':
                case 'image/gif':
                case 'image/png':
                    /*  image/svg+xml */
                    $extvalida=true;
                    break;
                default:
                    $extvalida=false;
            }
            if (!$extvalida) {
                $erros.="<p>O formato do ficheiro não é permitido.</p>";
            } else {
                $aleatorio=mt_rand();
                $nome_temp=date('YmdHis').$aleatorio;							
                $extensao=strtolower(strrchr($_FILES['foto4']['name'],'.'));
                $foto4=$nome_temp.$extensao;
                if (!move_uploaded_file($_FILES['foto4']['tmp_name'], "fotos/" . $foto4)) {
                    $erros.='<p>Ocorreu um erro no envio do ficheiro, por favor altere o registo.</p>';
                }
            }
        }
    }
    /* Upload de ficheiros - foto 5 */
    if ($_FILES['foto5']['error']!==0) {
        switch($_FILES['foto5']['error']) {
            case '1':
                $erros.='<p>O ficheiro transferido excede a directiva upload_max_filesize do php.ini.</p>';
                break;
            case '2':
                $erros.='<p>O ficheiro transferido excede a directiva MAX_FILE_SIZE do formulário.</p>';
                break;
            case '3':
                $erros.='<p>O ficheiro foi parcialmente transferido.</p>';
                break;
            case '4':
                $erros.='<p>A 5ª foto não foi transferida.</p>';
                break;
            case '6':
                $erros.='<p>Não existe pasta temporária.</p>';
                break;
            case '7':
                $erros.='<p>Não foi possível escrever o ficheiro em disco.</p>';
                break;
            case '8':
                $erros.='<p>Problemas com a extensão do ficheiro.</p>';
                break;
            /*case '999':*/
            default:
                $erros.='<p>'.$_FILES['foto5']['error'].' - Código de erro não disponível.</p>';
        }
    } else {
        if (empty($_FILES['foto5']['tmp_name']) || $_FILES['foto5']['tmp_name'] == 'none') {
            $erros.="<p>Não foi possível transferir o ficheiro.</p>";
        } else {
            switch ($_FILES['foto5']['type']) {
                case 'image/jpeg':
                case 'image/jpg':
                case 'image/gif':
                case 'image/png':
                    /*  image/svg+xml */
                    $extvalida=true;
                    break;
                default:
                    $extvalida=false;
            }
            if (!$extvalida) {
                $erros.="<p>O formato do ficheiro não é permitido.</p>";
            } else {
                $aleatorio=mt_rand();
                $nome_temp=date('YmdHis').$aleatorio;							
                $extensao=strtolower(strrchr($_FILES['foto5']['name'],'.'));
                $foto5=$nome_temp.$extensao;
                if (!move_uploaded_file($_FILES['foto5']['tmp_name'], "fotos/" . $foto5)) {
                    $erros.='<p>Ocorreu um erro no envio do ficheiro, por favor altere o registo.</p>';
                }
            }
        }
    }
     if ($erros==="") {
        if (!is_numeric($mes) || $mes>13 || $mes<0 ) {
            $erros.="<p>O primeiro campo da data de registo corresponde ao mês (ex: 12).</p>";
        }
        if (!is_numeric($ano) || $ano>date("Y") || $ano<1900 ) {
            $erros.="<p>O segundo campo da data de registo corresponde ao ano (ex: 1999).</p>";
        }
        if ($cod_modelo==0) {
            $erros.="<p>Tem de seleccionar um modelo existente!";
        }
        if (!is_numeric($potencia)) {
            $erros.="<p>A potência só pode conter algarismos!</p>";
        }
        if (!is_numeric($cilindrada)) {
            $erros.="<p>A cilindrada só pode conter algarismos!</p>";
        }
        if (!is_numeric($kms)) {
            $erros.="<p>O campo 'Kms' só pode conter algarismos!</p>";
        }
        if (!preg_match("/^[a-zA-Z ]{4,}$/",$cor)) {
            $erros.="<p>O campo 'cor' só pode conter letras e espaços e ter no mínimo 4 caracteres.</p>";
        }
        if (!preg_match("/^[0-9]$/",$nportas)) {
            $erros.="<p>O campo 'Nº portas' só pode conter um único algarismo!</p>";
        }
        if (!is_numeric($preco) || $preco<0 ) {
            $erros.="<p>O campo 'preço' só pode conter algarismos!</p>";
        }
        
            if ($erros==="") {
            // inserir anúncio:  
            $comandoInserirAnuncio=sprintf("INSERT INTO automoveis(cod_utilizador,titulo,cod_marca,cod_modelo,ano,mes,cilindrada,potencia,combustivel,kms,preco,cor,nportas,descricao,linkanuncio,destaque,foto1,foto2,foto3,foto4,foto5) VALUES(%d,'%s',%d,%d,%d,%d,%d,%d,'%s',%d,%d,'%s',%d,'%s','%s','%s','%s','%s','%s','%s','%s')",$cod_utilizador,$titulo,$cod_marca,$cod_modelo,$ano,$mes,$cilindrada,$potencia,$combustivel,$kms,$preco,$cor,$nportas,$descricao,$linkanuncio,$destaque,$foto,$foto2,$foto3,$foto4,$foto5);
                
                if (!mysqli_query($liga,$comandoInserirAnuncio)) {
                    echo "<p>Erro: ".mysqli_errno($liga) . " - " . mysqli_error($liga)."</p>";
                    exit();
                }
                if (mysqli_affected_rows($liga)===0) {
                    $erros.="<p>Erro na inserção do anúncio.</p>";
                }
            // inserir caracteristicas:         
            $comandoInserirCaracteristicas=sprintf("INSERT INTO caracteristicas(linkanuncio,jantes,direccao,fecho,ac,esp,vidros,computador,livro,farois) VALUES('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s')",$linkanuncio,$jantes,$direccao,$fecho,$ac,$esp,$vidros,$computador,$livro,$farois);
                
                if (!mysqli_query($liga,$comandoInserirCaracteristicas)) {
                    echo "<p>Erro: ".mysqli_errno($liga) . " - " . mysqli_error($liga)."</p>";
                    exit();
                }
                if (mysqli_affected_rows($liga)===0) {
                    $erros.="<p>Erro na inserção do anúncio (características).</p>";
                }
                
}
         header("Location: anuncios.php");
     }}
?>
<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Colocar anúncio</title>
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
        main {
            text-align: center;
        }
        fieldset {
            display: inline-block;
        }
        h1 {
            font-size: 1.5rem;
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
    <h1>Colocar anúncio</h1>
    <form id="formanuncio" method="post" action="<?=$_SERVER['PHP_SELF']?>" enctype="multipart/form-data">
            <p><label for="titulo">Título:</label> <input type="text" id="titulo" name="titulo" maxlength="80" value="<?=($erros!=="") ? $titulo : ''?>"></p>

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
            <p><label for="dataregisto">Data de registo:</label> <input type="text" id="dataregisto1" name="dataregisto1" maxlength="2" value="<?=($erros!=="") ? $_POST['dataregisto1'] : '' ?>"> / <input type="text" id="dataregisto2" name="dataregisto2" maxlength="4" value="<?=($erros!=="") ? $_POST['dataregisto2'] : '' ?>"></p>
            <p><label for="cilindrada">Cilindrada:</label> <input type="text" id="cilindrada" name="cilindrada" maxlength="120" value="<?=($erros!=="") ? $cilindrada : ''?>"></p>
            <p><label for="potencia">Potência:</label> <input type="text" id="potencia" name="potencia" maxlength="30" value="<?=($erros!=="") ? $potencia : ''?>"></p>
            <p><label for="combustivel">Combustível: </label>
                    <select id="combustivel" name="combustivel">
                        <option value="0">Seleccione...</option>
                        <option value="G">Gasolina</option>
                        <option value="D">Diesel</option>
                    </select>
            </p>
            <p><label for="kms">Kms:</label> <input type="text" id="kms" name="kms" maxlength="80" value="<?=($erros!=="") ? $kms : ''?>"></p>
            <p><label for="cor">Cor:</label> <input type="text" id="cor" name="cor" maxlength="80" value="<?=($erros!=="") ? $cor : ''?>"></p>
            <p><label for="cor">Nº portas:</label> <input type="text" id="nportas" name="nportas" maxlength="80" value="<?=($erros!=="") ? $cor : ''?>"></p>
            <p><label for="descricao">Descrição:</label> <textarea id="descricao" name="descricao" rows="4" cols="50"></textarea></p>
            <fieldset>
            <legend>Características:</legend>
            <p><input type="checkbox" id="jantes" name="jantes" value="s"> <label for="jantes">Jantes de liga leve</label></p>
            <p><input type="checkbox" id="direccao" name="direccao" value="s"> <label for="direccao">Direcção assistida</label></p>
            <p><input type="checkbox" id="fecho" name="fecho" value="s"> <label for="fecho">Fecho central</label></p>
            <p><input type="checkbox" id="esp" name="esp" value="s"> <label for="esp">ESP</label></p>
            <p><input type="checkbox" id="ac" name="ac" value="s"> <label for="ac">Ar condicionado</label></p>
            <p><input type="checkbox" id="vidros" name="vidros" value="s"> <label for="vidros">Vidros eléctricos</label></p>
            <p><input type="checkbox" id="computador" name="computador" value="s"> <label for="computador">Computador de bordo</label></p>
            <p><input type="checkbox" id="farois" name="farois" value="s"> <label for="farois">Faróis de nevoeiro</label></p>
            <p><input type="checkbox" id="livro" name="livro" value="s"> <label for="livro">Livro de revisões</label></p>
            </fieldset>
            <p><label for="preco">Preço:</label> <input type="text" id="preco" name="preco" maxlength="80" value="<?=($erros!=="") ? $preco : ''?>"> €</p>
            <p><input type="checkbox" name="destaque" id="destaque" value="s"><label for="destaque">Colocar em destaque?</label></p>
            <p><label for="foto">Foto1:</label> <input type="file" id="foto" name="foto"></p>
            <p><label for="foto2">Foto2:</label> <input type="file" id="foto2" name="foto2"></p>
            <p><label for="foto3">Foto3:</label> <input type="file" id="foto3" name="foto3"></p>
            <p><label for="foto4">Foto4:</label> <input type="file" id="foto4" name="foto4"></p>
            <p><label for="foto5">Foto5:</label> <input type="file" id="foto5" name="foto5"></p>
            <p><input type="submit" id="btSubmit" name="btSubmit" value="Registar anúncio"></p>
        </form>
    <?php
        if ($erros!=="") {
        echo $erros;
        }
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