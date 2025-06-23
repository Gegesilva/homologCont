/* Faz o submmit no formulario ao pressionar enter */

document.getElementById('numero_serie').addEventListener('keypress', function (e) {
    if (e.key === 'Enter' && this.value.trim() !== '') {
        e.preventDefault(); // Evita o comportamento padrão se necessário
        document.getElementById('FormInputBip').submit();
    }
});


// form-submit.js

document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('FormInputBip');
    const inputBusca = document.getElementById('numero_serie');
    const statusMsg = document.getElementById('statusMsg');

    form.addEventListener('submit', function(e) {
        e.preventDefault();

        const numeroSerie = inputBusca.value.trim();

        if (numeroSerie === '') {
            statusMsg.innerHTML = '<span style="color: red;">Informe um número de série.</span>';
            return;
        }

        // Cria um objeto FormData para enviar via fetch
        const formData = new FormData();
        formData.append('numero_serie', numeroSerie);

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