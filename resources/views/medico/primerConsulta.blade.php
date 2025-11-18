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

    </div>

    <form action="{{ route('consulta.store') }}" method="POST">
        @csrf

        {{-- STEP 1 --}}
        <div class="step active" id="step-1">

           <h2>Objetivo de la consulta</h2>

            <div class="mb-3">
                <select name="objetivo" id="objetivo" class="form-select">
                    <option value="">Seleccione...</option>

                    @foreach ($objetivos as $obj)
                        <option value="{{ $obj->id }}">{{ $obj->nombre }}</option>
                    @endforeach
                </select>
            </div>



            <h3>Datos personales</h3>

            <h3>Antecedentes</h3>
            <div class="mb-3">
                <label for="antecedente" class="form-label">Antecedente</label>
                <div class="dropdown">
                    <input type="text" id="antecedente-input" class="form-control dropdown-toggle" data-bs-toggle="dropdown" placeholder="Ingresar 3 letras...">
                    <div id="antecedente-dropdown" class="dropdown-menu p-2" style="max-height: 180px; overflow-y: auto;"></div>
                </div>
            </div>

            

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
                    <select name="ciclos" id="ciclos" class="form-select">
                    <option value="">Seleccione...</option>
                    <option value="regular">Regular</option>
                    <option value="irregular">Irregular</option>
                </select>
                </div>
                <div class="col-md-4">
                    <label for="duracion" class="form-label">Duracion del ciclo</label>
                    <input type="number" name="duracion" id="duracion" class="form-control" placeholder="Ej: 21 dias">
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
                    <select name="color-ojos" id="color-ojos" class="form-select">
                        <option value="">Seleccione...</option>
                        <option value="ambar">Ambar</option>
                        <option value="castaño">Castaño</option>
                        <option value="avellana">Avellana</option>
                        <option value="azul">Azul</option>
                        <option value="verde">Verde</option>
                        <option value="gris">Gris</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="color-pelo" class="form-label">Color de pelo</label>
                    <select name="color-pelo" id="color-pelo" class="form-select">
                        <option value="">Seleccione...</option>
                        <option value="negro">Negro</option>
                        <option value="castaño">Castaño</option>
                        <option value="rubio">Rubio</option>
                        <option value="pelirrojo">Pelirrojo</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="tipo-pelo" class="form-label">Tipo de pelo</label>
                    <select name="tipo-pelo" id="tipo-pelo" class="form-select">
                        <option value="">Seleccione...</option>
                        <option value="rizado">Rizado</option>
                        <option value="liso">Liso</option>
                        <option value="ondulado">Ondulado</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="altura" class="form-label">Altura</label>
                    <input type="number" name="altura" id="altura" class="form-control" placeholder="Ej: 1,72">
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
            
        </div>


    </form>
</div>

@endsection


@section('scripts')
<script src="{{ asset('js/terminos.js') }}"></script>
<script src="{{ asset('js/familiares.js') }}"></script>
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
