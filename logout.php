<?php
session_start();
// $_SESSION=array();
unset($_SESSION['id']);
unset($_SESSION['nome']);
$params = session_get_cookie_params();
setcookie(session_name(), '', time() - 42000,
    $params["path"], $params["domain"],
    $params["secure"], $params["httponly"]
);
// session_regenerate_id();
session_destroy();
header("Location: index.php");
?>