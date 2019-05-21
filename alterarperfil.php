<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
}
if (isset($_POST['btCancelar'])) {
    header("Location: perfil.php");
}
$erros="";
if (!isset($_POST['btSubmit'])) {
    require "svliga.php";
    $comando="SELECT * FROM utilizadores WHERE cod_utilizador=".$_SESSION['id'];
    $resultado=mysqli_query($liga,$comando);
    if (mysqli_num_rows($resultado)===0) {
        header("Location: index.php");
        exit();
    }
    $linha=mysqli_fetch_assoc($resultado);
    $nome=$linha['nome'];
    $email=$linha['email'];
    $morada=$linha['morada'];
    $localidade=$linha['localidade'];
    $cp=$linha['cp_numerico'];
    $cp_localidade=$linha['cp_localidade'];
    $telefone=$linha['telefone'];

} else {
    $nome=$_POST['nome'];
    $email=$_POST['email'];
    $morada=$_POST['morada'];
    $localidade=$_POST['localidade'];
    $cp=$_POST['cp_numerico'];
    $cp_localidade=$_POST['cp_localidade'];
    $telefone=$_POST['telefone'];
    $senha=$_POST['senha'];
    $rsenha=$_POST['rsenha'];

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
//        if (strlen($telefone)<9) {
        if (!preg_match("/^[1-9]{1}[0-9]{8}$/",$telefone)) {
            $erros.="<p>O campo 'telefone' tem de conter 9 algarismos.</p>";
        }
        // testar senha e rsenha - verificar se são iguais
        if ($senha!==$rsenha) {
           $erros.="<p>O conteúdo dos campos 'senha' e 'repetir senha' têm de ser iguais.</p>"; 
        }
        
        if ($erros==="") {
            require "svliga.php";
            
            if ($_SESSION['email']==$email) {
                
                if (empty($senha) || empty($rsenha)) {
                            $comandoAltera1=sprintf("UPDATE utilizadores SET nome='%s',morada='%s',localidade='%s',cp_numerico='%s',cp_localidade='%s',telefone=%d WHERE cod_utilizador=%d",$nome,$morada,$localidade,$cp,$cp_localidade,$telefone,$_SESSION['id']);

                        if (!mysqli_query($liga,$comandoAltera1)) {
                                echo "<p>Erro: ".mysqli_errno($liga) . " - " . mysqli_error($liga)."</p>";
                                exit();
                            }
                            if (mysqli_affected_rows($liga)===0) {
                                $erros.="<p>Erro na alteração dos dados.</p>";
                            }
                            echo "<p>Os seus dados foram alterados. Será redirecionado para a página inicial.</p>";
                            sleep(5);
                            header("Location: index.php");
                            exit();
                    } else {
                    $senha=password_hash($senha, PASSWORD_DEFAULT);
                    $comandoAltera2=sprintf("UPDATE utilizadores SET nome='%s',morada='%s',localidade='%s',cp_numerico='%s',cp_localidade='%s',telefone=%d,senha='%s' WHERE cod_utilizador=%d",$nome,$morada,$localidade,$cp,$cp_localidade,$telefone,$senha,$_SESSION['id']);

                        if (!mysqli_query($liga,$comandoAltera2)) {
                                echo "<p>Erro: ".mysqli_errno($liga) . " - " . mysqli_error($liga)."</p>";
                                exit();
                            }
                            if (mysqli_affected_rows($liga)===0) {
                                $erros.="<p>Erro na alteração dos dados.</p>";
                            }
                            echo "<p>Os seus dados foram alterados. Será redirecionado para a página inicial.</p>";
                            sleep(5);
                            header("Location: index.php");
                            exit();
                }
            } else {
                //email é diferente do inicial
                $comandoExiste="SELECT email FROM utilizadores WHERE email='".$email."' AND cod_utilizador<>".$_SESSION['id'];
                $resultadoExiste=mysqli_query($liga,$comandoExiste);
                if (mysqli_num_rows($resultadoExiste)===0) {
                    $token=sha1(uniqid());
                        if (empty($senha) || empty($rsenha)) {
                                $comandoAltera3=sprintf("UPDATE utilizadores SET nome='%s',email='%s',morada='%s',localidade='%s',cp_numerico='%s',cp_localidade='%s',telefone=%d,token='%s',estado='R' WHERE cod_utilizador=%d",$nome,$email,$morada,$localidade,$cp,$cp_localidade,$telefone,$token,$_SESSION['id']);

                            if (!mysqli_query($liga,$comandoAltera3)) {
                                    echo "<p>Erro: ".mysqli_errno($liga) . " - " . mysqli_error($liga)."</p>";
                                    exit();
                                }
                                if (mysqli_affected_rows($liga)===0) {
                                    $erros.="<p>Erro na alteração dos dados.</p>";
                                } else {
                        require_once 'swiftmailer-5.x/lib/swift_required.php';

                        $transport = Swift_SmtpTransport::newInstance('smtp.mailtrap.io', 25) 
                            ->setUsername('2a7924e4fecfd7')
                            ->setPassword('0157f27e15f4e1');
                        $mailer = Swift_Mailer::newInstance($transport);
                        $message = Swift_Message::newInstance()
                            // Give the message a subject
                            ->setSubject('Confirmação de alteração')

                            // Set the From address with an associative array
                            ->setFrom(array('no-reply@standvirtual.pt' => 'Standvirtual'))

                            // Set the To addresses with an associative array
                            ->setTo(array($email => $nome))

                            // Give it a body
                            ->setBody('Para reactivar a sua conta copie o endereço seguinte para o seu browser - http://localhost/diogoCorreia/Projecto%202/activar_registo.php?email='.$email.'&token='.$token)

                            // And optionally an alternative body
                            ->addPart('<p>Clique no <a href="http://localhost/diogoCorreia/Projecto%202/activar_registo.php?email='.$email.'&token='.$token.'">link</a> para reactivar a sua conta!</p>', 'text/html');

                            // Optionally add any attachments
                            // ->attach(Swift_Attachment::fromPath('fotos/JoJoMoyes.jpg'));

                        if ($mailer->send($message)==0) {
                            $erros.="<p>O seu registo foi efectuado, mas houve um erro no envio do email de confirmação. Por favor, contacte-nos.</p>";
                        } else {
                            echo "Os seus dados foram alterados. Será redirecionado para a página inicial.";
                            sleep(10);
                            header("Location: index.php");
                            exit();
                        }
                    }
                        } else {
                            $senha=password_hash($senha, PASSWORD_DEFAULT);
                            $comandoAltera4=sprintf("UPDATE utilizadores SET nome='%s',email='%s',morada='%s',localidade='%s',cp_numerico='%s',cp_localidade='%s',telefone=%d,senha='%s',token='%s',estado='R' WHERE cod_utilizador=%d",$nome,$email,$morada,$localidade,$cp,$cp_localidade,$telefone,$senha,$token,$_SESSION['id']);

                            if (!mysqli_query($liga,$comandoAltera4)) {
                                    echo "<p>Erro: ".mysqli_errno($liga) . " - " . mysqli_error($liga)."</p>";
                                    exit();
                                }
                                if (mysqli_affected_rows($liga)===0) {
                                    $erros.="<p>Erro na alteração dos dados.</p>";
                                } else {
                        require_once 'swiftmailer-5.x/lib/swift_required.php';

                        $transport = Swift_SmtpTransport::newInstance('smtp.mailtrap.io', 25) 
                            ->setUsername('2a7924e4fecfd7')
                            ->setPassword('0157f27e15f4e1');
                        $mailer = Swift_Mailer::newInstance($transport);
                        $message = Swift_Message::newInstance()
                            // Give the message a subject
                            ->setSubject('Confirmação de alteração')

                            // Set the From address with an associative array
                            ->setFrom(array('no-reply@standvirtual.pt' => 'Standvirtual'))

                            // Set the To addresses with an associative array
                            ->setTo(array($email => $nome))

                            // Give it a body
                            ->setBody('Para reactivar a sua conta copie o endereço seguinte para o seu browser - http://localhost/diogoCorreia/Projecto%202/activar_registo.php?email='.$email.'&token='.$token)

                            // And optionally an alternative body
                            ->addPart('<p>Clique no <a href="http://localhost/diogoCorreia/Projecto%202/activar_registo.php?email='.$email.'&token='.$token.'">link</a> para reactivar a sua conta!</p>', 'text/html');

                            // Optionally add any attachments
                            // ->attach(Swift_Attachment::fromPath('fotos/JoJoMoyes.jpg'));

                        if ($mailer->send($message)==0) {
                            $erros.="<p>O seu registo foi efectuado, mas houve um erro no envio do email de confirmação. Por favor, contacte-nos.</p>";
                        } else {
                            echo "Os seus dados foram alterados. Será redirecionado para a página inicial.";
                            sleep(10);
                            header("Location: index.php");
                            exit();
                        }}}
                } else {
                $erros.="<p>Já existe um utilizador registado na base de dados com o email fornecido.</p>";
                }}}}}

?>
<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Alterar perfil</title>
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
            text-align: center;
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
        #info {
            width: 100%;
            
        }
    </style>
</head>
<body>
<header>
    <a href="index.php"><img src="imagens/logotipo.png" alt="Logotipo Standvirtual" id="logo"></a>
    <ul id="menu">
        <li><a href="perfil.php">Perfil</a></li>
        <li><a href="logout.php">Sair</a></li>
    </ul>   
    <hr>
</header>
<main>
    <h1>Alterar perfil</h1>
    <form id="formutilizador" method="post" action="<?=$_SERVER['PHP_SELF']?>">
        <p><label for="nome">Nome:</label> <input type="text" id="nome" name="nome" maxlength="80" value="<?=$nome?>"></p>
        <p><label for="email">Email: </label> <input type="text" id="email" name="email" maxlength="50" value="<?=$email?>"></p>
        <p><label for="morada">Morada:</label> <input type="text" id="morada" name="morada" maxlength="120" value="<?=$morada?>"></p>
        <p><label for="localidade">Localidade:</label> <input type="text" id="localidade" name="localidade" maxlength="30" value="<?=$localidade?>"></p>
        <p><label for="cp_numerico">CP:</label> <input type="text" id="cp_numerico" name="cp_numerico" maxlength="8" value="<?=$cp?>"> <input type="text" id="cp_localidade" name="cp_localidade" maxlength="30" value="<?=$cp_localidade?>"></p>
        <p><label for="telefone">Telefone: </label> <input type="text" id="telefone" name="telefone" maxlength="9"  value="<?=$telefone?>"></p>
        <p><label for="senha">Senha: </label> <input type="password" id="senha" name="senha" maxlength="20"></p>
        <p><label for="rsenha">Repetir Senha: </label> <input type="password" id="rsenha" name="rsenha" maxlength="20"></p>
        <p><img id="captcha" src="securimage/securimage_show.php" alt="CAPTCHA Image"></p>
        <p><input type="text" name="captcha_code" size="10" maxlength="6"> <a href="javascript:void(0)" onclick="document.getElementById('captcha').src = 'securimage/securimage_show.php?' + Math.random(); return false">[ Imagem diferente ]</a></p>
        <p><input type="submit" id="btSubmit" name="btSubmit" value="Alterar registo"> <input type="submit" id="btCancelar" name="btCancelar" value="Cancelar"></p>
    </form>
<?php
    if ($erros!=="") {
    echo $erros;
    }
?>
</main>
</body>
</html>