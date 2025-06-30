document.addEventListener('DOMContentLoaded', function () {
    /* campos para inserir serie */
    const form = document.getElementById('FormInputBip');
    const inputBusca = document.getElementById('numero_serie');
    const inputBusca2 = document.getElementById('nContainer');
    const statusMsg = document.getElementById('statusMsg');

    /* campos para inserir modelo */
    const formModelo = document.getElementById('modalForm');
    const inputModelo = document.getElementById('modelo');
    const statusMsgMod = document.getElementById('statusMsgMod'); // crie essa div no HTML para mostrar status do modelo

    inputBusca.addEventListener('keypress', function (e) {
        if (e.key === 'Enter' && this.value.trim() !== '') {
            e.preventDefault();
            form.requestSubmit(); // moderno e correto
        }
    });

    /* insere serie */
    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const numeroSerie = inputBusca.value.trim();
        if (numeroSerie === '') {
            statusMsg.innerHTML = '<span style="color: red;">Informe um número de série.</span>';
            return;
        }

        const formData = new FormData();
        formData.append('numero_serie', numeroSerie);
        formData.append('nContainer', inputBusca2.value.trim());

        fetch('../Models/inserir.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(text => {
                statusMsg.innerHTML = `<span style="color: green;">${text}</span>`;
                inputBusca.value = '';
            })
            .catch(() => {
                statusMsg.innerHTML = '<span style="color: red;">Erro ao salvar.</span>';
            });
    });


    /* insere modelos */
    formModelo.addEventListener('submit', function (e) {
        e.preventDefault();
    
        const modelo = inputModelo.value.trim();
        if (modelo === '') {
            statusMsg.innerHTML = '<span style="color: red;">Informe um número de modelo.</span>';
            return;
        }
    
        const formData = new FormData();
        formData.append('modelo', modelo);
    
        fetch('../Models/modelo.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(text => {
                statusMsg.innerHTML = `<span style="color: green;">${text}</span>`;
                inputModelo.value = '';
    
                // Aqui você pode atualizar a tabela, por exemplo:
                atualizarTabelaModelo();
    
                // Também pode fechar o modal se quiser
                document.getElementById('modal').style.display = 'none';
            })
            .catch(() => {
                statusMsg.innerHTML = '<span style="color: red;">Erro ao salvar.</span>';
            });
    });
    
});


/* Aciona modal */

// Abrir modal com delegação
document.addEventListener('click', function (event) {
    if (event.target.classList.contains('abrirModal')) {
        const serie = event.target.getAttribute('data-serie');
        document.getElementById('serieInput').value = serie;
        document.getElementById('modal').style.display = 'flex';
    }
});

// Fechar modal
document.getElementById('fecharModal').addEventListener('click', function () {
    document.getElementById('modal').style.display = 'none';
});

// Submeter form do modal
document.getElementById('modalForm').addEventListener('submit', function (e) {
    e.preventDefault(); // evita reload
    // Aqui você processa o form
    console.log('Form enviado!');
    document.getElementById('modal').style.display = 'none';
});