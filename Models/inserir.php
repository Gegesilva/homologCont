<?php
header('Content-Type: text/plain; charset=UTF-8');
include_once "../Config/config.php";


try {
    $conn = new PDO("sqlsrv:server=$server;Database=$base", $usuarioBanco, $SenhaBanco);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_POST['numero_serie']) && isset($_POST['nContainer'])) {
        $numero_serie = trim($_POST['numero_serie']);
        $nContainer = trim($_POST['nContainer']);

        // Verifica se o número de série já existe na tabela HOMOLOG_CONTAINER
        $sql0 = "SELECT COUNT(*) AS total FROM HOMOLOG_CONTAINER WHERE NUMSERIE = :numero_serie";
        $stmt0 = $conn->prepare($sql0);
        $stmt0->execute([':numero_serie' => $numero_serie]);
        $JaBipado = $stmt0->fetchAll(PDO::FETCH_ASSOC);

        if ($JaBipado[0]['total'] > 0) {
            echo "Número de série já cadastrado.";
            exit;
        }

        if ($numero_serie !== '') {
            //$sql = "INSERT INTO SUA_TABELA (ColunaNumeroSerie) VALUES (:numero_serie)";
            $sql = "INSERT INTO HOMOLOG_CONTAINER
                                (
                                    NCONTAINER,
                                    DTCAD,
                                    NUMSERIE,
                                    MODELO1,
                                    MODELO2,
                                    TIPOALT,
                                    GRAVACÃO,
                                    TIPO,
                                    VEND_COMP
                                )VALUES(
                                    :nContainer,
                                    GETDATE(),
                                    ISNULL((SELECT TOP 1 TB02054_NUMSERIE FROM TB02054 WHERE TB02054_NUMSERIE = :numero_serie2), :numero_serie3),
                                    (SELECT TOP 1 TB02054_PRODUTO FROM TB02054 WHERE TB02054_NUMSERIE = :numero_serie),
                                    NULL,
                                    NULL,
                                    NULL,
                                    NULL,
                                    NULL
                                    )";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':numero_serie', $numero_serie);
            $stmt->bindParam(':numero_serie2', $numero_serie);
            $stmt->bindParam(':numero_serie3', $numero_serie);
            $stmt->bindParam(':nContainer', $nContainer);

            if ($stmt->execute()) {
                echo "Número de série salvo com sucesso!";
            } else {
                echo "Falha ao salvar.";
            }
        } else {
            echo "Número de série vazio.";
        }
    } else {
        echo "Dados não recebidos.";
    }
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}
?>