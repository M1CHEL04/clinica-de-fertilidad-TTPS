document.addEventListener("DOMContentLoaded", () => {

    const fuma = document.getElementById("fuma");
    const campoCantidad = document.getElementById("campo-cantidad");

    const cant = document.getElementById("cant_cigarros");
    const dias = document.getElementById("dias_fuma");
    const anios = document.getElementById("anios_fuma");
    const cantidadFinal = document.getElementById("cantidad");
    const preview = document.getElementById("preview_cantidad");

    // Mostrar/ocultar según selección de Fuma
    fuma.addEventListener("change", () => {
        if (fuma.value === "si") {
            campoCantidad.style.display = "block";
        } else {
            campoCantidad.style.display = "none";
            cant.value = "";
            dias.value = "";
            anios.value = "";
            cantidadFinal.value = "";
            preview.textContent = "";
        }
    });

    // Función que calcula el pack-año
    function actualizarPackDias() {
        const c = parseFloat(cant.value) || 0;
        const d = parseFloat(dias.value) || 0;
        const a = parseFloat(anios.value) || 0;

        if (c && d && a) {
            const resultado = (c * d * a) / 20;
            cantidadFinal.value = resultado.toFixed(2); // valor numérico que se envía al backend
            preview.textContent = `-> ${c} cigarros x ${d} días x ${a} años / 20 = ${resultado.toFixed(2)}`;
        } else {
            cantidadFinal.value = "";
            preview.textContent = "";
        }
    }

    // Actualizar cada vez que el usuario escribe
    [cant, dias, anios].forEach(input => {
        input.addEventListener("input", actualizarPackDias);
    });

});
