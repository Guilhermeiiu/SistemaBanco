<?php
// Configuração do banco de dados
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'banco';

// Conexão com o MySQL
$conn = new mysqli($host, $user, $password, $dbname);

// Verifica se a conexão falhou
if ($conn->connect_error) {
    die('Erro ao conectar ao banco de dados: ' . $conn->connect_error);
}
?>

