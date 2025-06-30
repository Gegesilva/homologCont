document.addEventListener('DOMContentLoaded', function () {
    /* campos para inserir serie */
    const form = document.getElementById('FormInputBip');
    const inputBusca = document.getElementById('numero_serie');
    const inputBusca2 = document.getElementById('nContainer');
    const statusMsg = document.getElementById('statusMsg');

    /* campos para inserir modelo */
    const formModelo = document.getElementById('modalForm');
    const inputModelo = document.getElementById('modelo');
    const serieInput = document.getElementById('serieInput');
    const containerInput = document.getElementById('containerInput');
    //const referenciaInput = document.getElementById('referenciaInput');
    const statusMsgMod = document.getElementById('statusMsgMod'); // crie essa div no HTML para mostrar status do modelo

    inputBusca.addEventListener('keypress', function (e) {
        if (e.key === 'Enter' && this.value.trim() !== '') {
            e.preventDefault();
            form.requestSubmit(); // moderno e correto
        }
    });

    /* insere serie */
    form.addEventListener('submit', function (e) {
       // e.preventDefault();

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
            statusMsgMod.innerHTML = '<span style="color: red;">Informe um número de modelo.</span>';
            return;
        }

        const serie = serieInput.value.trim();
        if (serie === '') {
            statusMsgMod.innerHTML = '<span style="color: red;">Série não informada.</span>';
            return;
        }

        const container = containerInput.value.trim();
        if (container === '') {
            statusMsgMod.innerHTML = '<span style="color: red;">cont não informada.'+container+'</span>';
            return;
        }

        /* const referencia = referenciaInput.value.trim();
        if (referencia === '') {
            statusMsgMod.innerHTML = '<span style="color: red;">referencia não informada.</span>';
            return;
        } */

        const formData = new FormData();
        formData.append('modelo', modelo);
        formData.append('serie', serie);
        formData.append('container', container);
        //formData.append('referencia', referencia);

        fetch('../Models/modelo.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(text => {
                console.log(text); // Log para depuração
                statusMsgMod.innerHTML = `<span style="color: green;">${text}</span>`;
                inputModelo.value = '';

                atualizarTabelaModelo();

                document.getElementById('modal').style.display = 'none';
            })
            .catch(() => {
                statusMsgMod.innerHTML = '<span style="color: red;">Erro ao salvar.</span>';
            });
    });


});


/* Aciona modal */

// Abrir modal com delegação
document.addEventListener('click', function (event) {
    if (event.target.classList.contains('abrirModal')) {
        const serie = event.target.getAttribute('data-serie');
        const container = event.target.getAttribute('data-container');

        document.getElementById('serieInput').value = serie;
        document.getElementById('containerInput').value = container;
        document.getElementById('modal').style.display = 'flex';
        

        document.getElementById('modelo').focus(); // Foca no campo de modelo do modal

        console.log('Abrindo modal com:', { serie, container });

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