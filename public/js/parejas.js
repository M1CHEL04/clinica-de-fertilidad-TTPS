document.addEventListener("DOMContentLoaded", function () {
    const objetivo = document.getElementById("objetivo");
    const contenedor = document.getElementById("antecedentePareja");
    rol = window.rol || {};
    console.log(rol);
    objetivo.addEventListener("change", function () {
        const value = parseInt(this.value);
        contenedor.innerHTML = ""; // limpiar

        // NO cargar formulario
        if (value === 4 || value === 5 || !value) {
            return;
        }
        let url = "";
        if (rol == 3) url = "/operador";
        else if (rol == 5) url = "/jefe";
        else url = "/medico";

        // GAMETOS PROPIOS → datos del hombre
        if (value === 1) {
            url += "/consulta/partials/hombre-gametos";
        }

        // ESPERMA DONADO → solo fenotipo
        if (value === 2) {
            url += "/consulta/partials/hombre-donado";
        }

        // ROPA → datos mujer
        if (value === 3) {
            url += "/consulta/partials/pareja-mujer";
        }

        // cargar HTML vía AJAX
        fetch(url)
            .then((res) => res.text())
            .then((html) => {
                contenedor.innerHTML = html;
                // Pequeño delay para asegurar que el DOM esté completamente renderizado
                setTimeout(() => {
                    inicializarFormParejas();
                }, 50);
            });
    });
});
