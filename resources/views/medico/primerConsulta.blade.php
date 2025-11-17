@extends('layouts.layoutInterno')

@section('content')
<style>
    .step { display: none; }
    .step.active { display: block; }

    .wizard-nav {
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
    }

    .wizard-nav span {
        padding: 8px 15px;
        border-radius: 6px;
        background: #d8d8d8;
        font-weight: bold;
    }

    .wizard-nav .active {
        background: #007bff;
        color: white;
    }
</style>

<div class="container">
    <h1>Primera Consulta</h1>

    <div class="wizard-nav">
        <span class="nav-step active" data-step="1">Datos iniciales</span>
        <span class="nav-step" data-step="2">Antecedentes familiares</span>
        <span class="nav-step" data-step="3">Antecedentes ginecológicos</span>
        <span class="nav-step" data-step="4">Fenotipo</span>
        <span class="nav-step" data-step="5">Estudios médicos</span>
    </div>

    <form action="{{ route('consulta.store') }}" method="POST">
        @csrf

        {{-- STEP 1 --}}
        <div class="step active" id="step-1">

            <h2>Objetivo de la consulta</h2>
            <div class="mb-3">
                <select name="objetivo" id="objetivo" class="form-select">
                    <option value="Embarazo con gametos propios">Gametos propios</option>
                    <option value="Embarazo con esperma donado">Esperma donado</option>
                    <option value="Metodo ROPA">ROPA</option>
                </select>

            </div>


            <h3>Datos personales</h3>

            {{-- FUMA --}}
            <div class="mb-3">
                <label for="fuma" class="form-label">¿Fuma?</label>
                <select name="fuma" id="fuma" class="form-select">
                    <option value="">Seleccione...</option>
                    <option value="si">Sí</option>
                    <option value="no">No</option>
                </select>
            </div>

            <div class="mb-3" id="campo-cantidad" style="display:none;">
                <label class="form-label">Pack/días</label>

                <div class="row">
                    <div class="col-md-4">
                        <input type="number" min="0" id="cant_cigarros" class="form-control" placeholder="Cigarros por día">
                    </div>

                    <div class="col-md-4">
                        <input type="number" min="0" id="dias_fuma" class="form-control" placeholder="Días por semana">
                    </div>

                    <div class="col-md-4">
                        <input type="number" min="0" id="anios_fuma" class="form-control" placeholder="Años fumando">
                    </div>
                </div>

                <!-- Campo final que se enviará al backend -->
                <input type="hidden" name="cantidad" id="cantidad">
                
                <small class="text-muted" id="preview_cantidad" style="display:block; margin-top: 5px;"></small>
            </div>

            {{-- ALCOHOL --}}
            <div class="mb-3">
                <label for="alcohol" class="form-label">¿Consume alcohol?</label>
                <select name="alcohol" id="alcohol" class="form-select">
                    <option value="">Seleccione...</option>
                    <option value="si">Sí</option>
                    <option value="no">No</option>
                </select>
            </div>

            <div id="alcohol-extra" style="display:none;">
                <div class="mb-3">
                    <label for="frecuencia" class="form-label">Frecuencia</label>
                    <input type="number" min="0" id="frecuencia" class="form-control" placeholder="dias a la semana">
                    
                </div>

                <div class="mb-3">
                    <label for="bebida-alcohol" class="form-label">Qué bebida</label>
                    <input type="text" name="bebida-alcohol" id="bebida-alcohol" class="form-control" placeholder="Ej: cerveza, vodka">
                </div>
            </div>

            {{-- DROGAS --}}
            <div class="mb-3">
                <label for="drogas" class="form-label">Drogas recreativas</label>
                <select name="droga" id="droga" class="form-select">
                    <option value="">Seleccione...</option>
                    <option value="si">Sí</option>
                    <option value="no">No</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="observaciones" class="form-label">Observaciones</label>
                <textarea name="observaciones" id="observaciones" class="form-control"></textarea>
            </div>

            <br><br>
            <button type="button" onclick="nextStep()" class="btn btn-primary float-end">Siguiente →</button>
        </div>

        {{-- STEP 2 --}}
        <div class="step" id="step-2">

            <h3>Antecedentes familiares</h3>
            <div id="familiares-container"></div>
            <button type="button" id="add-familiar" class="btn btn-secondary mt-2">Añadir familiar</button>

            <br>
            <button type="button" onclick="prevStep()" class="btn btn-secondary">← Atrás</button>
            <button type="button" onclick="nextStep()" class="btn btn-primary float-end">Siguiente →</button>
        
        </div>


        {{-- STEP 3 --}}
        <div class="step" id="step-3">

            <h3>Antecedentes ginecológicos</h3>
            <div class="row">
                <div class="col-md-4">
                    <label for="ciclos" class="form-label">Ciclos menstruales</label>
                    <input type="text" name="ciclos" id="ciclos" class="form-control" placeholder="regular/irregular">
                </div>
                <div class="col-md-4">
                    <label for="duracion" class="form-label">Duracion del ciclo</label>
                    <input type="text" name="duracion" id="duracion" class="form-control" placeholder="Ej: 21 dias">
                </div>
                <div class="col-md-4">
                    <label for="caracteristica" class="form-label">Caracteristica del sangrado</label>
                    <input type="text" name="caracteristica" id="caracteristica" class="form-control" placeholder="Ej: abundante, leve">
                </div>
                <div class="col-md-4">
                    <label for="menarca" class="form-label">Menarca (edad)</label>
                    <input type="number" name="menarca" id="menarca" class="form-control" placeholder="Ej: 12 años">
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-3">
                    <label for="embarazos" class="form-label">G</label>
                    <input type="number" name="embarazos" class="form-control" placeholder="Cantidad de embarazos">
                </div>
                <div class="col-md-3">
                    <label for="partos" class="form-label">P</label>
                    <input type="number" name="partos" class="form-control" placeholder="Cantidad de partos">
                </div>
                <div class="col-md-3">
                    <label for="abortos" class="form-label">AB</label>
                    <input type="number" name="abortos" class="form-control" placeholder="Cantidad de abortos">
                </div>
                <div class="col-md-3">
                    <label for="ectopicos" class="form-label">CT</label>
                    <input type="number" name="ectopicos" class="form-control" placeholder="Cantidad de embarazos ectopicos">
                </div>
            </div>

            <div class="mt-3">
                <label for="examen_fisico" class="form-label">Examen físico</label>
                <textarea name="examen_fisico" class="form-control"></textarea>
            </div>
            <br>
                <button type="button" onclick="prevStep()" class="btn btn-secondary">← Atrás</button>
                <button type="button" onclick="nextStep()" class="btn btn-primary float-end">Siguiente →</button>
        </div>

        {{-- STEP 4 --}}
        <div class="step" id="step-4">

            <h3 class="mt-4">Fenotipo</h3>
            <div class="row">
                <div class="col-md-4">
                    <label for="color-ojos" class="form-label">Color de ojos</label>
                    <input type="text" name="color-ojos" id="color-ojos" class="form-control" placeholder="Ej: marron, azul">
                </div>
                <div class="col-md-4">
                    <label for="color-pelo" class="form-label">Color de pelo</label>
                    <input type="text" name="color-pelo" id="color-pelo" class="form-control" placeholder="Ej: rubio, morocho">
                </div>
                <div class="col-md-4">
                    <label for="tipo-pelo" class="form-label">Tipo de pelo</label>
                    <input type="text" name="tipo-pelo" id="tipo-pelo" class="form-control" placeholder="Ej: rizado, lacio">
                </div>
                <div class="col-md-4">
                    <label for="altura" class="form-label">Altura</label>
                    <input type="text" name="altura" id="altura" class="form-control" placeholder="Ej: 1,72">
                </div>
                <div class="col-md-4">
                    <label for="complexion" class="form-label">Complexion</label>
                    <input type="text" name="complexion" id="complexion" class="form-control" placeholder="Ej: delgada, robusta">
                </div>
                <div class="col-md-4">
                    <label for="rasgos-etnicos" class="form-label">Rasgos etnicos</label>
                    <input type="text" name="rasgos-etnicos" id="rasgos-etnicos" class="form-control" placeholder="Ej: asiatico">
                </div>
            </div>

            <br>
            <button type="button" onclick="prevStep()" class="btn btn-secondary">← Atrás</button>
            <button type="button" onclick="nextStep()" class="btn btn-primary float-end">Siguiente →</button>
        </div>



        {{-- STEP 5 --}}
        <div class="step" id="step-5">

            <h3>Estudios Médicos</h3>

            {{-- Reutilizo tus selects dinámicos --}}

            <div class="mb-3">
                <label>Estudios Ginecológicos</label>
                <div class="d-flex">
                    <select id="ginecologicos-select" class="form-select me-2">
                        @foreach($ginecologicos as $estudio)
                            <option value="{{ $estudio['id'] }}">{{ $estudio['nombre'] }}</option>
                        @endforeach
                    </select>
                    <button type="button" id="add-ginecologico" class="btn btn-outline-primary">Agregar</button>
                </div>
                <ul id="ginecologicos-list" class="list-group mt-2"></ul>
            </div>

            <div class="mb-3">
                <label>Estudios Hormonales</label>
                <div class="d-flex">
                    <select id="hormonales-select" class="form-select me-2">
                        @foreach($hormonales as $estudio)
                            <option value="{{ $estudio['id'] }}">{{ $estudio['nombre'] }}</option>
                        @endforeach
                    </select>
                    <button type="button" id="add-hormonal" class="btn btn-outline-primary">Agregar</button>
                </div>
                <ul id="hormonales-list" class="list-group mt-2"></ul>
            </div>

            <div class="mb-3">
                <label>Prequirúrgicos</label>
                <div class="d-flex">
                    <select id="prequirurgicos-select" class="form-select me-2">
                        @foreach($prequirurgicos as $estudio)
                            <option value="{{ $estudio['id'] }}">{{ $estudio['nombre'] }}</option>
                        @endforeach
                    </select>
                    <button type="button" id="add-prequirurgico" class="btn btn-outline-primary">Agregar</button>
                </div>
                <ul id="prequirurgicos-list" class="list-group mt-2"></ul>
            </div>

            <div class="mb-3">
                <label>Estudios de Semen</label>
                <div class="d-flex">
                    <select id="semen-select" class="form-select me-2">
                        @foreach($semen as $estudio)
                            <option value="{{ $estudio['id'] }}">{{ $estudio['nombre'] }}</option>
                        @endforeach
                    </select>
                    <button type="button" id="add-semen" class="btn btn-outline-primary">Agregar</button>
                </div>
                <ul id="semen-list" class="list-group mt-2"></ul>
            </div>

            <br>
            <button type="button" onclick="prevStep()" class="btn btn-secondary">← Atrás</button>
            <button type="submit" class="btn btn-success float-end">Guardar consulta</button>
        </div>

    </form>
</div>

@endsection


@section('scripts')
<script src="{{ asset('js/terminos.js') }}"></script>
<script src="{{ asset('js/familiares.js') }}"></script>
<script src="{{ asset('js/estudios.js')}}"></script>
<script src="{{ asset('js/wizard.js')}}"></script>
<script>
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
</script>
<script>
    document.addEventListener("DOMContentLoaded", () => {

    const fuma = document.getElementById("fuma");
    const campoCantidad = document.getElementById("campo-cantidad");

    const cant = document.getElementById("cant_cigarros");
    const dias = document.getElementById("dias_fuma");
    const anios = document.getElementById("anios_fuma");
    const cantidadFinal = document.getElementById("cantidad");
    const preview = document.getElementById("preview_cantidad");

    // Mostrar/ocultar según Selección de Fuma
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

    // Función que arma el texto final
    function actualizarPackDias() {
        if (cant.value && dias.value && anios.value) {
            const texto = `${cant.value} cigarros x ${dias.value} días x ${anios.value} años / 20`;
            cantidadFinal.value = texto;
            preview.textContent = "->" + texto;
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
</script>
@endsection
