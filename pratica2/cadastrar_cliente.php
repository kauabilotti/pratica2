<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'] ?? '';
    $cpf = $_POST['cpf'] ?? '';
    $email = $_POST['email'] ?? '';
    $telefone = $_POST['telefone'] ?? '';
    $descricao = $_POST['descricao'] ?? '';
    $urgencia = $_POST['urgencia'] ?? '';
    $id_funcionario = $_POST['id_funcionario'] ?? '';

    // Verifica se todos os campos obrigatórios estão preenchidos
    if (empty($nome) || empty($cpf) || empty($email) || empty($telefone) || empty($descricao) || empty($urgencia) || empty($id_funcionario)) {
        echo "Todos os campos são obrigatórios.";
    } else {
        // Inicia uma transação para inserir o cliente e a solicitação
        $conn->begin_transaction();
        try {
            // Insere o cliente
            $stmt_cliente = $conn->prepare("INSERT INTO Clientes (nome, cpf, email, telefone) VALUES (?, ?, ?, ?)");
            $stmt_cliente->bind_param("ssss", $nome, $cpf, $email, $telefone);
            $stmt_cliente->execute();
            $id_cliente = $stmt_cliente->insert_id;

            // Insere a solicitação associada ao cliente
            $stmt_solicitacao = $conn->prepare("INSERT INTO Solicitações (id_cliente, descricao, urgencia, id_funcionario) VALUES (?, ?, ?, ?)");
            $stmt_solicitacao->bind_param("issi", $id_cliente, $descricao, $urgencia, $id_funcionario);
            $stmt_solicitacao->execute();

            // Confirma a transação
            $conn->commit();
            echo "Cliente e solicitação cadastrados com sucesso!";
        } catch (Exception $e) {
            // Reverte a transação em caso de erro
            $conn->rollback();
            echo "Erro ao cadastrar cliente ou solicitação: " . $e->getMessage();
        }

        $stmt_cliente->close();
        $stmt_solicitacao->close();
    }
}

// Busca a lista de funcionários para exibição no formulário
$funcionarios = $conn->query("SELECT id_funcionario, nome FROM Funcionarios");

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Cliente e Solicitação</title>
</head>
<body>
    <h1>Cadastrar Cliente e Solicitação</h1>
    <form method="POST" action="cadastrar_cliente.php">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" required><br><br>

        <label for="cpf">CPF:</label>
        <input type="text" id="cpf" name="cpf" maxlength="11" required><br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>

        <label for="telefone">Telefone:</label>
        <input type="text" id="telefone" name="telefone" required><br><br>

        <label for="descricao">Descrição da Solicitação:</label>
        <textarea id="descricao" name="descricao" required></textarea><br><br>

        <label for="urgencia">Urgência:</label>
        <select id="urgencia" name="urgencia" required>
            <option value="">Selecione</option>
            <option value="baixa">Baixa</option>
            <option value="média">Média</option>
            <option value="alta">Alta</option>
        </select><br><br>

        <label for="id_funcionario">Funcionário Responsável:</label>
        <select id="id_funcionario" name="id_funcionario" required>
            <option value="">Selecione</option>
            <?php
            // Preenche o dropdown com os funcionários cadastrados
            while ($func = $funcionarios->fetch_assoc()) {
                echo "<option value='{$func['id_funcionario']}'>{$func['nome']}</option>";
            }
            ?>
        </select><br><br>

        <button type="submit">Cadastrar</button>
    </form>
</body>
</html>
