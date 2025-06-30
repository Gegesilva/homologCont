<?php
header('Content-Type: text/plain; charset=UTF-8');
include_once "../Config/config.php";

try {
    // Conexão
    $conn = new PDO("sqlsrv:server=$server;Database=$base", $usuarioBanco, $SenhaBanco);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Verifica se veio id e modelo via POST
    if (isset($_POST['serieInput']) && isset($_POST['modelo'])) {
        $serie = trim($_POST['serieInput']);
        $modelo = trim($_POST['modelo']);

        if ($serie === '' || $modelo === '') {
            echo "ID ou Modelo vazio.";
            exit;
        }


        // Faz o update
        $sqlUpdate = "UPDATE HOMOLOG_CONTAINER SET MODELO2= :modelo WHERE NUMSERIE = :serie";
        $stmtUpdate = $conn->prepare($sqlUpdate);
        $stmtUpdate->bindParam(':modelo', $modelo);
        $stmtUpdate->bindParam(':serie', $serie);

        if ($stmtUpdate->execute()) {
            echo "Modelo atualizado com sucesso!";
        } else {
            echo "Falha ao atualizar.";
        }
    } else {
        echo "Dados não recebidos.";
    }
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}
?>
