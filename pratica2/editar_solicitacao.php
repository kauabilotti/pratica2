<?php
include 'db.php';

// Verifica se o ID da solicitação foi passado via GET
if (isset($_GET['id'])) {
    $id_solicitacao = $_GET['id'];

    // Consulta para buscar as informações da solicitação
    $query = "SELECT * FROM Solicitações WHERE id_solicitacao = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_solicitacao);
    $stmt->execute();
    $result = $stmt->get_result();
    $solicitacao = $result->fetch_assoc();
}

// Se o formulário for enviado, atualizar a solicitação
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $descricao = $_POST['descricao'];
    $urgencia = $_POST['urgencia'];
    $status = $_POST['status'];
    $id_funcionario = $_POST['id_funcionario'];

    // Depuração: Mostrar valor de status enviado
    echo "Valor de status enviado: $status<br>";

    // Verifica se o valor de 'status' é válido
    $valid_statuses = ['pendente', 'em andamento', 'finalizada'];
    if (!in_array($status, $valid_statuses)) {
        die("Erro: Status inválido. O status deve ser 'pendente', 'em andamento' ou 'finalizada'.");
    }

    // Atualiza a solicitação no banco de dados
    $updateQuery = "UPDATE Solicitações SET descricao = ?, urgencia = ?, status = ?, id_funcionario = ? WHERE id_solicitacao = ?";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bind_param("ssiii", $descricao, $urgencia, $status, $id_funcionario, $id_solicitacao);
    
    if ($updateStmt->execute()) {
        // Redireciona para a lista de solicitações
        header("Location: listar_solicitacoes.php");
        exit();
    } else {
        echo "Erro ao atualizar a solicitação: " . $updateStmt->error;
    }
}

// Consulta para obter a lista de funcionários
$funcQuery = "SELECT id_funcionario, nome FROM Funcionarios";
$funcResult = $conn->query($funcQuery);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Solicitação</title>
</head>
<body>
    <h1>Editar Solicitação</h1>
    <form method="POST" action="editar_solicitacao.php?id=<?php echo $solicitacao['id_solicitacao']; ?>">
        <label for="descricao">Descrição:</label>
        <textarea id="descricao" name="descricao" required><?php echo $solicitacao['descricao']; ?></textarea><br>

        <label for="urgencia">Urgência:</label>
        <select id="urgencia" name="urgencia" required>
            <option value="baixa" <?php echo $solicitacao['urgencia'] == 'baixa' ? 'selected' : ''; ?>>Baixa</option>
            <option value="média" <?php echo $solicitacao['urgencia'] == 'média' ? 'selected' : ''; ?>>Média</option>
            <option value="alta" <?php echo $solicitacao['urgencia'] == 'alta' ? 'selected' : ''; ?>>Alta</option>
        </select><br>

        <label for="status">Status:</label>
        <select id="status" name="status" required>
            <option value="pendente" <?php echo $solicitacao['status'] == 'pendente' ? 'selected' : ''; ?>>Pendente</option>
            <option value="em andamento" <?php echo $solicitacao['status'] == 'em andamento' ? 'selected' : ''; ?>>Em Andamento</option>
            <option value="finalizada" <?php echo $solicitacao['status'] == 'finalizada' ? 'selected' : ''; ?>>Finalizada</option>
        </select><br>

        <label for="id_funcionario">Funcionário:</label>
        <select id="id_funcionario" name="id_funcionario" required>
            <option value="">Selecione</option>
            <?php while ($row = $funcResult->fetch_assoc()) { ?>
                <option value="<?php echo $row['id_funcionario']; ?>" <?php echo $solicitacao['id_funcionario'] == $row['id_funcionario'] ? 'selected' : ''; ?>>
                    <?php echo $row['nome']; ?>
                </option>
            <?php } ?>
        </select><br>

        <button type="submit">Atualizar Solicitação</button>
    </form>
</body>
</html>

<?php
$conn->close();
?>
