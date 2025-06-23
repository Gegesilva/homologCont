<?php
header('Content-Type: application/json');
include_once "../Config/config.php";

$busca = isset($_GET['busca']) ? trim($_GET['busca']) : '';

try {
    $conn = new PDO("sqlsrv:server=$server;Database=$base", $usuarioBanco, $SenhaBanco);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT
                TB02055_NUMSERIE AS Serie,
                TB02002_NCONTEINER AS Container,
                TB01010_REFERENCIA AS Modelo
            FROM TB02002
            LEFT JOIN TB02003 ON TB02003_CODIGO = TB02002_CODIGO
            LEFT JOIN TB02055 ON TB02055_CODIGO = TB02002_CODIGO 
                AND TB02055_PRODUTO = TB02003_PRODUTO 
                AND TB02055_TABELA = 'TB02002'
            LEFT JOIN TB01010 ON TB01010_CODIGO = TB02003_PRODUTO
            WHERE TB02002_NCONTEINER = :busca";

    $stmt = $conn->prepare($sql);
    $stmt->execute([':busca' => $busca]);

    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($resultados);
} catch (PDOException $e) {
    echo json_encode(['erro' => $e->getMessage()]);
}
?>
