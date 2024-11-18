<?php
// Inclui a conexão com o banco de dados
include 'db/conexao.php';

// Inicializa variáveis para mensagens
$mensagem = '';

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipo = filter_input(INPUT_POST, 'tipo', FILTER_SANITIZE_SPECIAL_CHARS);
    $numero_conta = filter_input(INPUT_POST, 'numero_conta', FILTER_VALIDATE_INT);
    $valor = filter_input(INPUT_POST, 'valor', FILTER_VALIDATE_FLOAT);

    // Validações básicas
    if ($tipo && $numero_conta && $valor > 0) {
        // Busca a conta no banco de dados
        $sql_busca = "SELECT saldo FROM contas WHERE numero = ?";
        $stmt_busca = $conn->prepare($sql_busca);
        $stmt_busca->bind_param("i", $numero_conta);
        $stmt_busca->execute();
        $resultado = $stmt_busca->get_result();
        $conta = $resultado->fetch_assoc();

        if ($conta) {
            $saldo_atual = $conta['saldo'];

            if ($tipo === 'deposito') {
                // Realiza o depósito
                $novo_saldo = $saldo_atual + $valor;

                $sql_update = "UPDATE contas SET saldo = ? WHERE numero = ?";
                $stmt_update = $conn->prepare($sql_update);
                $stmt_update->bind_param("di", $novo_saldo, $numero_conta);

                if ($stmt_update->execute()) {
                    $mensagem = "Depósito realizado com sucesso! Novo saldo: R$ " . number_format($novo_saldo, 2, ',', '.');
                } else {
                    $mensagem = "Erro ao realizar o depósito.";
                }
                $stmt_update->close();

            } elseif ($tipo === 'saque') {
                // Valida se há saldo suficiente para o saque
                if ($saldo_atual >= $valor) {
                    $novo_saldo = $saldo_atual - $valor;

                    $sql_update = "UPDATE contas SET saldo = ? WHERE numero = ?";
                    $stmt_update = $conn->prepare($sql_update);
                    $stmt_update->bind_param("di", $novo_saldo, $numero_conta);

                    if ($stmt_update->execute()) {
                        $mensagem = "Saque realizado com sucesso! Novo saldo: R$ " . number_format($novo_saldo, 2, ',', '.');
                    } else {
                        $mensagem = "Erro ao realizar o saque.";
                    }
                    $stmt_update->close();
                } else {
                    $mensagem = "Saldo insuficiente para realizar o saque.";
                }
            }
        } else {
            $mensagem = "Conta não encontrada.";
        }
        $stmt_busca->close();
    } else {
        $mensagem = "Preencha todos os campos corretamente e o valor deve ser maior que zero.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Operações Bancárias</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f9f9f9;
        }
        h1 {
            color: #333;
        }
        form {
            background-color: #fff;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        form input, form select, form button {
            display: block;
            margin: 10px 0;
            padding: 10px;
            width: 100%;
            max-width: 400px;
            font-size: 16px;
        }
        form button {
            background-color: #007BFF;
            color: #fff;
            border: none;
            cursor: pointer;
        }
        form button:hover {
            background-color: #0056b3;
        }
        .message {
            margin-top: 20px;
            font-size: 16px;
            color: green;
        }
        .error {
            color: red;
        }
    </style>
</head>
<body>
    <h1>Operações Bancárias</h1>

    <form method="POST" action="">
        <label for="tipo">Operação:</label>
        <select id="tipo" name="tipo" required>
            <option value="">Selecione</option>
            <option value="deposito">Depósito</option>
            <option value="saque">Saque</option>
        </select>

        <label for="numero_conta">Número da Conta:</label>
        <input type="number" id="numero_conta" name="numero_conta" required>

        <label for="valor">Valor (R$):</label>
        <input type="number" step="0.01" id="valor" name="valor" required>

        <button type="submit">Realizar Operação</button>
    </form>

    <?php if ($mensagem): ?>
        <div class="message <?php echo strpos($mensagem, 'Erro') !== false ? 'error' : ''; ?>">
            <?php echo $mensagem; ?>
        </div>
    <?php endif; ?>

    <a href="index.php">Voltar ao menu principal</a>
</body>
</html>
