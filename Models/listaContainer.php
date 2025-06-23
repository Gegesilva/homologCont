<?php
header('Content-Type: application/json');

// Debug provisório:
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once "../Config/config.php"; // Caminho correto da configuração

try {
    $conn = new PDO("sqlsrv:server=$server;Database=$base", $usuarioBanco, $SenhaBanco);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT 
                TB02002_NCONTEINER AS NumContainer,
                TB02002_CODIGO AS Compra
            FROM TB02002
            WHERE TB02002_NCONTEINER IS NOT NULL
            AND TB02002_DATA BETWEEN DATEADD(DAY, -90, GETDATE()) AND GETDATE()";

    $stmt = $conn->query($sql);
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($resultados);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'mensagem' => $e->getMessage()
    ]);
}
?>
