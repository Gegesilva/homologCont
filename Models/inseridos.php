<?php
header('Content-Type: application/json');
include_once "../Config/config.php"; // Conexão com o SQL Server

$busca = isset($_GET['busca']) ? trim($_GET['busca']) : '';

try {
    $conn = new PDO("sqlsrv:server=$server;Database=$database", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Consulta segura com parâmetros
    $sql = "SELECT ID, NOME, DETALHE 
            FROM SUA_TABELA
            WHERE NOME LIKE :busca OR ID LIKE :busca";

    $stmt = $conn->prepare($sql);
    $stmt->execute([':busca' => "%$busca%"]);

    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($resultados);
} catch (PDOException $e) {
    echo json_encode(['erro' => $e->getMessage()]);
}
?>
