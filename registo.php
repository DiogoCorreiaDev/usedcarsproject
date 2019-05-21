<?php
$erros="";
if (isset($_POST['btSubmit'])) {
    echo "<pre>".print_r($_POST,TRUE)."</pre>";
    require "svliga.php";
    $nome=mysqli_real_escape_string($liga,$_POST['nome']);
    $email=mysqli_real_escape_string($liga,$_POST['email']);
    $morada=mysqli_real_escape_string($liga,$_POST['morada']);
    $localidade=mysqli_real_escape_string($liga,$_POST['localidade']);
    $cp=mysqli_real_escape_string($liga,$_POST['cp']);
    $cp_localidade=mysqli_real_escape_string($liga,$_POST['cp_localidade']);
    $telefone=(int)$_POST['telefone'];
    $senha=$_POST['senha'];
    $rsenha=$_POST['rsenha'];
    $token="";
    
    foreach($_POST as $chave=>$valor)  {
        if (empty($_POST[$chave])) {
            $erros.="<p>Todos os campos são de preenchimento obrigatório.</p>";
            break;
        }
    }  
    /* Verificar o Captcha */
    include_once 'securimage/securimage.php';
    $securimage = new Securimage();
    if ($securimage->check($_POST['captcha_code']) == false) {
        $erros.="<p>Código de segurança errado.</p>";
    }   
    if ($erros==="") {
        if (!preg_match("/^[a-zA-Z ]{6,}$/",$nome)) {
            $erros.="<p>O campo 'nome' só pode conter letras e espaços e ter no mínimo 6 caracteres.</p>";
        }
        if (!preg_match("/^[1-9][0-9]{3}-[0-9]{3}$/",$cp)) {
            $erros.="<p>O campo 'cp' tem o formato 9999-999.</p>";
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $erros.="<p>O valor introduzido no campo 'email' não tem o formato correcto.</p>";
        }
        if (!preg_match("/^[1-9]{1}[0-9]{8}$/",$telefone)) {
            $erros.="<p>O campo 'telefone' tem de conter 9 algarismos.</p>";
        }
        // testar senha e rsenha - verificar se são iguais
        if ($senha!==$rsenha) {
           $erros.="<p>O conteúdo dos campos 'senha' e 'repetir senha' têm de ser iguais.</p>"; 
        }
        if ($erros==="") {  
            // Verificar se o email já existe antes de inserir
            $comandoExiste="SELECT email FROM utilizadores WHERE email='".$email."'";
            $resultadoExiste=mysqli_query($liga,$comandoExiste);
            
            if (mysqli_num_rows($resultadoExiste)===0) {
                $token=sha1(uniqid());
                $senha=password_hash($senha, PASSWORD_DEFAULT);    
                
                $comandoInserir=sprintf("INSERT INTO utilizadores(nome,email,morada,localidade,cp_numerico,cp_localidade,telefone,senha,token,estado) VALUES('%s','%s','%s','%s','%s','%s',%d,'%s','%s','%s')",$nome,$email,$morada,$localidade,$cp,$cp_localidade,$telefone,$senha,$token,'R');
                
                if (!mysqli_query($liga,$comandoInserir)) {
                    echo "<p>Erro: ".mysqli_errno($liga) . " - " . mysqli_error($liga)."</p>";
                    exit();
                }
                if (mysqli_affected_rows($liga)===0) {
                    $erros.="<p>Erro na inserção do utilizador.</p>";
                } else {
                    // enviar email
                    
                    require_once 'swiftmailer-5.x/lib/swift_required.php';

                    $transport = Swift_SmtpTransport::newInstance('smtp.mailtrap.io', 25) 
                        ->setUsername('...')
                        ->setPassword('...');
                    $mailer = Swift_Mailer::newInstance($transport);
                    $message = Swift_Message::newInstance()
                        // Give the message a subject
                        ->setSubject('Confirmação de registo')

                        // Set the From address with an associative array
                        ->setFrom(array('no-reply@standvirtual.pt' => 'Standvirtual'))

                        // Set the To addresses with an associative array
                        ->setTo(array($email => $nome))

                        // Give it a body
                        ->setBody('Para activar a sua conta copie o endereço seguinte para o seu browser - http://localhost/diogoCorreia/project/activar_registo.php?email='.$email.'&token='.$token)

                        // And optionally an alternative body
                        ->addPart('<p>Clique no <a href="http://localhost/diogoCorreia/project/activar_registo.php?email='.$email.'&token='.$token.'">link</a> para activar a sua conta!</p>', 'text/html');


                    if ($mailer->send($message)==0) {
                        $erros.="<p>O seu registo foi efectuado, mas houve um erro no envio do email de confirmação. Por favor, contacte-nos.</p>";
                    } else {
                        header("Location: registook.php");
                    }
                
                }
            } else {
                $erros.="<p>Já existe um utilizador registado na base de dados com o email fornecido.</p>";
            }
            mysqli_free_result($resultadoExiste);
        }
    }
}
?>
<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Registo de utilizador</title>
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
        #formutilizador {
            display: inline-block;
        }
        main {
            text-align: center;
        }
        .row {
            display: table;
            width: 100%;
        }
        .col-1-of-4 {
            display: table-cell;
            width: 25%;
        }
        .col-3-of-4 {
            display: table-cell;
            width: 75%;
        }
        .row p {
            margin: 10px 0 10px 0;
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
           <li><a href="login.php">Login</a></li>
        </ul>
    <hr>
</header>
<main>
    <h1>Registo de utilizador</h1>
<?php
    if ($erros!=="") {
        echo $erros;
    }
?>
<form id="formutilizador" method="post" action="<?=$_SERVER['PHP_SELF']?>" enctype="multipart/form-data">
    <div class="row">
        <div class="col-1-of-4">
            <p><label for="nome">Nome:</label></p>
        </div>
        <div class="col-3-of-4">
            <p><input type="text" id="nome" name="nome" maxlength="80" value="<?=($erros!=="") ? $nome : ''?>"></p>
        </div>
    </div>
    <div class="row">
        <div class="col-1-of-4">
            <p><label for="email">Email: </label></p>
        </div>
        <div class="col-3-of-4">
            <p><input type="text" id="email" name="email" maxlength="50" value="<?=($erros!=="") ? $email : ''?>"></p>
        </div>
    </div>
    <div class="row">
        <div class="col-1-of-4">
            <p><label for="morada">Morada:</label></p>
        </div>
        <div class="col-3-of-4">
            <p><input type="text" id="morada" name="morada" maxlength="120" value="<?=($erros!=="") ? $morada : ''?>"></p>
        </div>
    </div>
    <div class="row">
        <div class="col-1-of-4">
            <p><label for="localidade">Localidade:</label></p>
        </div>
        <div class="col-3-of-4">
            <p><input type="text" id="localidade" name="localidade" maxlength="30" value="<?=($erros!=="") ? $localidade : ''?>"></p>
        </div>
    </div>
    <div class="row">
        <div class="col-1-of-4">
            <p><label for="cp1">CP:</label></p>
        </div>
        <div class="col-3-of-4">
            <p><input type="text" id="cp" name="cp" maxlength="8" value="<?=($erros!=="") ? $_POST['cp'] : '' ?>"> <input type="text" id="cp_localidade" name="cp_localidade" maxlength="30" value="<?=($erros!=="") ? $cp_localidade : ''?>"></p>
        </div>
    </div>
    <div class="row">
        <div class="col-1-of-4">
            <p><label for="telefone">Telefone: </label></p>
        </div>
        <div class="col-3-of-4">
            <p><input type="text" id="telefone" name="telefone" maxlength="9"  value="<?=($erros!=="") ? $telefone : ''?>"></p>
        </div>
    </div>
    <div class="row">
        <div class="col-1-of-4">
            <p><label for="senha">Senha: </label></p>
        </div>
        <div class="col-3-of-4">
            <p><input type="password" id="senha" name="senha" maxlength="20"></p>
        </div>
    </div>
    <div class="row">
        <div class="col-1-of-4">
            <p><label for="rsenha">Repetir Senha: </label></p>
        </div>
        <div class="col-3-of-4">
            <p><input type="password" id="rsenha" name="rsenha" maxlength="20"></p>
        </div>
    </div>
    <p><img id="captcha" src="securimage/securimage_show.php" alt="CAPTCHA Image"></p>
    <p><input type="text" name="captcha_code" size="10" maxlength="6"> <a href="">[ Imagem diferente ]</a></p>
    
    <p><input type="submit" id="btSubmit" name="btSubmit" value="Inserir registo"></p>
</form>
</main>

</body>
</html>