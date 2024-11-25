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
        #saudacao {
            font-size: 18px;
            color: #555;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <h1>Sistema Bancário</h1>
    
    <!-- Div para exibir a saudação dinâmica -->
    <div id="saudacao"></div>

    <div class="content">
        <h2>Bem-vindo ao BankALL</h2>
        <p>Use o menu abaixo para acessar as funcionalidades do sistema.</p>
    </div>
    <div class="menu">
        <a href="criar_agencia.php">Criar Agência</a>
        <a href="criar_conta.php">Criar Conta Corrente</a>
        <a href="operacoes.php">Operações (Depósitos/Saques)</a>
      
        <a href="pesquisar.php">Pesquisar Conta</a>
        <a href="listar_contas.php">Listar Contas</a>
        <a href="tranferencias.php">Transferência</a>
        <a href="excluir_conta.php">Excluir Conta</a>
        <a href="excluir_agencia.php">Excluir Agência</a>
    </div>

    <!-- Script para exibir a saudação baseada no horário -->
    <script>
        window.onload = function() {
            const horario = new Date().getHours(); // Obtém a hora atual
            let saudacao;

            // Define a saudação baseada no horário
            if (horario < 12) {
                saudacao = "Bom dia!";
            } else if (horario < 18) {
                saudacao = "Boa tarde!";
            } else {
                saudacao = "Boa noite!";
            }

            // Exibe a saudação na div com id 'saudacao'
            document.getElementById("saudacao").innerText = saudacao + " Bem-vindo ao Sistema Bancário.";
        }
    </script>
</body>
</html>
