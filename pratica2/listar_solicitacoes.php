<?php
include 'db.php';

// Consulta todas as solicitações
$query = "SELECT s.id_solicitacao, s.descricao, s.urgencia, s.status, s.data_abertura, 
                 c.nome AS cliente, f.nome AS funcionario
          FROM Solicitações s
          INNER JOIN Clientes c ON s.id_cliente = c.id_cliente
          LEFT JOIN Funcionarios f ON s.id_funcionario = f.id_funcionario";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listar Solicitações</title>
</head>
<body>
    <h1>Listar Solicitações</h1>
    
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Descrição</th>
                <th>Urgência</th>
                <th>Status</th>
                <th>Data Abertura</th>
                <th>Cliente</th>
                <th>Funcionário</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['id_solicitacao']}</td>
                            <td>{$row['descricao']}</td>
                            <td>{$row['urgencia']}</td>
                            <td>{$row['status']}</td>
                            <td>{$row['data_abertura']}</td>
                            <td>{$row['cliente']}</td>
                            <td>" . ($row['funcionario'] ?? 'N/A') . "</td>
                            <td>
                                <a href='editar_solicitacao.php?id={$row['id_solicitacao']}'>Editar</a> |
                                <a href='excluir_solicitacao.php?id={$row['id_solicitacao']}'>Excluir</a>
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='8'>Nenhuma solicitação encontrada.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>

<?php
$conn->close();
?>
