<?php
include 'db/conexao.php';

$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cliente_nome = filter_input(INPUT_POST, 'cliente_nome', FILTER_SANITIZE_SPECIAL_CHARS);
    $cliente_cpf = filter_input(INPUT_POST, 'cliente_cpf', FILTER_SANITIZE_SPECIAL_CHARS);
    $cliente_endereco = filter_input(INPUT_POST, 'cliente_endereco', FILTER_SANITIZE_SPECIAL_CHARS);
    $agencia_id = filter_input(INPUT_POST, 'agencia_id', FILTER_VALIDATE_INT);

    if ($cliente_nome && $cliente_cpf && $cliente_endereco && $agencia_id) {
        $sql_numero_conta = "SELECT MAX(numero) AS max_numero FROM contas";
        $result = $conexao->query($sql_numero_conta);
        $max_numero = $result->fetch_assoc()['max_numero'] ?? 0;
        $novo_numero = $max_numero + 1;

        $sql = "INSERT INTO contas (numero, cliente_nome, cliente_cpf, cliente_endereco, agencia_id, saldo)
                VALUES (?, ?, ?, ?, ?, 0)";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("isssi", $novo_numero, $cliente_nome, $cliente_cpf, $cliente_endereco, $agencia_id);

        if ($stmt->execute()) {
            $mensagem = "Conta criada com sucesso! Número da conta: $novo_numero";
        } else {
            $mensagem = "Erro ao criar a conta: " . $conexao->error;
        }

        $stmt->close();
    } else {
        $mensagem = "Por favor, preencha todos os campos corretamente.";
    }
}

$sql_agencias = "SELECT id, nome FROM agencias";
$agencias = $conexao->query($sql_agencias);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Conta Corrente</title>
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
    <h1>Criar Conta Corrente</h1>

    <form method="POST" action="">
        <label for="cliente_nome">Nome do Cliente:</label>
        <input type="text" id="cliente_nome" name="cliente_nome" required>

        <label for="cliente_cpf">CPF do Cliente:</label>
        <input type="text" id="cliente_cpf" name="cliente_cpf" required>

        <label for="cliente_endereco">Endereço do Cliente:</label>
        <input type="text" id="cliente_endereco" name="cliente_endereco" required>

        <label for="agencia_id">Agência:</label>
        <select id="agencia_id" name="agencia_id" required>
            <option value="">Selecione uma Agência</option>
            <?php while ($agencia = $agencias->fetch_assoc()): ?>
                <option value="<?php echo $agencia['id']; ?>">
                    <?php echo htmlspecialchars($agencia['nome']); ?>
                </option>
            <?php endwhile; ?>
        </select>

        <button type="submit">Criar Conta</button>
    </form>

    <?php if ($mensagem): ?>
        <div class="message <?php echo strpos($mensagem, 'Erro') !== false ? 'error' : ''; ?>">
            <?php echo $mensagem; ?>
        </div>
    <?php endif; ?>

    <a href="index.php">Voltar ao menu principal</a>
</body>
</html>
