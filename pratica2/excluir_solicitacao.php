<?php
include 'db.php';

// Verifica se o ID da solicitação foi passado via GET
if (isset($_GET['id'])) {
    $id_solicitacao = $_GET['id'];

    // Exclui a solicitação
    $query = "DELETE FROM Solicitações WHERE id_solicitacao = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_solicitacao);
    $stmt->execute();

    // Redireciona para a lista de solicitações
    header("Location: listar_solicitacoes.php");
    exit();
} else {
    echo "ID da solicitação não encontrado.";
}

$conn->close();
?>
