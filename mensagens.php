<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Mensagens</title>
</head>
<body>
<?php
    
if (empty ($_GET['msg'])) {
    echo "<p> Mensagem desconhecida</p>";
} else {
    switch($_GET['msg']) {
        case 1:
            echo "<p>Registo inserido</p>";
            break;
        case 2:
            echo "<p>Registo confirmado</p>";
            break;
        case 3:
            echo "<p>Dados Alterados</p>";
            break;
        default:
            echo "<p>Mensagem desconhecida</p>";
    }
}

?>
<p><a href="index.php">Voltar à página principal</a></p>
</body>
</html>