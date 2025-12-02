@extends('layouts.layoutInterno')

@section('content')
    <style>
        .step {
            display: none;
        }

        .step.active {
            display: block;
        }

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
    <div
        style="
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

            <form action="{{ $tratamiento ? route('consulta.update', $tratamiento->id) : route('consulta.store') }}"
                method="POST">

                @csrf

                {{-- Siempre incluimos el ID del paciente --}}
                <input type="hidden" name="paciente_id" value="{{ $paciente->id }}">

                {{-- Si es update, incluimos tratamiento_id --}}
                @if ($tratamiento)
                    <input type="hidden" name="tratamiento_id" value="{{ $tratamiento->id }}">
                @endif



                {{-- STEP 1 --}}
                <div class="step active" id="step-1">

                    <h3>Datos personales</h3>

                    {{-- ANTECEDENTES DINÁMICOS --}}
                    <div class="mb-3">
                        <label for="antecedente-input" class="form-label">Antecedentes</label>

                        <div class="dropdown">
                            <input type="text" id="antecedente-input" class="form-control"
                                placeholder="Ingresar 3 letras...">

                            <div id="antecedente-dropdown" class="dropdown-menu p-2"
                                style="max-height: 180px; overflow-y: auto;">
                            </div>
                        </div>
                        @php
                            $antecedentes =
                                json_decode($tratamiento?->antecedentesPersonales?->antecedentes, true) ?? [];
                        @endphp
                        <ul id="antecedente-list" class="list-group mt-2">
                            {{-- Si hay antecedentes personales guardados --}}
                            @if ($tratamiento?->antecedentesPersonales?->antecedentes)
                                <label class="form-label">Antecedentes cargados anteriormente</label>
                                @foreach ($antecedentes as $antecedente)
                                    <li class="list-group-item">{{ $antecedente }}</li>
                                @endforeach
                            @endif
                        </ul>
                    </div>

                    {{-- FUMA --}}
                    <div class="mb-3">
                        <label for="fuma" class="form-label required">¿Fuma?</label>
                        <select name="fuma" id="fuma" class="form-select" required>
                            <option value="">Seleccione...</option>
                            <option value="si" {{ $tratamiento?->antecedentesPersonales?->fuma > 0 ? 'selected' : '' }}>
                                Sí</option>
                            <option value="no"
                                {{ $tratamiento?->antecedentesPersonales?->fuma === 0 ? 'selected' : '' }}>No</option>
                        </select>
                    </div>

                    {{-- CAMPOS DE CANTIDAD SI FUMA --}}
                    @php
                        $cant = $tratamiento?->antecedentesPersonales?->cantidad ?? '';
                        $detalleCant = $cant ? explode('|', $cant) : [null, null, null];
                    @endphp

                    <div class="mb-3" id="campo-cantidad"
                        style="{{ $tratamiento?->antecedentesPersonales?->fuma > 0 ? '' : 'display:none;' }}">
                        <label class="form-label ">Pack/días</label>

                        <div class="row">
                            <div class="col-md-4">
                                <input type="number" min="0" id="cant_cigarros" class="form-control"
                                    placeholder="Cigarros por día" value="{{ old('cant_cigarros', $detalleCant[0]) }}">
                            </div>

                            <div class="col-md-4">
                                <input type="number" min="0" id="dias_fuma" class="form-control"
                                    placeholder="Días por semana" value="{{ old('dias_fuma', $detalleCant[1]) }}">
                            </div>

                            <div class="col-md-4">
                                <input type="number" min="0" id="anios_fuma" class="form-control"
                                    placeholder="Años fumando" value="{{ old('anios_fuma', $detalleCant[2]) }}">
                            </div>
                            @if ($tratamiento?->antecedentesPersonales?->cuanto_fuma > 0)
                                <p>Resultado anteriormente almacenado segun el estandar:
                                    {{ $tratamiento?->antecedentesPersonales?->cuanto_fuma }}</p>
                            @endif
                        </div>

                        <input type="hidden" name="cantidad" id="cantidad" value="{{ $cant }}">
                        <small class="text-muted" id="preview_cantidad" style="display:block; margin-top: 5px;">
                            {{ $cant }}
                        </small>
                    </div>

                    {{-- ALCOHOL --}}

                    <div class="mb-3">
                        <label for="alcohol" class="form-label required">¿Consume alcohol?</label>

                        <select name="alcohol" id="alcohol" class="form-select" required>
                            <option value="">Seleccione...</option>
                            <option value="si"
                                {{ $tratamiento?->antecedentesPersonales?->alcohol > 0 ? 'selected' : '' }}>Sí</option>
                            <option value="no"
                                {{ $tratamiento?->antecedentesPersonales?->alcohol === 0 ? 'selected' : '' }}>No</option>
                        </select>
                    </div>

                    <div id="alcohol-extra"
                        style="{{ $tratamiento?->antecedentesPersonales?->alcohol > 0 ? '' : 'display:none;' }}">

                        <div class="mb-3">
                            <label for="frecuencia" class="form-label required">Frecuencia</label>
                            <input type="number" min="0" id="frecuencia" name="frecuencia" class="form-control"
                                value="{{ $tratamiento?->antecedentesPersonales?->frecuencia_alcohol }}"
                                placeholder="días a la semana">
                        </div>

                        <div class="mb-3">
                            <label for="bebida-alcohol" class="form-label required">Qué bebida</label>
                            <input type="text" name="bebida-alcohol" id="bebida-alcohol" class="form-control"
                                value="{{ $tratamiento?->antecedentesPersonales?->bebida_alcohol }}"
                                placeholder="Ej: cerveza, vodka">
                        </div>
                    </div>

                    {{-- DROGAS --}}


                    <div class="mb-3">
                        <label for="droga" class="form-label required">Drogas recreativas</label>

                        <select name="droga" id="droga" class="form-select" required>
                            <option value="">Seleccione...</option>
                            <option value="si"
                                {{ $tratamiento?->antecedentesPersonales->droga > 0 ? 'selected' : '' }}>Sí</option>
                            <option value="no"
                                {{ $tratamiento?->antecedentesPersonales->droga === 0 ? 'selected' : '' }}>No</option>
                        </select>
                    </div>

                    {{-- OBSERVACIONES --}}
                    <div class="mb-3">
                        <label for="observaciones" class="form-label required">Observaciones</label>
                        <textarea name="observaciones" id="observaciones" class="form-control">{{ old('observaciones', $tratamiento?->antecedentesPersonales?->observaciones) }}</textarea>
                    </div>

                    <br>
                    <button type="button" onclick="nextStep()" class="btn btn-primary float-end">
                        Siguiente →
                    </button>

                </div>

                {{-- STEP 2 --}}

                <div class="step" id="step-2">

                    <h3>Antecedentes familiares</h3>

                    <div id="familiares-container">
                        @if ($tratamiento?->antecedentesFamiliares)
                            @foreach ($tratamiento->antecedentesFamiliares as $index => $familiar)
                                <div class="mb-3 familiar-entry" data-index="{{ $index }}">
                                    <label class="form-label ">Familiar</label>
                                    <input type="text" name="patologias_familiares[{{ $index }}]"
                                        class="form-control mb-1" placeholder="Nombre del familiar"
                                        value="{{ $familiar->patologias_familiares }}" readonly>

                                    <label class="form-label">Patologías</label>
                                    <ul class="list-group-item mb-2">
                                        @foreach (json_decode($familiar->patologias ?? '[]') as $patologia)
                                            <li class="list-group-item">{{ $patologia }}</li>
                                        @endforeach
                                    </ul>

                                    <hr>
                                </div>
                            @endforeach
                        @endif
                    </div>

                    <button type="button" id="add-familiar" class="btn btn-secondary mt-2">Añadir familiar</button>
                    <button type="button" onclick="prevStep()" class="btn btn-secondary">← Atrás</button>
                    <button type="button" onclick="nextStep()" class="btn btn-primary float-end">Siguiente →</button>
                </div>

                {{-- ========================= STEP 3: Antecedentes ginecológicos ========================= --}}
                <div class="step" id="step-3">
                    <h3>Antecedentes ginecológicos</h3>
                    @php $g = $tratamiento?->antecedentesGinecologicos @endphp
                    <div class="row">
                        <div class="col-md-4">
                            <label for="ciclos" class="form-label required">Ciclos menstruales</label>
                            <select name="ciclos" id="ciclos" class="form-select" required>
                                <option value="">Seleccione...</option>
                                <option value="regular" {{ ($g?->ciclo_regular ?? '') === 1 ? 'selected' : '' }}>Regular
                                </option>
                                <option value="irregular" {{ ($g?->ciclo_regular ?? '') === 0 ? 'selected' : '' }}>
                                    Irregular</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="duracion" class="form-label required">Duración del ciclo</label>
                            <input type="number" name="duracion" id="duracion" class="form-control"
                                placeholder="Ej: 21 dias" value="{{ old('duracion', $g?->duracion) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="caracteristica" class="form-label required">Característica del sangrado</label>
                            <input type="text" name="caracteristica" id="caracteristica" class="form-control"
                                placeholder="Ej: abundante, leve"
                                value="{{ old('caracteristica', $g?->caracteristicas_sangrado) }}" required>
                        </div>
                        <div class="col-md-4 mt-3">
                            <label for="menarca" class="form-label required">Menarca (edad)</label>
                            <input type="number" name="menarca" id="menarca" class="form-control"
                                placeholder="Ej: 12 años" value="{{ old('menarca', $g?->edad_menarca) }}" required>
                        </div>
                        <div class="col-md-3 mt-3">
                            <label for="embarazos" class="form-label required">G</label>
                            <input type="number" name="embarazos" id="embarazos" class="form-control"
                                placeholder="Cantidad de embarazos" value="{{ old('embarazos', $g?->G) }}" required>
                        </div>
                        <div class="col-md-3 mt-3">
                            <label for="partos" class="form-label required">P</label>
                            <input type="number" name="partos" id="partos" class="form-control"
                                placeholder="Cantidad de partos" value="{{ old('partos', $g?->P) }}" required>
                        </div>
                        <div class="col-md-3 mt-3">
                            <label for="abortos" class="form-label required">AB</label>
                            <input type="number" name="abortos" id="abortos" class="form-control"
                                placeholder="Cantidad de abortos" value="{{ old('abortos', $g?->AB) }}" required>
                        </div>
                        <div class="col-md-3 mt-3">
                            <label for="ectopicos" class="form-label required">CT</label>
                            <input type="number" name="ectopicos" id="ectopicos" class="form-control"
                                placeholder="Cantidad de embarazos ectópicos" value="{{ old('ectopicos', $g?->CT) }}"
                                required>
                        </div>
                        <div class="col-md-12 mt-3">
                            <label for="examen_fisico" class="form-label required">Examen físico</label>
                            <textarea name="examen_fisico" id="examen_fisico" class="form-control" required>{{ old('examen_fisico', $g?->examen_fisico) }}</textarea>
                        </div>
                    </div>

                    <br>
                    <button type="button" onclick="prevStep()" class="btn btn-secondary">← Atrás</button>
                    <button type="button" onclick="nextStep()" class="btn btn-primary float-end">Siguiente →</button>
                </div>


                {{-- ========================= STEP 4: Fenotipo ========================= --}}
                <div class="step" id="step-4">
                    <h3 class="mt-4">Fenotipo</h3>
                    @php $f = $tratamiento?->antecedentesFenotipos @endphp

                    <div class="row">
                        <div class="col-md-4">
                            <label for="color-ojos" class="form-label required">Color de ojos</label>
                            <select name="color-ojos" id="color-ojos" class="form-select" required>
                                <option value="" disabled selected>Seleccione un color de ojos</option>
                                @foreach ($coloresOjos as $color)
                                    <option value="{{ $color->value }}"
                                        {{ ($f?->color_ojos ?? '') === $color->value ? 'selected' : '' }}>
                                        {{ $color->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="color-pelo" class="form-label required">Color de pelo</label>
                            <select name="color-pelo" id="color-pelo" class="form-select" required>
                                <option value="" disabled selected>Seleccione un color de pelo</option>
                                @foreach ($coloresPelo as $color)
                                    <option value="{{ $color->value }}"
                                        {{ ($f?->color_pelo ?? '') === $color->value ? 'selected' : '' }}>
                                        {{ $color->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="tipo-pelo" class="form-label required">Tipo de pelo</label>
                            <select name="tipo-pelo" id="tipo-pelo" class="form-select" required>
                                <option value="" disabled selected>Seleccione un tipo de pelo</option>
                                @foreach ($tipoPelo as $tipo)
                                    <option value="{{ $tipo->value }}"
                                        {{ ($f?->tipo_pelo ?? '') === $tipo->value ? 'selected' : '' }}>
                                        {{ $tipo->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mt-3">
                            <label for="altura" class="form-label required">Altura (cm)</label>
                            <input type="number" name="altura" id="altura" class="form-control"
                                placeholder="Ej: 172" value="{{ $f?->altura }}" required>
                        </div>
                        <div class="col-md-4 mt-3">
                            <label for="complexion" class="form-label required">Complexión</label>
                            <select name="complexion" id="complexion" class="form-select" required>
                                <option value="" disabled selected>Seleccione una complexión</option>
                                @foreach ($complexiones as $complexion)
                                    <option value="{{ $complexion->value }}"
                                        {{ ($f?->complexion ?? '') === $complexion->value ? 'selected' : '' }}>
                                        {{ $complexion->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mt-3">
                            <label for="rasgos-etnicos" class="form-label required">Rasgos étnicos</label>
                            <select name="rasgos-etnicos" id="rasgos-etnicos" class="form-select" required>
                                <option value="" disabled selected>Seleccione rasgos étnicos</option>
                                @foreach ($rasgos as $rasgo)
                                    <option value="{{ $rasgo->value }}"
                                        {{ ($f?->rasgos_etnicos ?? '') === $rasgo->value ? 'selected' : '' }}>
                                        {{ $rasgo->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <br>
                    <button type="button" onclick="prevStep()" class="btn btn-secondary">← Atrás</button>
                    <button type="button" onclick="nextStep()" class="btn btn-primary float-end">Siguiente →</button>
                </div>

                {{-- STEP 5 --}}


                <div class="step" id="step-5">


                    <div class="mb-3">
                        <label class="form-label">Objetivo seleccionado anteriormente:</label>
                        <span id="objetivo-label" class="fw-bold">
                            {{ isset($tratamiento) ? $tratamiento->objetivo->nombre : '-' }}
                        </span>
                    </div>
                    <div class="mb-3">
                        <h2>Objetivo de la consulta</h2>

                        <select name="objetivo" id="objetivo" class="form-select">

                            <option value="">Seleccione...</option>
                            @foreach ($objetivos as $obj)
                                <option value="{{ $obj->id }}" {{ isset($tratamiento) }}>
                                    {{ $obj->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>


                    <div id="antecedentePareja"></div>

                    <button type="button" onclick="prevStep()" class="btn btn-secondary">← Atrás</button>
                    @if (session('rol') == 3)
                     <button type="submit" class="btn btn-primary float-end" disabled  style="opacity: 0.4; cursor: not-allowed;">Solo puedes ver los datos, no editar</button>
                    @else
                    <button type="submit" class="btn btn-primary float-end">Guardar consulta</button>
                    @endif
                </div>

            </form>

        </div> {{-- card --}}
    </div> {{-- centering container --}}

@endsection


@section('scripts')
    <script>
        window.tratamiento = {!! json_encode($tratamiento ?? null) !!};
        window.rol ={!! json_encode( session('rol') ?? null) !!}
    </script>

    <script src="{{ asset('js/terminos.js') }}"></script>
    <script src="{{ asset('js/familiares.js') }}"></script>
    <script src="{{ asset('js/wizard.js') }}"></script>
    <script src="{{ asset('js/formulario.js') }}"></script>
    <script src="{{ asset('js/estandarFumar.js') }}"></script>
    <script src="{{ asset('js/antecedentes.js') }}"></script>
    <script src="{{ asset('js/parejas.js') }}"></script>
    <script src="{{ asset('js/antecedentes-pareja.js') }}"></script>
@endsection
