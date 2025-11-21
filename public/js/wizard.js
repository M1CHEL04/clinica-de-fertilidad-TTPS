
let currentStep = 1;

function showStep(step) {
    document.querySelectorAll('.step').forEach(s => s.classList.remove('active'));
    document.getElementById("step-" + step).classList.add('active');

    document.querySelectorAll('.nav-step').forEach(n => n.classList.remove('active'));
    document.querySelector('.nav-step[data-step="'+step+'"]').classList.add('active');

    currentStep = step;
}


function nextStep() {

    // --- VALIDACIÓN DEL PASO 1 ---
    if (currentStep === 1) {
        const objetivo = document.getElementById('objetivo');
        const fuma = document.getElementById('fuma');
        const alcohol = document.getElementById('alcohol');
        const droga = document.getElementById('droga');
        const dia = document.getElementById('cant_cigarros');
        const semana = document.getElementById('dias_fuma');
        const anio = document.getElementById('anios_fuma');
        const frecuencia = document.getElementById('frecuencia');
        const bebida = document.getElementById('bebida-alcohol');

        if (!objetivo.value) {
            alert("Debe seleccionar un objetivo.");
            return;
        }
        if (!fuma.value) {
            alert("Debe indicar si fuma.");
            return;
        }else if (fuma.value == "si"){
            if (!dia.value){
                alert("Debe indicar la cantidad por dia")
            }
            if (!semana.value){
                alert("Debe indicar la cantidad de dias por semana")
            }
            if (!anio.value){
                alert("Debe indicar la cantidad de años")
            }
            
        }
        if (!alcohol.value) {
            alert("Debe indicar si consume alcohol.");
            return;
        }else if (alcohol.value == "si"){
             if (!frecuencia.value){
                alert("Debe indicar la frecuencia")
            }
            if (!bebida.value){
                alert("Debe indicar el tipo de bebida")
            }
        }
        if (!droga.value) {
            alert("Debe indicar si consume drogas.");
            return;
        }
    }


    // --- VALIDACIÓN DEL PASO 3 ---
    if (currentStep === 3) {
        const ciclos = document.getElementById('ciclos');
        const duracion = document.getElementById('duracion');
        const menarca = document.getElementById('menarca');
        const caracteristica = document.getElementById('caracteristica');
        const examen_fisico = document.getElementById('examen_fisico');
        const embarazos = document.getElementById('embarazos');
        const partos = document.getElementById('partos');
        const abortos = document.getElementById('abortos');
        const ectopicos = document.getElementById('ectopicos');
                

        if (!ciclos.value) {
            alert("Debe indicar si el ciclo es regular.");
            return;
        }
        if (!duracion.value) {
            alert("Debe completar la duración del ciclo.");
            return;
        }
        if (!caracteristica.value) {
            alert("Debe completar las características del sangrado.");
            return;
        }
        if (!menarca.value) {
            alert("Debe completar la edad de la menarca.");
            return;
        }
        if (!examen_fisico.value.trim()) {
            alert("Debe completar el examen fisico.");
            return;
        }
        if (!embarazos.value) {
            alert("Debe completar la cantidad de embarazos.");
            return;
        }
        if (!abortos.value) {
            alert("Debe completar la cantidad de abortos.");
            return;
        }
        if (!ectopicos.value) {
            alert("Debe completar la cantidad de embarazos ectopicos.");
            return;
        }
        if (!partos.value) {
            alert("Debe completar la cantidad de partos.");
            return;
        }
    }

    // --- VALIDACIÓN DEL PASO 4 (si hicieras una antes del submit) ---
    // if (currentStep === 4) {}

    // PASA AL SIGUIENTE PASO SI TODO ESTÁ BIEN
    if (currentStep < 4) showStep(currentStep + 1);
}

function prevStep() {
    if (currentStep > 1) showStep(currentStep - 1);
}

