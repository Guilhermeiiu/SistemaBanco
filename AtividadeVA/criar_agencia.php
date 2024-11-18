<?php
// Inclui a conexão com o banco de dados
include 'db/conexao.php';

// Inicializa variáveis para mensagens
$mensagem = '';

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_SPECIAL_CHARS);
    $numero = filter_input(INPUT_POST, 'numero', FILTER_VALIDATE_INT);
    $endereco = filter_input(INPUT_POST, 'endereco', FILTER_SANITIZE_SPECIAL_CHARS);

    // Validação básica dos campos
    if ($nome && $numero && $endereco) {
        // Insere os dados no banco de dados
        $sql = "INSERT INTO agencias (nome, numero, endereco) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sis", $nome, $numero, $endereco);

        if ($stmt->execute()) {
            $mensagem = "Agência criada com sucesso!";
        } else {
            $mensagem = "Erro ao criar a agência: " . $conn->error;
        }

        $stmt->close();
    } else {
        $mensagem = "Por favor, preencha todos os campos corretamente.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Agência</title>
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
    <h1>Criar Agência</h1>

    <form method="POST" action="">
        <label for="nome">Nome da Agência:</label>
        <input type="text" id="nome" name="nome" required>

        <label for="numero">Número da Agência:</label>
        <input type="number" id="numero" name="numero" required>

        <label for="endereco">Endereço:</label>
        <input type="text" id="endereco" name="endereco" required>

        <button type="submit">Criar Agência</button>
    </form>

    <?php if ($mensagem): ?>
        <div class="message <?php echo strpos($mensagem, 'Erro') !== false ? 'error' : ''; ?>">
            <?php echo $mensagem; ?>
        </div>
    <?php endif; ?>

    <a href="index.php">Voltar ao menu principal</a>
</body>
</html>
