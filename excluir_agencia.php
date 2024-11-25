<?php
// Inclui a conexão com o banco de dados
include 'db/conexao.php';

// Inicializa variáveis
$mensagem = '';

// Verifica se o número da agência foi passado pela URL
if (isset($_GET['numero_agencia'])) {
    $numero_agencia = filter_input(INPUT_GET, 'numero_agencia', FILTER_VALIDATE_INT);

    if ($numero_agencia) {
        // Verifica se a agência existe no banco
        $sql_verifica_agencia = "SELECT * FROM agencias WHERE numero = ?";
        $stmt_verifica_agencia = $conn->prepare($sql_verifica_agencia);
        $stmt_verifica_agencia->bind_param("i", $numero_agencia);
        $stmt_verifica_agencia->execute();
        $result_agencia = $stmt_verifica_agencia->get_result();

        if ($result_agencia->num_rows > 0) {
            // Verifica se existem contas associadas a essa agência
            $sql_verifica_contas = "SELECT * FROM contas WHERE agencia_id = ?";
            $stmt_verifica_contas = $conn->prepare($sql_verifica_contas);
            $stmt_verifica_contas->bind_param("i", $numero_agencia);
            $stmt_verifica_contas->execute();
            $result_contas = $stmt_verifica_contas->get_result();

            if ($result_contas->num_rows > 0) {
                // Se houver contas associadas, exibe uma mensagem informando o problema
                $mensagem = "Não é possível excluir a agência de número $numero_agencia porque existem contas associadas a ela.";
            } else {
                // Caso não haja contas associadas, procede com a exclusão da agência
                $sql_excluir = "DELETE FROM agencias WHERE numero = ?";
                $stmt_excluir = $conn->prepare($sql_excluir);
                $stmt_excluir->bind_param("i", $numero_agencia);

                if ($stmt_excluir->execute()) {
                    $mensagem = "Agência de número $numero_agencia excluída com sucesso!";
                } else {
                    $mensagem = "Erro ao excluir a agência: " . $conn->error;
                }

                $stmt_excluir->close();
            }

            $stmt_verifica_contas->close();
        } else {
            $mensagem = "Agência não encontrada.";
        }

        $stmt_verifica_agencia->close();
    } else {
        $mensagem = "Número da agência inválido.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluir Agência</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f9f9f9;
        }
        h1 {
            color: #333;
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
    <h1>Excluir Agência</h1>

    <?php if ($mensagem): ?>
        <div class="message <?php echo strpos($mensagem, 'Erro') !== false || strpos($mensagem, 'inválido') !== false ? 'error' : ''; ?>">
            <?php echo $mensagem; ?>
        </div>
    <?php endif; ?>

    <form method="GET" action="excluir_agencia.php">
        <label for="numero_agencia">Número da Agência:</label>
        <input type="number" id="numero_agencia" name="numero_agencia" required>

        <button type="submit">Excluir Agência</button>
    </form>

    <a href="index.php">Voltar ao menu principal</a>
</body>
</html>
