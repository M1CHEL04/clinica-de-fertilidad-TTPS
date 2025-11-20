document.addEventListener("DOMContentLoaded", () => {

    const fuma = document.getElementById("fuma");
    const campoCantidad = document.getElementById("campo-cantidad");

    const alcohol = document.getElementById("alcohol");
    const alcoholExtra = document.getElementById("alcohol-extra");

    // --- FUMA ---
    fuma.addEventListener("change", () => {
        if (fuma.value === "si") {
            campoCantidad.style.display = "block";
        } else {
            campoCantidad.style.display = "none";
            document.getElementById("cantidad").value = "";
        }
    });

    // --- ALCOHOL ---
    alcohol.addEventListener("change", () => {
        if (alcohol.value === "si") {
            alcoholExtra.style.display = "block";
        } else {
            alcoholExtra.style.display = "none";
            document.getElementById("frecuencia").value = "";
            document.getElementById("bebida-alcohol").value = "";
        }
    });

});