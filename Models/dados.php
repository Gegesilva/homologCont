<?php
header('Content-Type: application/json');
include_once "../Config/config.php";

$busca = isset($_GET['busca']) ? trim($_GET['busca']) : '';

try {
    $conn = new PDO("sqlsrv:server=$server;Database=$base", $usuarioBanco, $SenhaBanco);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);



    $sql = "-- lista dos numeros de serie bipados
                WITH HOMOLOG AS (
                    SELECT DISTINCT NUMSERIE, MODELO1, MODELO2, NCONTAINER FROM HOMOLOG_CONTAINER
                ),

                -- numeros de serie da compra
                COMPRA AS (
                    SELECT DISTINCT 
                        TB02055_NUMSERIE AS Serie, 
                        TB02055_CODIGO,
                        TB02003_PRODUTO AS Modelo,
                        TB01010_REFERENCIA AS Referencia,
                        TB02002_NCONTEINER AS Container
                    FROM TB02002
                    JOIN TB02003 ON TB02003_CODIGO = TB02002_CODIGO
                    JOIN TB02055 ON TB02055_CODIGO = TB02002_CODIGO
                        AND TB02055_PRODUTO = TB02003_PRODUTO
                        AND TB02055_TABELA = 'TB02002'
                    LEFT JOIN TB01010 ON TB01010_CODIGO = TB02003_PRODUTO
                    WHERE TB02002_NCONTEINER = :busca
                ),

                -- BipExist: flag por serie
                BIP_EXIST AS (
                    SELECT DISTINCT 
                        c.Serie,
                        1 AS BipExist
                    FROM COMPRA c
                    JOIN HOMOLOG h ON c.Serie = h.NUMSERIE
                ),

                -- BipNotExist: flag por modelo
                BIP_NOT_EXIST AS (
                    SELECT DISTINCT 
                        c.Modelo,
                        1 AS BipNotExist
                    FROM COMPRA c
                    JOIN HOMOLOG h 
                        ON c.Modelo = h.MODELO1
                    WHERE NOT EXISTS (
                        SELECT 1 
                        FROM TB02055 t
                        JOIN TB02003 i ON i.TB02003_PRODUTO = t.TB02055_PRODUTO
                        WHERE t.TB02055_CODIGO = c.TB02055_CODIGO
                        AND t.TB02055_NUMSERIE = h.NUMSERIE
                        AND t.TB02055_PRODUTO = i.TB02003_PRODUTO
                    )
                ),

                -- Extras: numeros bipados que não existem na compra
                EXTRAS AS (
                    SELECT DISTINCT
                        h.NUMSERIE AS Serie,
                        NCONTAINER AS Container,
                        NULL AS Modelo,
						MODELO2 AS Modelo2,
                        TB01010_REFERENCIA AS Referencia,
                        0 AS BipExist,
                        1 AS BipNotExist
                    FROM HOMOLOG h
					LEFT JOIN TB01010 ON TB01010_CODIGO = MODELO2
                    WHERE NOT EXISTS (
                        SELECT 1 FROM TB02055 t WHERE t.TB02055_NUMSERIE = h.NUMSERIE
                    )
                )

                -- resultado final
                SELECT DISTINCT
                    c.Serie,
                    c.Container,
                    c.Modelo,
					null Modelo2,
                    c.Referencia,
                    ISNULL(be.BipExist, 0) AS BipExist,
                    ISNULL(bne.BipNotExist, 0) AS BipNotExist
                FROM COMPRA c
                LEFT JOIN BIP_EXIST be ON be.Serie = c.Serie
                LEFT JOIN BIP_NOT_EXIST bne ON bne.Modelo = c.Modelo

                UNION ALL

                SELECT
                    e.Serie,
                    e.Container,
                    ISNULL(e.Modelo, e.Modelo2) Modelo,
					e.Modelo2,
                    e.Referencia,
                    e.BipExist,
                    e.BipNotExist
                FROM EXTRAS e
                WHERE e.Serie IS NOT NULL

                ORDER BY BipNotExist DESC
                ";

    $stmt = $conn->prepare($sql);
    $stmt->execute([':busca' => $busca]);

    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($resultados);
} catch (PDOException $e) {
    echo json_encode(['erro' => $e->getMessage()]);
}
?>
