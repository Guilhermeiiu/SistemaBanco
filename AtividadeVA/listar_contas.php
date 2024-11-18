<?php
// Inclui a conexão com o banco de dados
include 'db/conexao.php';

// Consulta para buscar todas as contas junto com o nome da agência
$sql = "
    SELECT 
        contas.numero AS numero_conta,
        contas.cliente_nome,
        contas.cliente_cpf,
        contas.saldo,
        agencias.nome AS nome_agencia
    FROM contas
    JOIN agencias ON contas.agencia_id = agencias.id
    ORDER BY contas.numero ASC
";
$resultados = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listar Contas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f9f9f9;
        }
        h1 {
            color: #333;
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
    <h1>Listar Todas as Contas</h1>

    <?php if ($resultados && $resultados->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Número da Conta</th>
                    <th>Nome do Cliente</th>
                    <th>CPF</th>
                    <th>Saldo</th>
                    <th>Agência</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($conta = $resultados->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($conta['numero_conta']); ?></td>
                        <td><?php echo htmlspecialchars($conta['cliente_nome']); ?></td>
                        <td><?php echo htmlspecialchars($conta['cliente_cpf']); ?></td>
                        <td>R$ <?php echo number_format($conta['saldo'], 2, ',', '.'); ?></td>
                        <td><?php echo htmlspecialchars($conta['nome_agencia']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="message">Nenhuma conta encontrada.</div>
    <?php endif; ?>

    <a href="index.php">Voltar ao menu principal</a>
</body>
</html>
