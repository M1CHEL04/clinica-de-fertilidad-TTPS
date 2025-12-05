@extends('layouts.layoutInterno')

@section('styles')
    <style>
        /* Fondo general para contraste */
        body {
            background-color: #f8f9fa;
        }

        /* Estilos del flujo de pasos (wizard) */
        .step {
            display: none;
        }

        .step.active {
            display: block;
        }

        /* Navegación de pasos mejorada */
        .wizard-nav {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            border-bottom: 2px solid #e9ecef;
        }

        .wizard-nav span {
            padding: 10px 15px;
            border-radius: 6px 6px 0 0;
            background: transparent;
            color: #6c757d;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            border-bottom: 2px solid transparent;
            text-align: center;
        }

        .wizard-nav .active {
            background: white;
            color: #007bff;
            border-bottom: 2px solid #007bff;
            font-weight: bold;
        }

        /* Estilo para los campos requeridos */
        label.required::after {
            content: " *";
            color: #412c2c8e;
            font-weight: bold;
        }

        /* Contenedor principal */
        .main-content-container {
            min-height: 80vh;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding: 40px 15px;
        }

        /* Tarjeta del formulario */
        .consult-card {
            max-width: 850px;
            width: 100%;
            margin-top: 20px;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            padding: 30px;
        }

        input[type="number"],
        select,
        textarea {
            background-color: #ffffff;
            border: 1px solid #ced4da;
            border-radius: 6px;
            padding: 10px 12px;
            width: 100%;
            box-sizing: border-box;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }

        /* Campos del formulario */
        input[type="text"],
        select,
        textarea {
            background-color: #ffffff;
            border: 1px solid #ced4da;
            border-radius: 6px;
            padding: 10px 12px;
            width: 100%;
            box-sizing: border-box;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }

        input:focus,
        select:focus,
        textarea:focus {
            border-color: #80bdff;
            outline: none;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, .25);
        }

        /* Agrupación de campos */
        .form-group {
            margin-bottom: 20px;
        }

        /* Botones */
        button,
        .btn {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 8px 10px;
            border-radius: 6px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover,
        .btn:hover {
            background-color: #0056b3;
        }

        .list-group-item button.btn-outline-danger {
            border: none;
            background: transparent;
            color: #495057;
            padding: 4px 8px;
            margin-left: 10px;
        }

        .list-group-item button.btn-outline-danger:hover {
            color: #49494aff;
        }
    </style>
@endsection

@section('page-header')
    <div class="page-header">
        <div>
            <h1 class="page-title">Primer consulta</h1>
            <p class="page-subtitle">Cargar información detallada del paciente y su objetivo</p>
        </div>
        @if (session('rol') == 3)
            <div>
                <a href="/operador/home" class="btn-secondary">
                    <i class="fas fa-arrow-left mr-2"></i> Volver a Pacientes
                </a>
            </div>
        @elseif (session('rol') == 5)
            <div>
                <a href="/jefe/home" class="btn-secondary">
                    <i class="fas fa-arrow-left mr-2"></i> Volver a Pacientes
                </a>
            </div>
        @else
            <div>
                <a href="/medico/home" class="btn-secondary">
                    <i class="fas fa-arrow-left mr-2"></i> Volver a Pacientes
                </a>
            </div>
        @endif
    </div>
@endsection

@section('content')

    {{-- CONTENEDOR CENTRADO CORRECTAMENTE --}}
    <div class="main-content-container">

        {{-- Contenedor principal con estilo de card --}}
        <div class="card consult-card p-4">


            <div class="wizard-nav">
                <span class="nav-step active" data-step="1"><i class="fas fa-user-check me-2"></i>Datos iniciales</span>
                <span class="nav-step" data-step="2"><i class="fas fa-users me-2"></i>Antecedentes familiares</span>
                <span class="nav-step" data-step="3"><i class="fas fa-venus me-2"></i>Antecedentes ginecológicos</span>
                <span class="nav-step" data-step="4"><i class="fas fa-dna me-2"></i>Fenotipo</span>
                <span class="nav-step" data-step="5"><i class="fas fa-bullseye me-2"></i>Objetivo</span>
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



                {{-- STEP 1: Datos personales --}}
                <div class="step active" id="step-1">

                    <h3 class="mb-3 border-bottom pb-2">Datos personales</h3>

                    {{-- ANTECEDENTES DINÁMICOS --}}
                    <div class="mb-4">
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
                                <label class="form-label mt-2">Antecedentes cargados anteriormente</label>
                                @foreach ($antecedentes as $antecedente)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        {{ $antecedente }}

                                    </li>
                                @endforeach
                            @endif
                        </ul>
                    </div>
                    {{-- {{ dd($tratamiento) }} --}}
                    {{-- FUMA --}}
                    <div class="mb-4">
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

                    <div class="mb-4 p-3 border rounded bg-light" id="campo-cantidad"
                        style="{{ $tratamiento?->antecedentesPersonales?->fuma > 0 ? '' : 'display:none;' }}">
                        <label class="form-label fw-bold">Detalle de consumo de tabaco</label>

                        <div class="row">
                            <div class="col-md-4 mb-2">
                                <label for="cant_cigarros" class="form-label">Cigarros por día</label>
                                <input type="number" min="0" id="cant_cigarros" class="form-control"
                                    placeholder="Cigarros por día" value="{{ old('cant_cigarros', $detalleCant[0]) }}">
                            </div>

                            <div class="col-md-4 mb-2">
                                <label for="dias_fuma" class="form-label">Días por semana</label>
                                <input type="number" min="0" id="dias_fuma" class="form-control"
                                    placeholder="Días por semana" value="{{ old('dias_fuma', $detalleCant[1]) }}">
                            </div>

                            <div class="col-md-4 mb-2">
                                <label for="anios_fuma" class="form-label">Años fumando</label>
                                <input type="number" min="0" id="anios_fuma" class="form-control"
                                    placeholder="Años fumando" value="{{ old('anios_fuma', $detalleCant[2]) }}">
                            </div>
                        </div>

                        @if ($tratamiento?->antecedentesPersonales?->cuanto_fuma > 0)
                            <p class="mt-2 small text-primary">Resultado almacenado (Estandar):
                                {{ $tratamiento?->antecedentesPersonales?->cuanto_fuma }}</p>
                        @endif

                        <input type="hidden" name="cantidad" id="cantidad" value="{{ $cant }}">
                        <small class="text-muted" id="preview_cantidad" style="display:block; margin-top: 5px;">
                            {{ $cant }}
                        </small>
                    </div>

                    {{-- ALCOHOL --}}
                    <div class="mb-4">
                        <label for="alcohol" class="form-label required">¿Consume alcohol?</label>
                        <select name="alcohol" id="alcohol" class="form-select" required>
                            <option value="">Seleccione...</option>
                            <option value="si"
                                {{ $tratamiento?->antecedentesPersonales?->alcohol > 0 ? 'selected' : '' }}>Sí</option>
                            <option value="no"
                                {{ $tratamiento?->antecedentesPersonales?->alcohol === 0 ? 'selected' : '' }}>No</option>
                        </select>
                    </div>

                    <div id="alcohol-extra" class="mb-4 p-3 border rounded bg-light"
                        style="{{ $tratamiento?->antecedentesPersonales?->alcohol > 0 ? '' : 'display:none;' }}">
                        <label class="form-label fw-bold">Detalle de consumo de alcohol</label>
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <label for="frecuencia" class="form-label required">Frecuencia (días a la
                                    semana)</label>
                                <input type="number" min="0" id="frecuencia" name="frecuencia"
                                    class="form-control"
                                    value="{{ $tratamiento?->antecedentesPersonales?->frecuencia_alcohol }}"
                                    placeholder="días a la semana">
                            </div>

                            <div class="col-md-6 mb-2">
                                <label for="bebida-alcohol" class="form-label required">Qué bebida</label>
                                <input type="text" name="bebida-alcohol" id="bebida-alcohol" class="form-control"
                                    value="{{ $tratamiento?->antecedentesPersonales?->bebida_alcohol }}"
                                    placeholder="Ej: cerveza, vodka">
                            </div>
                        </div>
                    </div>

                    {{-- DROGAS --}}
                    <div class="mb-4">
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
                    <div class="mb-4">
                        <label for="observaciones" class="form-label required">Observaciones</label>
                        <textarea name="observaciones" id="observaciones" class="form-control" rows="3">{{ old('observaciones', $tratamiento?->antecedentesPersonales?->observaciones) }}</textarea>
                    </div>

                    <div class="d-flex justify-content-end pt-3 border-top">
                        <button type="button" onclick="nextStep()" class="btn btn-primary">
                            Siguiente <i class="fas fa-arrow-right ms-2"></i>
                        </button>
                    </div>

                </div>

                {{-- STEP 2: Antecedentes familiares --}}
                <div class="step" id="step-2">
                    <h3 class="mb-3 border-bottom pb-2">Antecedentes familiares</h3>

                    <div id="familiares-container">
                        @if ($tratamiento?->antecedentesFamiliares)
                            @foreach ($tratamiento->antecedentesFamiliares as $index => $familiar)
                                <div class="mb-4 p-3 border rounded familiar-entry" data-index="{{ $index }}">
                                    <label class="form-label fw-bold">Familiar cargado</label>
                                    <input type="text" name="patologias_familiares[{{ $index }}]"
                                        class="form-control mb-2" placeholder="Nombre del familiar"
                                        value="{{ $familiar->patologias_familiares }}" readonly>

                                    <label class="form-label mt-2">Patologías</label>
                                    <ul class="list-group list-group-flush mb-2">
                                        @foreach (json_decode($familiar->patologias ?? '[]') as $patologia)
                                            <li class="list-group-item">{{ $patologia }}</li>
                                        @endforeach
                                    </ul>

                                    <small class="text-danger">Nota: Los datos cargados no son editables directamente.
                                        Debe Añadir nuevos familiares o modificar la lista de patologías si es
                                        dinámica.</small>
                                </div>
                            @endforeach
                        @endif
                    </div>




                    <div class="d-flex justify-content-between pt-3 border-top mt-4">


                        <button type="button" onclick="prevStep()" class="btn btn-secondary"><i
                                class="fas fa-arrow-left me-2"></i>Atrás</button>
                        <button type="button" onclick="nextStep()" class="btn btn-primary">Siguiente <i
                                class="fas fa-arrow-right ms-2"></i></button>

                        <button type="button" id="add-familiar" class="btn btn-info"><i
                                class="fas fa-plus me-2"></i>Añadir familiar</button>
                    </div>
                </div>

                {{-- ========================= STEP 3: Antecedentes ginecológicos ========================= --}}
                <div class="step" id="step-3">
                    <h3 class="mb-3 border-bottom pb-2">Antecedentes ginecológicos</h3>
                    @php $g = $tratamiento?->antecedentesGinecologicos @endphp
                    <div class="row g-3"> {{-- g-3 añade espaciado (gap) entre columnas y filas --}}
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
                            <label for="duracion" class="form-label required">Duración del ciclo (días)</label>
                            <input type="number" name="duracion" id="duracion" class="form-control"
                                placeholder="Ej: 21 dias" value="{{ old('duracion', $g?->duracion) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="caracteristica" class="form-label required">Característica del sangrado</label>
                            <input type="text" name="caracteristica" id="caracteristica" class="form-control"
                                placeholder="Ej: abundante, leve"
                                value="{{ old('caracteristica', $g?->caracteristicas_sangrado) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="menarca" class="form-label required">Menarca (edad en años)</label>
                            <input type="number" name="menarca" id="menarca" class="form-control"
                                placeholder="Ej: 12 años" value="{{ old('menarca', $g?->edad_menarca) }}" required>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label required">Fórmula Obstétrica (G P AB CT)</label>
                            <div class="row">
                                <div class="col-3">
                                    <label for="embarazos" class="form-label required small">G (Gestaciones)</label>
                                    <input type="number" name="embarazos" id="embarazos" class="form-control"
                                        placeholder="G" value="{{ old('embarazos', $g?->G) }}" required>
                                </div>
                                <div class="col-3">
                                    <label for="partos" class="form-label required small">P (Partos)</label>
                                    <input type="number" name="partos" id="partos" class="form-control"
                                        placeholder="P" value="{{ old('partos', $g?->P) }}" required>
                                </div>
                                <div class="col-3">
                                    <label for="abortos" class="form-label required small">AB (Abortos)</label>
                                    <input type="number" name="abortos" id="abortos" class="form-control"
                                        placeholder="AB" value="{{ old('abortos', $g?->AB) }}" required>
                                </div>
                                <div class="col-3">
                                    <label for="ectopicos" class="form-label required small">CT (Ectópicos)</label>
                                    <input type="number" name="ectopicos" id="ectopicos" class="form-control"
                                        placeholder="CT" value="{{ old('ectopicos', $g?->CT) }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label for="examen_fisico" class="form-label required">Examen físico</label>
                            <textarea name="examen_fisico" id="examen_fisico" class="form-control" rows="3" required>{{ old('examen_fisico', $g?->examen_fisico) }}</textarea>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between pt-3 border-top mt-4">
                        <button type="button" onclick="prevStep()" class="btn btn-secondary"><i
                                class="fas fa-arrow-left me-2"></i>Atrás</button>
                        <button type="button" onclick="nextStep()" class="btn btn-primary">Siguiente <i
                                class="fas fa-arrow-right ms-2"></i></button>
                    </div>
                </div>


                {{-- ========================= STEP 4: Fenotipo ========================= --}}
                <div class="step" id="step-4">
                    <h3 class="mb-3 border-bottom pb-2">Fenotipo</h3>
                    @php $f = $tratamiento?->antecedentesFenotipos @endphp

                    <div class="row g-3">
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
                        <div class="col-md-4">
                            <label for="altura" class="form-label required">Altura (cm)</label>
                            <input type="number" name="altura" id="altura" class="form-control"
                                placeholder="Ej: 172" value="{{ $f?->altura }}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="complexion" class="form-label required">Complexión</label>
                            <select name="complexion" id="complexion" class="form-select" required>
                                <option value="" disabled selected>Seleccione una complexión</option>
                                @foreach ($complexiones as $complexion)
                                    <option value="{{ $complexion->id }}"
                                        {{ ($f?->complexion_corporal ?? '') == $complexion->id ? 'selected' : '' }}>
                                        {{ $complexion->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
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

                    <div class="d-flex justify-content-between pt-3 border-top mt-4">
                        <button type="button" onclick="prevStep()" class="btn btn-secondary"><i
                                class="fas fa-arrow-left me-2"></i>Atrás</button>
                        <button type="button" onclick="nextStep()" class="btn btn-primary">Siguiente <i
                                class="fas fa-arrow-right ms-2"></i></button>
                    </div>
                </div>

                {{-- STEP 5: Objetivo --}}
                <div class="step" id="step-5">
                    <h3 class="mb-3 border-bottom pb-2">Objetivo de la consulta</h3>

                    <div class="mb-4 p-3 border rounded bg-light">
                        <label class="form-label fw-bold">Objetivo seleccionado anteriormente:</label>
                        <span id="objetivo-label" class="fw-bolder text-primary fs-5">
                            {{ isset($tratamiento) ? $tratamiento->objetivo->nombre : 'No especificado' }}
                        </span>
                    </div>

                    <div class="mb-4">
                        <label for="objetivo" class="form-label required">Seleccionar Nuevo Objetivo</label>
                        <select name="objetivo" id="objetivo" class="form-select" required>
                            <option value="">Seleccione...</option>
                            @foreach ($objetivos as $obj)
                                <option value="{{ $obj->id }}"
                                    >
                                    {{ $obj->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>


                    <div id="antecedentePareja"></div>

                    <button type="button" onclick="prevStep()" class="btn btn-secondary">← Atrás</button>
                    @if (session('rol') == 3)
                        <button type="submit" class="btn btn-primary float-end" disabled
                            style="opacity: 0.4; cursor: not-allowed;">Solo puedes ver los datos, no editar</button>
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
        window.rol = {!! json_encode(session('rol') ?? null) !!}
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
