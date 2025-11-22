document.addEventListener("DOMContentLoaded", function() {
    const objetivo = document.getElementById("objetivo");
    const contenedor = document.getElementById("antecedentePareja");

    objetivo.addEventListener("change", function() {
        const value = parseInt(this.value);
        contenedor.innerHTML = ""; // limpiar

        // NO cargar formulario
        if (value === 4 || value === 5 || !value) {
            return;
        }

        let url = "";

        // GAMETOS PROPIOS → datos del hombre
        if (value === 1) {
            url = "/medico/consulta/hombre_gametos";
        }

        // ESPERMA DONADO → solo fenotipo
        if (value === 2) {
            url = "/medico/consulta/hombre_donado";
        }

        // ROPA → datos mujer
        if (value === 3) {
            url = "/medico/consulta/pareja_mujer";
        }

        // cargar HTML vía AJAX
        fetch(url)
            .then(res => res.text())
            .then(html => contenedor.innerHTML = html);
    });
});
