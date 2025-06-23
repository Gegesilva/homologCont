<?php

header('Content-type: text/html; charset=ISO-8895-1');
include_once "../Config/config.php";
include_once "../Models/user.php";

/* error_reporting(0); // Desativa a exibição de todos os tipos de erros
ini_set('display_errors', '0'); // Garante que erros não sejam exibidos no navegador */

//validaUsuario($conn);

/* print_r(validaUsuario($conn)); */

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tela Responsiva</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../Public/css/container.css">
</head>

<body class="d-flex flex-column">

    <header class="text-center">
        <h4>Consulta de Dados</h4>
        <div class="input-header">
            <input type="text" class="form-control mx-auto" style="max-width: 500px;" placeholder="Digite sua busca..."
                id="inputBusca">
        </div>
    </header>

    <main>
        <div class="container-tabela">
            <table class="table table-hover table-borderless table-sm">
                <thead>
                    <tr>
                        <th>Container</th>
                        <th>Compra</th>

                    </tr>
                </thead>
                <tbody id="tabelaDados">
                    <!-- Dados preenchidos pelo PHP -->
                    <script>
                        document.getElementById('inputBusca').addEventListener('input', function () {
                            const busca = this.value;

                            fetch('consulta.php?busca=' + encodeURIComponent(busca))
                                .then(response => response.json())
                                .then(dados => {
                                    const tabela = document.getElementById('tabelaDados');
                                    tabela.innerHTML = '';

                                    if (dados.length === 0) {
                                        tabela.innerHTML = '<tr><td colspan="3" class="text-center">Nenhum dado encontrado</td></tr>';
                                        return;
                                    }

                                    dados.forEach(item => {
                                        const linha = `<tr>
                                                            <td>${item.ID}</td>
                                                            <td>${item.NOME}</td>
                                                            <td>${item.DETALHE}</td>
                                                        </tr>`;
                                        tabela.innerHTML += linha;
                                    });
                                })
                                .catch(error => {
                                    console.error('Erro na busca:', error);
                                });
                        });
                    </script>

                </tbody>
            </table>
        </div>

        <button class="btn btn-primary btn-acao mt-2">Ação</button>
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../Public/JS/script.js"></script>
</body>

</html>