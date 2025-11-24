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

    label.required::after {
        content: " *";
        color: #e75353ff;
        font-weight: bold;
    }
</style>

{{-- CONTENEDOR CENTRADO CORRECTAMENTE --}}
<div style="
    min-height: 80vh;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 40px;
">

    <div class="card shadow p-4" style="max-width: 900px; width: 100%;">


    <h1>Primera Consulta</h1>

    <div class="wizard-nav">
        <span class="nav-step active" data-step="1">Datos iniciales</span>
        <span class="nav-step" data-step="2">Antecedentes familiares</span>
        <span class="nav-step" data-step="3">Antecedentes ginecológicos</span>
        <span class="nav-step" data-step="4">Fenotipo</span>
        <span class="nav-step" data-step="5">Objetivo</span>
    </div>

    <form action="{{ route('consulta.store') }}" method="POST">
        @csrf
        <input type="hidden" name="paciente_id" value="{{ $paciente->id }}">


        {{-- STEP 1 --}}
        <div class="step active" id="step-1">


            <h3>Datos personales</h3>

            
            <div class="mb-3">
                <label for="antecedente-input" class="form-label">Antecedentes</label>
                <div class="dropdown">
                    <input type="text" id="antecedente-input" class="form-control" placeholder="Ingresar 3 letras..." >
                    <div id="antecedente-dropdown" class="dropdown-menu p-2" style="max-height: 180px; overflow-y: auto;"></div>
                </div>
                <ul id="antecedente-list" class="list-group mt-2"></ul>
            </div>


            {{-- FUMA --}}
            <div class="mb-3">
                <label for="fuma" class="form-label required">¿Fuma?</label>
                <select name="fuma" id="fuma" class="form-select" required>
                    <option value="">Seleccione...</option>
                    <option value="si">Sí</option>
                    <option value="no">No</option>
                </select>
            </div>

            <div class="mb-3" id="campo-cantidad" style="display:none;">
                <label class="form-label required">Pack/días</label>

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

                <input type="hidden" name="cantidad" id="cantidad">
                <small class="text-muted" id="preview_cantidad" style="display:block; margin-top: 5px;"></small>
            </div>

            {{-- ALCOHOL --}}
            <div class="mb-3">
                <label for="alcohol" class="form-label required">¿Consume alcohol?</label>
                <select name="alcohol" id="alcohol" class="form-select" required>
                    <option value="">Seleccione...</option>
                    <option value="si">Sí</option>
                    <option value="no">No</option>
                </select>
            </div>

            <div id="alcohol-extra" style="display:none;">
                <div class="mb-3">
                    <label for="frecuencia" class="form-label required">Frecuencia</label>
                    <input type="number" min="0" id="frecuencia" class="form-control" placeholder="dias a la semana">
                </div>

                <div class="mb-3">
                    <label for="bebida-alcohol" class="form-label required">Qué bebida</label>
                    <input type="text" name="bebida-alcohol" id="bebida-alcohol" class="form-control" placeholder="Ej: cerveza, vodka">
                </div>
            </div>

            {{-- DROGAS --}}
            <div class="mb-3">
                <label for="drogas" class="form-label required">Drogas recreativas</label>
                <select name="droga" id="droga" class="form-select" required>
                    <option value="">Seleccione...</option>
                    <option value="si">Sí</option>
                    <option value="no">No</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="observaciones" class="form-label required">Observaciones</label>
                <textarea name="observaciones" id="observaciones" class="form-control"></textarea>
            </div>

            <br>
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
                    <label for="ciclos" class="form-label required">Ciclos menstruales</label> 
                    <select name="ciclos" id="ciclos" class="form-select" required> 
                        <option value="">Seleccione...</option> 
                        <option value="regular">Regular</option> 
                        <option value="irregular">Irregular</option> 
                    </select> </div> <div class="col-md-4"> 
                        <label for="duracion" class="form-label required">Duracion del ciclo</label> 
                        <input type="number" name="duracion" id="duracion" class="form-control" placeholder="Ej: 21 dias" required> 
                    </div> 
                    <div class="col-md-4"> 
                        <label for="caracteristica" class="form-label required">Caracteristica del sangrado</label> 
                        <input type="text" name="caracteristica" id="caracteristica" class="form-control" placeholder="Ej: abundante, leve" required> 
                    </div> 
                    <div class="col-md-4"> 
                        <label for="menarca" class="form-label required">Menarca (edad)</label> 
                        <input type="number" name="menarca" id="menarca" class="form-control" placeholder="Ej: 12 años" required> 
                    </div> 
                </div> 
                <div class="row mt-3"> 
                    <div class="col-md-3"> 
                        <label for="embarazos" class="form-label required">G</label> 
                        <input type="number" name="embarazos" id="embarazos" class="form-control" placeholder="Cantidad de embarazos" required> 
                    </div> 
                    <div class="col-md-3"> 
                        <label for="partos" class="form-label required">P</label> 
                        <input type="number" name="partos" id="partos" class="form-control" placeholder="Cantidad de partos" required> 
                    </div> 
                    <div class="col-md-3"> 
                        <label for="abortos" class="form-label required">AB</label> 
                        <input type="number" name="abortos" id="abortos" class="form-control" placeholder="Cantidad de abortos" required> 
                    </div> 
                    <div class="col-md-3"> 
                        <label for="ectopicos" class="form-label required">CT</label> 
                        <input type="number" name="ectopicos" id="ectopicos" class="form-control" placeholder="Cantidad de embarazos ectopicos" required> 
                    </div> 
                </div> 
                <div class="mt-3"> 
                    <label for="examen_fisico" class="form-label required">Examen físico</label> 
                    <textarea name="examen_fisico" id="examen_fisico" class="form-control" required>

                    </textarea> 
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
                    <label for="color-ojos" class="form-label required">Color de ojos</label> 
                    <select name="color-ojos" id="color-ojos" class="form-select" required> 
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
                    <label for="color-pelo" class="form-label required">Color de pelo</label> 
                    <select name="color-pelo" id="color-pelo" class="form-select" required> 
                        <option value="">Seleccione...</option> 
                        <option value="negro">Negro</option> 
                        <option value="castaño">Castaño</option> 
                        <option value="rubio">Rubio</option> 
                        <option value="pelirrojo">Pelirrojo</option> 
                    </select> 
                </div> 
                <div class="col-md-4"> 
                    <label for="tipo-pelo" class="form-label required">Tipo de pelo</label> 
                    <select name="tipo-pelo" id="tipo-pelo" class="form-select" required> 
                        <option value="">Seleccione...</option> 
                        <option value="rizado">Rizado</option> 
                        <option value="liso">Liso</option> 
                        <option value="ondulado">Ondulado</option> 
                    </select> 
                </div> 
                <div class="col-md-4"> 
                    <label for="altura" class="form-label required">Altura (en cm)</label> 
                    <input type="number" name="altura" id="altura" class="form-control" placeholder="Ej: 172" required> 
                </div> 
                <div class="col-md-4"> 
                    <label for="complexion" class="form-label required">Complexion</label> 
                    <input type="text" name="complexion" id="complexion" class="form-control" placeholder="Ej: delgada, robusta" required> 
                </div> 
                <div class="col-md-4"> 
                    <label for="rasgos-etnicos" class="form-label required">Rasgos etnicos</label> 
                    <input type="text" name="rasgos-etnicos" id="rasgos-etnicos" class="form-control" placeholder="Ej: asiatico" required> 
                </div> 
            </div>

            <br>
            <button type="button" onclick="prevStep()" class="btn btn-secondary">← Atrás</button>
            <button type="button" onclick="nextStep()" class="btn btn-primary float-end">Siguiente →</button>
        </div>

        {{-- STEP 5 --}}
        <div class="step" id="step-5">

            <h2>Objetivo de la consulta</h2>

            <div class="mb-3">
                <select name="objetivo" id="objetivo" class="form-select">
                    <option value="">Seleccione...</option>

                    @foreach ($objetivos as $obj)
                        <option value="{{ $obj->id }}">{{ $obj->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div id="antecedentePareja"></div>

            <button type="button" onclick="prevStep()" class="btn btn-secondary">← Atrás</button>
            <button type="submit" class="btn btn-success float-end">Guardar consulta</button>
        </div>

    </form>

    </div> {{-- card --}}
</div> {{-- centering container --}}

@endsection


@section('scripts')
<script src="{{ asset('js/terminos.js') }}"></script>
<script src="{{ asset('js/familiares.js') }}"></script>
<script src="{{ asset('js/wizard.js')}}"></script>
<script src="{{ asset('js/formulario.js')}}"></script>
<script src="{{ asset('js/estandarFumar.js')}}"></script>
<script src="{{ asset('js/antecedentes.js')}}"></script>
<script src="{{ asset('js/parejas.js')}}"></script>
<script src="{{ asset('js/antecedentes-pareja.js')}}"></script>
@endsection
