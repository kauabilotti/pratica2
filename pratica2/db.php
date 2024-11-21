<?php
// Configurações do banco de dados
$servername = "localhost";
$username = "root";
$password = "root";
$database = "pratica2";

// Criar conexão
$conn = new mysqli($servername, $username, $password, $database);

// Verificar conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}
?>
