<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'] ?? '';
    $email = $_POST['email'] ?? '';
    $cargo = $_POST['cargo'] ?? '';

    // Verifica se os campos obrigatórios estão preenchidos
    if (empty($nome) || empty($email) || empty($cargo)) {
        echo "Todos os campos são obrigatórios.";
    } else {
        // Insere o funcionário no banco de dados
        $stmt = $conn->prepare("INSERT INTO Funcionarios (nome, email, cargo) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $nome, $email, $cargo);

        if ($stmt->execute()) {
            echo "Funcionário cadastrado com sucesso!";
        } else {
            echo "Erro ao cadastrar funcionário: " . $stmt->error;
        }

        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Funcionário</title>
</head>
<body>
    <h1>Cadastrar Funcionário</h1>
    <form method="POST" action="cadastrar_funcionario.php">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" required><br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>

        <label for="cargo">Cargo:</label>
        <input type="text" id="cargo" name="cargo" required><br><br>

        <button type="submit">Cadastrar Funcionário</button>
    </form>
</body>
</html>
