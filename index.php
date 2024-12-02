<?php
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
            font-family: 'Roboto', Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            color: #333;
            line-height: 1.6;
        }

        /* Cabeçalho */
        h1 {
            background-color: #007BFF;
            color: #fff;
            text-align: center;
            padding: 20px 0;
            margin: 0;
            font-size: 2.2em;
        }

        /* Saudação */
        #saudacao {
            text-align: center;
            font-size: 1.2em;
            color: #555;
            margin: 20px 0;
        }

        /* Container principal */
        .content {
            max-width: 800px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .content h2 {
            margin-top: 0;
            color: #007BFF;
            font-size: 1.8em;
        }

        .content p {
            font-size: 1em;
            color: #666;
        }

        /* Menu */
        .menu {
            text-align: center;
            margin-top: 20px;
        }

        .menu a {
            text-decoration: none;
            padding: 10px 20px;
            margin: 10px;
            background-color: #007BFF;
            color: #fff;
            border-radius: 50px;
            display: inline-block;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 123, 255, 0.2);
        }

        .menu a:hover {
            background-color: #0056b3;
            transform: scale(1.05);
            box-shadow: 0 6px 10px rgba(0, 86, 179, 0.3);
        }

        /* Responsividade */
        @media (max-width: 768px) {
            .menu a {
                display: block;
                margin: 10px auto;
                width: 80%;
            }}
    </style>
</head>
<body>
    <h1>Sistema Bancário</h1>
    
    <div id="saudacao"></div>

    <div class="content">
        <h2>BankALL</h2>
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

    <script>
        window.onload = function() {
            const horario = new Date().getHours(); // Obtém a hora atual
            let saudacao;

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