<?php
session_start();
$erros="";
if (isset($_POST['btSubmit'])) {
    $email=$_POST['email'];
    $senha=$_POST['senha'];
    if (empty($email) || empty($senha)) {
        $erros="<p>Os dois campos são de preenchimento obrigatório.</p>";
    } else {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $erros.="<p>Email com formato incorrecto.</p>";
        } else {
        require "svliga.php";
        $comando=sprintf("SELECT * FROM utilizadores WHERE email='%s'",$email);
        $resultado=mysqli_query($liga,$comando);
        if (mysqli_num_rows($resultado)===0) {
            // $erros="<p>Acesso negado.</p>";
            $erros="<p>Utilizador inexistente.</p>";
        } else {
            // utilizador existe
            $linha=mysqli_fetch_assoc($resultado);
            switch ($linha['estado']) {
                case "R":
                    $erros="<p>Utilizador não activado. Por favor consulte a sua caixa de correio.</p>";
                    break;
                case "S":
                    $erros="<p>Utilizador suspenso. Por favor fale com o admin.</p>";
                    break;
                case "A":
                    if (password_verify($senha,$linha['senha'])) {
                        $_SESSION['id']=$linha['cod_utilizador'];
                        $_SESSION['nome']=$linha['nome'];
                        $_SESSION['morada']=$linha['morada'];
                        $_SESSION['localidade']=$linha['localidade'];
                        $_SESSION['cp_numerico']=$linha['cp_numerico'];
                        $_SESSION['cp_localidade']=$linha['cp_localidade'];
                        $_SESSION['telefone']=$linha['telefone'];
                        $_SESSION['email']=$linha['email'];
                        $_SESSION['data_registo']=$linha['data_registo'];
                        $_SESSION['estado']=$linha['estado'];
                        if (isset($_SESSION['pag'])) {
                            $pagina=$_SESSION['pag'];
                            unset($_SESSION['pag']);
                            header("Location: ".$pagina);
                            exit();
                        } else {
                            header("Location: index.php");
                            exit();
                        }
                    } else {
                        // $erros="<p>Acesso negado.</p>";
                        $erros="<p>Senha errada.</p>";
                    }
                    break;
                default:
                    $erros="<p>Erro inexistente.</p>";
            }
        }
    }}
}
?>
<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Login</title>
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
        main {
            text-align: center;
        }
        #logo {
            width: 25rem;
            margin: 0 auto;
        }
    </style>
</head>
<body>
<header>
    <a href="index.php"><img src="imagens/logotipo.png" alt="Logotipo Standvirtual" id="logo"></a>
    <hr>
</header>
<main>
<h1>Login</h1>
<?php
    if (isset($erros)) {
        echo $erros;
    }
?>

<form id="formlogin" method="post" action="<?=$_SERVER['PHP_SELF']?>" enctype="multipart/form-data">
    <p><label for="nome">Email:</label> <input type="text" id="email" name="email" maxlength="50"></p>
    <p><label for="senha">Senha: </label> <input type="password" id="senha" name="senha" maxlength="20"></p>
    <p><input type="submit" id="btSubmit" name="btSubmit" value="Validar"></p>
</form>
<p><a href="index.php">Voltar à página principal</a></p>
</main>
</body>
</html>