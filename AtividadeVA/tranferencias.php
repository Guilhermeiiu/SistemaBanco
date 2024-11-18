<?php
// Inclui a conexão com o banco de dados
include 'db/conexao.php';

// Inicializa variáveis para mensagens
$mensagem = '';

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conta_origem = filter_input(INPUT_POST, 'conta_origem', FILTER_VALIDATE_INT);
    $conta_destino = filter_input(INPUT_POST, 'conta_destino', FILTER_VALIDATE_INT);
    $valor = filter_input(INPUT_POST, 'valor', FILTER_VALIDATE_FLOAT);

    // Validação básica
    if ($conta_origem && $conta_destino && $valor > 0) {
        // Busca os saldos das contas de origem e destino
        $sql_saldo = "SELECT numero, saldo FROM contas WHERE numero IN (?, ?)";
        $stmt = $conn->prepare($sql_saldo);
        $stmt->bind_param("ii", $conta_origem, $conta_destino);
        $stmt->execute();
        $resultados = $stmt->get_result();
        $contas = [];

        while ($linha = $resultados->fetch_assoc()) {
            $contas[$linha['numero']] = $linha['saldo'];
        }

        // Valida se ambas as contas existem
        if (isset($contas[$conta_origem]) && isset($contas[$conta_destino])) {
            // Verifica saldo suficiente na conta de origem
            if ($contas[$conta_origem] >= $valor) {
                // Atualiza o saldo da conta de origem
                $novo_saldo_origem = $contas[$conta_origem] - $valor;
                $sql_update_origem = "UPDATE contas SET saldo = ? WHERE numero = ?";
                $stmt_origem = $conn->prepare($sql_update_origem);
                $stmt_origem->bind_param("di", $novo_saldo_origem, $conta_origem);

                // Atualiza o saldo da conta de destino
                $novo_saldo_destino = $contas[$conta_destino] + $valor;
                $sql_update_destino = "UPDATE contas SET saldo = ? WHERE numero = ?";
                $stmt_destino = $conn->prepare($sql_update_destino);
                $stmt_destino->bind_param("di", $novo_saldo_destino, $conta_destino);

                if ($stmt_origem->execute() && $stmt_destino->execute()) {
                    $mensagem = "Transferência de R$ " . number_format($valor, 2, ',', '.') . " realizada com sucesso!";
                } else {
                    $mensagem = "Erro ao realizar a transferência.";
                }

                $stmt_origem->close();
                $stmt_destino->close();
            } else {
                $mensagem = "Saldo insuficiente na conta de origem.";
            }
        } else {
            $mensagem = "Uma ou ambas as contas não foram encontradas.";
        }

        $stmt->close();
    } else {
        $mensagem = "Preencha todos os campos corretamente. O valor deve ser maior que zero.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transferências</title>
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
        form input, form button {
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
    <h1>Transferências</h1>

    <form method="POST" action="">
        <label for="conta_origem">Conta de Origem:</label>
        <input type="number" id="conta_origem" name="conta_origem" required>

        <label for="conta_destino">Conta de Destino:</label>
        <input type="number" id="conta_destino" name="conta_destino" required>

        <label for="valor">Valor (R$):</label>
        <input type="number" step="0.01" id="valor" name="valor" required>

        <button type="submit">Realizar Transferência</button>
    </form>

    <?php if ($mensagem): ?>
        <div class="message <?php echo strpos($mensagem, 'Erro') !== false ? 'error' : ''; ?>">
            <?php echo $mensagem; ?>
        </div>
    <?php endif; ?>

    <a href="index.php">Voltar ao menu principal</a>
</body>
</html>
