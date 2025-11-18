function toggleObraSocial(show) {
    const details = document.getElementById('obra-social-details');
    const obraSocialSelect = document.getElementById('obra_social');
    const numeroAfiliado = document.getElementById('numero_afiliado');

    if (show) {
        details.style.display = 'grid';
        obraSocialSelect.required = true;
        numeroAfiliado.required = true;
    } else {
        details.style.display = 'none';
        obraSocialSelect.required = false;
        numeroAfiliado.required = false;
        obraSocialSelect.value = '';
        numeroAfiliado.value = '';
    }
}
        // Mostrar campos si ya hay una selección previa (para old values)
document.addEventListener('DOMContentLoaded', function() {
    const radioSi = document.querySelector('input[name="posee_obra_social"][value="si"]');
    if (radioSi && radioSi.checked) {
            toggleObraSocial(true);
    }
});