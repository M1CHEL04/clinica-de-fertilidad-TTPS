
let currentStep = 1;

function showStep(step) {
    document.querySelectorAll('.step').forEach(s => s.classList.remove('active'));
    document.getElementById("step-" + step).classList.add('active');

    document.querySelectorAll('.nav-step').forEach(n => n.classList.remove('active'));
    document.querySelector('.nav-step[data-step="'+step+'"]').classList.add('active');

    currentStep = step;
}

function nextStep() {
    if (currentStep < 4) showStep(currentStep + 1);
}

function prevStep() {
    if (currentStep > 1) showStep(currentStep - 1);
}
