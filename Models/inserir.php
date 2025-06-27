
<?php
header('Content-Type: text/plain; charset=UTF-8');
include_once "../Config/config.php";


try {
    $conn = new PDO("sqlsrv:server=$server;Database=$base", $usuarioBanco, $SenhaBanco);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_POST['numero_serie']) && isset($_POST['nContainer'])) {
        $numero_serie = trim($_POST['numero_serie']);
        $nc = trim($_POST['nContainer']);

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
                                        :numero_serie,
                                        NULL,
                                        NULL,
                                        NULL,
                                        NULL,
                                        NULL,
                                        NULL)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':numero_serie', $numero_serie);
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
