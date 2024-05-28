<?php
// Inicie a sessão
session_start();

// Desfaça todas as variáveis da sessão
$_SESSION = array();

// Destrua a sessão.
session_destroy();

// Redirecione para a página de login
header("location: login.php");
exit;
?>
