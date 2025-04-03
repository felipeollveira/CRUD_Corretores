<?php
$host = 'localhost:3306';
$user = 'root';
$pass = '';
$db   = 'corretores';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Definir charset para utf8
$conn->set_charset("utf8");
?>