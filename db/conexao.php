<?php
$servidor = 'localhost';
$usuario = 'root';
$senha = '';
$dbnome = 'banco';

$conexao = new mysqli($servidor, $usuario, $senha, $dbnome);

if ($conexao->connect_error) {
    die('Erro ao conectar ao banco de dados: ' . $conexao->connect_error);
}
?>

