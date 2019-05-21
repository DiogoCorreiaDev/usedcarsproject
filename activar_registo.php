<?php
$email=$_GET['email'];
$token=$_GET['token'];
if (!$token || !$email) {
    header("Location: index.php");
}
$erros="";
require "svliga.php";
$comando=sprintf("SELECT * FROM utilizadores WHERE email='%s'",$email);
$resultado=mysqli_query($liga,$comando);
if (mysqli_num_rows($resultado)===0) {
    echo "<p>Não existe registado o email fornecido.</p>";
} else {
    $linha=mysqli_fetch_assoc($resultado);
    if (empty($linha['token']) && $linha['estado']==="A") {
        $erros="<p>O registo já foi confirmado.</p>";
    } else {
        if ($linha['token']!==$token) {
            $erros="<p>O token está errado.</p>";
        } else {
            $comandoActualiza="UPDATE utilizadores SET token=null, estado='A' WHERE email='".$email."'";
            mysqli_query($liga,$comandoActualiza);
            if (mysqli_affected_rows($liga)!==1) {
                $erros="<p>Ocorreu um erro na confirmação do registo no site. Por favor contacte-nos.</p>";
            }
        }
    }
}
mysqli_free_result($resultado);
mysqli_close($liga);
?>
<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Confirmação de registo</title>
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
    </style>
</head>
<body>
<header>
    <img src="imagens/logotipo.png" alt="Logotipo Standvirtual" id="logo">
    <hr>
</header>
<?php
    if($erros!=="") {
        echo $erros;
        echo "<p><a href='index.php'>Voltar à página principal</a></p>";
    } else {
    echo "<p>O seu registo foi activado com sucesso!</p><p><a href='index.php'>Voltar à página principal</a></p>";
    }
    ?>
</body>
</html>