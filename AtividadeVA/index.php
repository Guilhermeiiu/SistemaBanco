<?php
// Inicia a sessão para manter os dados enquanto o sistema está em execução
session_start();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema Bancário</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f9f9f9;
        }
        h1 {
            color: #333;
        }
        .menu {
            margin-bottom: 20px;
        }
        .menu a {
            text-decoration: none;
            padding: 10px 20px;
            margin: 5px;
            background-color: #007BFF;
            color: #fff;
            border-radius: 5px;
            display: inline-block;
        }
        .menu a:hover {
            background-color: #0056b3;
        }
        .content {
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <h1>Sistema Bancário</h1>

    <div class="menu">
        <a href="criar_agencia.php">Criar Agência</a>
        <a href="criar_conta.php">Criar Conta Corrente</a>
        <a href="operacoes.php">Operações (Depósitos/Saques)</a>
        <a href="pesquisar.php">Pesquisar Conta</a>
        <a href="listar_contas.php">Listar Contas</a>
        <a href="excluir_conta.php">Excluir Conta</a>
    </div>

    <div class="content">
        <h2>Bem-vindo ao Sistema Bancário</h2>
        <p>Use o menu acima para acessar as funcionalidades do sistema.</p>
    </div>
</body>
</html>
