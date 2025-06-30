document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('FormInputBip');
    const inputBusca = document.getElementById('numero_serie');
    const inputBusca2 = document.getElementById('nContainer');
    const statusMsg = document.getElementById('statusMsg');

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
});


/* Aciona modal */

// Abrir modal com delegação
document.addEventListener('click', function (event) {
    if (event.target.classList.contains('abrirModal')) {
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