<?php
// Inclui a conexão com o banco de dados
include 'db/conexao.php';

// Inicializa variáveis para resultados e mensagens
$resultados = [];
$mensagem = '';

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $criterio = filter_input(INPUT_POST, 'criterio', FILTER_SANITIZE_SPECIAL_CHARS);
    $valor = filter_input(INPUT_POST, 'valor', FILTER_SANITIZE_SPECIAL_CHARS);

    // Validação básica
    if ($criterio && $valor) {
        // Define a consulta SQL com base no critério selecionado
        if ($criterio === 'nome') {
            $sql = "SELECT * FROM contas WHERE cliente_nome LIKE ?";
            $param = "%" . $valor . "%";
        } elseif ($criterio === 'cpf') {
            $sql = "SELECT * FROM contas WHERE cliente_cpf = ?";
            $param = $valor;
        }

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $param);
        $stmt->execute();
        $resultados = $stmt->get_result();

        if ($resultados->num_rows === 0) {
            $mensagem = "Nenhuma conta encontrada com o critério fornecido.";
        }
    } else {
        $mensagem = "Por favor, preencha todos os campos.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesquisar Contas</title>
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        .message {
            margin-top: 20px;
            font-size: 16px;
            color: red;
        }
    </style>
</head>
<body>
    <h1>Pesquisar Contas</h1>

    <form method="POST" action="">
        <label for="criterio">Pesquisar por:</label>
        <select id="criterio" name="criterio" required>
            <option value="">Selecione</option>
            <option value="nome">Nome</option>
            <option value="cpf">CPF</option>
        </select>

        <label for="valor">Valor:</label>
        <input type="text" id="valor" name="valor" required>

        <button type="submit">Pesquisar</button>
    </form>

    <?php if ($mensagem): ?>
        <div class="message">
            <?php echo $mensagem; ?>
        </div>
    <?php endif; ?>

    <?php if ($resultados && $resultados->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Número da Conta</th>
                    <th>Nome do Cliente</th>
                    <th>CPF</th>
                    <th>Endereço</th>
                    <th>Saldo</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($conta = $resultados->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($conta['numero']); ?></td>
                        <td><?php echo htmlspecialchars($conta['cliente_nome']); ?></td>
                        <td><?php echo htmlspecialchars($conta['cliente_cpf']); ?></td>
                        <td><?php echo htmlspecialchars($conta['cliente_endereco']); ?></td>
                        <td>R$ <?php echo number_format($conta['saldo'], 2, ',', '.'); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <a href="index.php">Voltar ao menu principal</a>
</body>
</html>
