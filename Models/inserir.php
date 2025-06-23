
<?php
header('Content-Type: application/json');
include_once "../Config/config.php";


try {
    $conn = new PDO("sqlsrv:server=$server;Database=$base", $usuarioBanco, $SenhaBanco);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_POST['numero_serie'])) {
        $numero_serie = trim($_POST['numero_serie']);

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
                                        ':container',
                                        GETDATE(),
                                        :numero_serie,
                                        NULL,
                                        NULL,
                                        NULL,
                                        NULL,
                                        NULL,
                                        NULL)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':numero_serie', $numero_serie);

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
