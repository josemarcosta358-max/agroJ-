<?php
session_start();

// Limpa todas as variáveis de sessão e destrói a sessão por completo
$_SESSION = [];
session_destroy();

header('Location: ../index.php');
exit;