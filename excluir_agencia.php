<?php
include 'db/conexao.php';

$mensagem = '';

if (isset($_GET['numero_agencia'])) {
    $numero_agencia = filter_input(INPUT_GET, 'numero_agencia', FILTER_VALIDATE_INT);

    if ($numero_agencia) {
        $sql_verifica_agencia = "SELECT * FROM agencias WHERE numero = ?";
        $stmt_verifica_agencia = $conexao->prepare($sql_verifica_agencia);
        $stmt_verifica_agencia->bind_param("i", $numero_agencia);
        $stmt_verifica_agencia->execute();
        $result_agencia = $stmt_verifica_agencia->get_result();

        if ($result_agencia->num_rows > 0) {
            $sql_verifica_contas = "SELECT * FROM contas WHERE agencia_id = (SELECT id FROM agencias WHERE numero = ?)";
            $stmt_verifica_contas = $conexao->prepare($sql_verifica_contas);
            $stmt_verifica_contas->bind_param("i", $numero_agencia);
            $stmt_verifica_contas->execute();
            $result_contas = $stmt_verifica_contas->get_result();

            if ($result_contas->num_rows > 0) {
                $mensagem = "Não é possível excluir a agência de número $numero_agencia porque existem contas associadas a ela.";
            } else {
                try {
                    $sql_excluir = "DELETE FROM agencias WHERE numero = ?";
                    $stmt_excluir = $conexao->prepare($sql_excluir);
                    $stmt_excluir->bind_param("i", $numero_agencia);

                    if ($stmt_excluir->execute()) {
                        $mensagem = "Agência de número $numero_agencia excluída com sucesso!";
                    }
                } catch (mysqli_sql_exception $e) {
                    if (strpos($e->getMessage(), 'a foreign key constraint fails') !== false) {
                        $mensagem = "Não é possível excluir a agência de número $numero_agencia porque existem contas associadas a ela.";
                    } else {
                        $mensagem = "Erro ao excluir a agência: " . $e->getMessage();
                    }
                }
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
