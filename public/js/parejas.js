document.addEventListener("DOMContentLoaded", function () {

    const selectObjetivo = document.getElementById("objetivo");
    const contenido = document.getElementById("contenido-dinamico-pareja");

    selectObjetivo.addEventListener("change", loadPartial);

    function loadPartial() {
        const sexoPaciente = document.getElementById("genero").value;
        const sexoPareja   = document.getElementById("genero-pareja").value;

        const objetivoTexto = selectObjetivo.options[selectObjetivo.selectedIndex].text.trim();

        console.log("loadPartial() -> genero:", sexoPaciente,
                    ", genero pareja:", sexoPareja,
                    ", objetivo:", objetivoTexto);

        contenido.innerHTML = ""; // limpiar

        let url = null;

        // ✔️ 1) Método ROPA → pareja mujer
        if (objetivoTexto === "Método ROPA") {
            url = "/consulta/pareja_mujer";
        }

        // ✔️ 2) Embarazo con gametos propios → hombre aporta gametos
        else if (objetivoTexto === "Embarazo con gametos propios") {
            url = "/consulta/hombre_gametos";
        }

        // ✔️ 3) Embarazo con esperma donado
        else if (objetivoTexto === "Embarazo con esperma donado") {
            url = "/consulta/hombre_donado";
        }

        // Si hay una vista para cargar
        if (url) {
            console.log("Cargando vista:", url);
            fetch(url)
                .then(r => r.text())
                .then(html => contenido.innerHTML = html)
                .catch(err => console.error("Error cargando vista:", err));
        } else {
            console.log("Ninguna condición coincide. No se cargará parcial.");
        }
    }
});
