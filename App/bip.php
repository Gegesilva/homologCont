<?php

header('Content-type: text/html; charset=ISO-8895-1');
include_once "../Config/config.php";
include_once "../Models/user.php";

/* error_reporting(0); // Desativa a exibição de todos os tipos de erros
ini_set('display_errors', '0'); // Garante que erros não sejam exibidos no navegador */

//validaUsuario($conn);

/* print_r(validaUsuario($conn)); */

/* mantem a variavel nContainer enquanto não houver post */
session_start();
if (isset($_POST['nContainer'])) {
    $_SESSION['nContainer'] = htmlspecialchars($_POST['nContainer']);
}

$nContainer = isset($_SESSION['nContainer']) ? $_SESSION['nContainer'] : '';


?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DATABIT</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../Public/css/container.css">
</head>

<body class="d-flex flex-column">

    <header class="text-center">
        <h4>Container: <?= $nContainer ?></h4>
        <div class="input-header">
            <form method="post" id="FormInputBip">
                <input type="text" class="form-control mx-auto" style="max-width: 500px;" placeholder="Numero de Série"
                    id="numero_serie">
                <input type="hidden" id="nContainer" name="nContainer" value="<?= $nContainer ?>">
            </form>
        </div>

        <div id="statusMsg" name="statusMsg" class="text-center mt-2 statusMsg"></div>
    </header>
    <!-- envia o codigo do container para a requisição -->
    <input type="hidden" id="containerIndex" value="<?= $nContainer ?>">
    <main>
        <div class="container-tabela">
            <table class="table table-hover table-borderless table-sm">
                <thead>
                    <tr>
                        <th>Serie</th>
                        <th>Referencia</th>
                        <th>Modelo</th>
                    </tr>
                </thead>
                <tbody id="tabelaDados">
                    <!-- Dados preenchidos pelo PHP -->
                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            const busca = document.getElementById('containerIndex').value;

                            fetch('../Models/dados.php?busca=' + encodeURIComponent(busca))
                                .then(response => response.json())
                                .then(dados => {
                                    const tabela = document.getElementById('tabelaDados');
                                    tabela.innerHTML = '';

                                    if (dados.length === 0) {
                                        tabela.innerHTML = '<tr><td colspan="3" class="text-center">Nenhum dado encontrado</td></tr>';
                                        return;
                                    }

                                    dados.forEach(item => {
                                        /* Define as cores das linhas de acordo com a existencia e hobilita ou desabilita o botão modelo */
                                        let cor = '';
                                        let botaoModelo = '';
                                        if (item.BipExist == '1') {
                                            cor = `#98FB98`;
                                            botaoModelo = ``;
                                        } else if (item.BipNotExist == '1') {
                                            cor = `#FA8072`;
                                            botaoModelo = `<button class="abrirModal" data-serie="${item.Serie}">Inserir Modelo</button>`;
                                        } else {
                                            cor = ``;
                                            botaoModelo = ``;
                                        }

                                        const linha = `
                                                    <tr>
                                                        <td style="background-color: ${cor};">${item.Serie}</td>
                                                        <td style="background-color: ${cor};">${item.Referencia}</td>
                                                        <td style="background-color: ${cor};">${item.Modelo}</td>
                                                        <td>
                                                            ${botaoModelo}
                                                        </td>
                                                    </tr>
                                                    `;
                                        tabela.innerHTML += linha;
                                    });
                                })
                                .catch(error => {
                                    console.error('Erro na busca:', error);
                                });

                            console.log('Busca realizada com sucesso:', busca);
                        });
                    </script>


                </tbody>
            </table>
        </div>

        <button onclick="volta('index.php')" class="btn btn-primary btn-acao mt-2">Voltar</button>
        <button class="btn btn-primary btn-acao mt-2">Fim</button>
    </main>

    

    <!-- modal escondido para alterar modelo -->
    <div id="modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%;
                        background-color: rgba(0,0,0,0.5); justify-content: center; align-items: center;">
        <div style="background-color: white; padding: 20px; border-radius: 5px;">
            <form id="modalForm">
                <input type="hidden" name="serie" id="serieInput" value="">
                <label>Digite algo:</label>
                <input type="text" name="campo" id="modelo" required>
                <script></script>
                <button type="submit">Enviar</button>
                <button type="button" id="fecharModal">Cancelar</button>
            </form>
        </div>
    </div>

    <!-- Bootstrap / JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../Public/JS/bip.js" charset="utf-8"></script>
    <script src="../Public/JS/script.js" charset="utf-8"></script>

</body>

</html>