document.getElementById('inputBusca').addEventListener('keypress', function (e) {
    if (e.key === 'Enter' && this.value.trim() !== '') {
        e.preventDefault(); // Evita o comportamento padrão se necessário
        document.getElementById('FormInput').submit();
    }
});