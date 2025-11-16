@extends('layouts.layoutInterno')

@section('content')
<div class="container">
    <h1>Primera Consulta</h1>

    <form action="{{ route('consulta.store') }}" method="POST">
        @csrf

        <!-- Objetivo de la consulta -->
        <div class="mb-3">
            <label for="objetivo" class="form-label">Objetivo de la consulta</label>
            <textarea name="objetivo" id="objetivo" class="form-control" required></textarea>
        </div>

        <!-- Historia clínica -->
        <h3>Historia Clínica</h3>
        <div class="mb-3">
            <label for="historia_clinica" class="form-label">Acceso a historia clínica</label>
            <textarea name="historia_clinica" id="historia_clinica" class="form-control"></textarea>
        </div>

        <!-- Antecedentes -->
        <h3>Antecedentes</h3>
        <div class="mb-3" style="max-width: 500px;">
            <label for="antecedente-input" class="form-label">Antecedentes (SNOMED/CIE-10)</label>
            <div class="dropdown">
                <input type="text" id="antecedente-input" name="antecedente" class="form-control dropdown-toggle" data-bs-toggle="dropdown" autocomplete="off" placeholder="Escriba al menos 3 letras...">
                <div id="antecedente-dropdown" class="dropdown-menu p-2" style="max-height: 180px; overflow-y: auto; width: 100%;"></div>
            </div>
        </div>




        <!-- Personales -->
        <h3>Datos Personales</h3>
        <div class="mb-3">
            <label for="fuma" class="form-label">¿Fuma? (pack-días)</label>
            <input type="text" name="fuma" id="fuma" class="form-control" placeholder="Ej: 10 cigarros x día x 5 años / 20">
        </div>
        <div class="mb-3">
            <label for="alcohol" class="form-label">Alcohol</label>
            <input type="text" name="alcohol" id="alcohol" class="form-control" placeholder="Frecuencia y tipo">
        </div>
        <div class="mb-3">
            <label for="drogas" class="form-label">Drogas recreativas</label>
            <input type="text" name="drogas" id="drogas" class="form-control">
        </div>
        <div class="mb-3">
            <label for="observaciones" class="form-label">Observaciones</label>
            <textarea name="observaciones" id="observaciones" class="form-control"></textarea>
        </div>

        <!-- Antecedentes familiares -->
        <h3>Antecedentes Familiares</h3>
        <div class="mb-3">
            <label for="familiares" class="form-label">Árbol familiar / patologías</label>
            <textarea name="familiares" id="familiares" class="form-control"></textarea>
        </div>

        <!-- Antecedentes ginecológicos -->
        <h3>Antecedentes Ginecológicos</h3>
        <div class="row">
            <div class="col-md-4">
                <label for="ciclos" class="form-label">Ciclos menstruales</label>
                <input type="text" name="ciclos" id="ciclos" class="form-control" placeholder="Regular/Irregular, duración, sangrado">
            </div>
            <div class="col-md-4">
                <label for="menarca" class="form-label">Menarca (edad)</label>
                <input type="number" name="menarca" id="menarca" class="form-control">
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-3">
                <label for="embarazos" class="form-label">G (embarazos)</label>
                <input type="number" name="embarazos" id="embarazos" class="form-control">
            </div>
            <div class="col-md-3">
                <label for="partos" class="form-label">P (partos)</label>
                <input type="number" name="partos" id="partos" class="form-control">
            </div>
            <div class="col-md-3">
                <label for="abortos" class="form-label">AB (abortos)</label>
                <input type="number" name="abortos" id="abortos" class="form-control">
            </div>
            <div class="col-md-3">
                <label for="ectopicos" class="form-label">CT (ectópicos)</label>
                <input type="number" name="ectopicos" id="ectopicos" class="form-control">
            </div>
        </div>
        <div class="mb-3 mt-3">
            <label for="examen_fisico" class="form-label">Examen físico</label>
            <textarea name="examen_fisico" id="examen_fisico" class="form-control"></textarea>
        </div>

        <!-- Fenotipo -->
        <h3>Fenotipo</h3>
        <div class="row">
            <div class="col-md-4">
                <label for="ojos" class="form-label">Color de ojos</label>
                <input type="text" name="ojos" id="ojos" class="form-control">
            </div>
            <div class="col-md-4">
                <label for="pelo" class="form-label">Color de pelo</label>
                <input type="text" name="pelo" id="pelo" class="form-control">
            </div>
            <div class="col-md-4">
                <label for="tipo_pelo" class="form-label">Tipo de pelo</label>
                <input type="text" name="tipo_pelo" id="tipo_pelo" class="form-control">
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-4">
                <label for="altura" class="form-label">Altura</label>
                <input type="text" name="altura" id="altura" class="form-control">
            </div>
            <div class="col-md-4">
                <label for="complexion" class="form-label">Complexión</label>
                <select name="complexion" id="complexion" class="form-select">
                    <option value="delgada">Delgada</option>
                    <option value="media">Media</option>
                    <option value="robusta">Robusta</option>
                </select>
            </div>
            <div class="col-md-4">
                <label for="etnia" class="form-label">Rasgos étnicos</label>
                <input type="text" name="etnia" id="etnia" class="form-control">
            </div>
        </div>

        <!-- Estudios médicos -->
        <h3>Estudios Médicos</h3>
        <div class="mb-3">
            <label class="form-label">Seleccione estudios</label><br>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="estudios[]" value="prequirurgico" id="prequirurgico">
                <label class="form-check-label" for="prequirurgico">Prequirúrgico</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="estudios[]" value="hormonales" id="hormonales">
                <label class="form-check-label" for="hormonales">Hormonales</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="estudios[]" value="ginecologicos" id="ginecologicos">
                <label class="form-check-label" for="ginecologicos">Ginecológicos</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="estudios[]" value="semen" id="semen">
                <label class="form-check-label" for="semen">Estudio de semen (si aplica)</label>
            </div>
        </div>

        <!-- Botón enviar -->
        <button type="submit" class="btn btn-primary">Guardar Consulta</button>
    </form>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('antecedente-input');
    const dropdown = document.getElementById('antecedente-dropdown');

    input.addEventListener('input', function () {
        const q = input.value;

        if (q.length < 3) {
            dropdown.innerHTML = '';
            dropdown.classList.remove('show');
            return;
        }

        fetch(`/terminos/search?q=${encodeURIComponent(q)}&limit=10`)
            .then(res => res.json())
            .then(data => {
                dropdown.innerHTML = '';
                if (data.rows && data.rows.length > 0) {
                    data.rows.forEach(item => {
                        const card = document.createElement('div');
                        card.classList.add('border', 'rounded', 'p-2', 'mb-2', 'bg-light', 'text-dark', 'cursor-pointer');
                        card.style.cursor = 'pointer';
                        card.textContent = item;
                        card.addEventListener('click', () => {
                            input.value = item;
                            dropdown.innerHTML = '';
                            dropdown.classList.remove('show');
                        });
                        dropdown.appendChild(card);
                    });
                    dropdown.classList.add('show');
                } else {
                    dropdown.classList.remove('show');
                }
            })
            .catch(err => {
                console.error("Error en fetch:", err);
                dropdown.classList.remove('show');
            });
    });

    document.addEventListener('click', function (e) {
        if (!input.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.classList.remove('show');
        }
    });
});
</script>
@endsection








