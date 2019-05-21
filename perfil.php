<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
}
require "svliga.php";
$erros="";

?>
<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Untitled Document</title>
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
            margin: 0 auto;
            text-align: center;
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
        #info {
            width: 38rem; /* =600px */
            display: table;
            margin: 0 auto;
        }
        .col-30 {
            display: table-cell;
            width: 30%;
            text-align: center;
            font-weight: bold;
        }
        .col-70 {
            display: table-cell;
            width: 70%;
        }
    </style>
</head>
<body>
<header>
    <a href="index.php"><img src="imagens/logotipo.png" alt="Logotipo Standvirtual" id="logo"></a>
    <ul id="menu">
        <li><a href="perfil.php">Perfil</a></li><li><a href="anuncios.php">Anúncios</a></li><li><a href="logout.php">Sair</a></li><li><a href="acesso_reservado.php">Acesso Reservado</a></li>
    </ul>   
    <hr>
</header>
<main>
<h1>Perfil de utilizador</h1>
    <div id="info">
        <div class="col-30">
            <p>Nome:</p>
            <p>Email:</p>
            <p>Morada:</p>
            <p>CP:</p>
            <p>Telefone:</p>
            <p>Data de registo:</p>
        </div>
        <div class="col-70">
            <p><?=$_SESSION['nome']?></p>
            <p><?=$_SESSION['email']?></p>
            <p><?=$_SESSION['morada']?>&nbsp;<?=$_SESSION['localidade']?></p>
            <p><?=$_SESSION['cp_numerico']?>&nbsp;<?=$_SESSION['cp_localidade']?></p>
            <p><?=$_SESSION['telefone']?></p>
            <p><?=$_SESSION['data_registo']?></p>
        </div>
    </div>
<p><a href="alterarperfil.php">Alterar Perfil</a></p>
<p><a href="anuncios.php">Anúncios</a></p>
<p><a href="index.php">Página principal</a></p>
</main>
</body>
</html>