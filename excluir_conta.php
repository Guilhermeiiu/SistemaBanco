<?php
include 'db/conexao.php';


$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numero_conta = filter_input(INPUT_POST, 'numero_conta', FILTER_VALIDATE_INT);

    if ($numero_conta) {
        $sql_verifica = "SELECT * FROM contas WHERE numero = ?";
        $stmt_verifica = $conexao->prepare($sql_verifica);
        $stmt_verifica->bind_param("i", $numero_conta);
        $stmt_verifica->execute();
        $result = $stmt_verifica->get_result();

        if ($result->num_rows > 0) {
            $sql = "DELETE FROM contas WHERE numero = ?";
            $stmt = $conexao->prepare($sql);
            $stmt->bind_param("i", $numero_conta);

            if ($stmt->execute()) {
                $mensagem = "Conta de número $numero_conta excluída com sucesso!";
            } else {
                $mensagem = "Erro ao excluir a conta: " . $conexao->error;
            }

            $stmt->close();
        } else {
            $mensagem = "Conta não encontrada.";
        }

        $stmt_verifica->close();
    } else {
        $mensagem = "Número da conta inválido.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluir Conta</title>
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
            background-color: #FF0000;
            color: #fff;
            border: none;
            cursor: pointer;
        }
        form button:hover {
            background-color: #CC0000;
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
    <h1>Excluir Conta</h1>

    <form method="POST" action="">
        <label for="numero_conta">Número da Conta:</label>
        <input type="number" id="numero_conta" name="numero_conta" required>

        <button type="submit">Excluir Conta</button>
    </form>

    <?php if ($mensagem): ?>
        <div class="message <?php echo strpos($mensagem, 'Erro') !== false || strpos($mensagem, 'inválido') !== false ? 'error' : ''; ?>">
            <?php echo $mensagem; ?>
        </div>
    <?php endif; ?>

    <a href="index.php">Voltar ao menu principal</a>
</body>
</html>
