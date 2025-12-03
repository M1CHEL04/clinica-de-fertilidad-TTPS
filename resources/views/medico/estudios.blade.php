@extends('layouts.layoutInterno')

@section('styles')
    <style>
        /* Fondo general para contraste */
        body {
            background-color: #f8f9fa;
        }

        /* Wrapper centrado */
        .card-centered-wrapper {
            min-height: 80vh;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding: 40px 15px;
        }

        /* Tarjeta principal */
        .card {
            border-radius: 10px;
            overflow: hidden;
        }

        .card-header {
            padding: 20px 30px;
        }

        .card-body {
            background-color: #ffffff;
        }

        /* Inputs y selects */
        .form-control,
        .form-select {
            background-color: #ffffff;
            border: 1px solid #ced4da;
            border-radius: 6px;
            padding: 10px 12px;
            font-size: 1rem;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #80bdff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, .25);
            outline: none;
        }

        /* Botones */
        .btn {
            font-weight: 500;
            border-radius: 6px;
            padding: 8px 14px;
        }

        .btn-outline-primary {
            border-color: #525252ff;
            color: #535353e3;
            background-color: #ffffff;
        }

        .btn-outline-primary:hover {
            background-color: #007bff;
            color: #ffffff;
        }

        .btn-success {
            padding: 10px 18px;
            font-size: 1rem;
        }

        /* Bloques de estudios */
        .bloque-estudios {
            background-color: #fdfdfd;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 30px;
        }

        .bloque-estudios label {
            font-size: 1.1rem;
            margin-bottom: 10px;
            display: block;
        }

        select option:disabled,
        .opcion-desactivada {
            color: #6c757d;
            /* Gris oscuro */
            background-color: #e9ecef;
            /* Fondo más apagado */
        }


        .list-group-item.d-flex.justify-content-between.align-items-center {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 15px;
            border-radius: 6px;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            margin-bottom: 6px;
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


@section('content')
    <div class="card-centered-wrapper">
        {{-- Diseño mejorado de la tarjeta con encabezado y cuerpo --}}
        <div class="card shadow" style="max-width: 900px; width: 100%;">

            <div class="card-header bg-primary text-black">
                <h1 class="h4 mb-0"><i class="fas fa-microscope me-2"></i> Asignar Estudios Médicos</h1>
            </div>

            <div class="card-body p-4">

                <p class="text-muted mb-4">Seleccioná los estudios requeridos para el paciente
                    <strong>{{ $paciente->nombre ?? 'N/A' }}</strong> y guardalos.</p>

                <form action="{{ route('estudios.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="paciente_id" value="{{ $paciente->id }}">


                    {{-- Bloque de Estudios Ginecológicos --}}
                    <div class="mb-4 p-3 border rounded">
                        <label class="form-label fw-bold text-primary"><i class="fas fa-venus me-2"></i> Estudios
                            Ginecológicos</label>
                        <div class="d-flex mb-2">
                            <select id="ginecologicos-select" class="form-select me-2">
                                <option value="">Seleccione un estudio...</option>
                                @foreach ($ginecologicos as $estudio)
                                    <option value="{{ $estudio['id'] }}" data-nombre="{{ $estudio['nombre'] }}">
                                        {{ $estudio['nombre'] }}</option>
                                @endforeach
                            </select>
                            <button type="button" id="add-ginecologico" class="btn btn-outline-primary flex-shrink-0"><i
                                    class="fas fa-plus me-1"></i> Agregar</button>
                        </div>
                        {{-- Lista donde se muestran los estudios seleccionados --}}
                        <ul id="ginecologicos-list" class="list-group">
                            {{-- Los estudios se agregarán aquí con JS --}}
                        </ul>
                    </div>

                    {{-- Bloque de Estudios Hormonales --}}
                    <div class="mb-4 p-3 border rounded">
                        <label class="form-label fw-bold text-primary"><i class="fas fa-flask me-2"></i> Estudios
                            Hormonales</label>
                        <div class="d-flex mb-2">
                            <select id="hormonales-select" class="form-select me-2">
                                <option value="">Seleccione un estudio...</option>
                                @foreach ($hormonales as $estudio)
                                    <option value="{{ $estudio['id'] }}" data-nombre="{{ $estudio['nombre'] }}">
                                        {{ $estudio['nombre'] }}</option>
                                @endforeach
                            </select>
                            <button type="button" id="add-hormonal" class="btn btn-outline-primary flex-shrink-0"><i
                                    class="fas fa-plus me-1"></i> Agregar</button>
                        </div>
                        <ul id="hormonales-list" class="list-group"></ul>
                    </div>

                    {{-- Bloque de Prequirúrgicos --}}
                    <div class="mb-4 p-3 border rounded">
                        <label class="form-label fw-bold text-primary"><i class="fas fa-heartbeat me-2"></i>
                            Prequirúrgicos</label>
                        <div class="d-flex mb-2">
                            <select id="prequirurgicos-select" class="form-select me-2">
                                <option value="">Seleccione un estudio...</option>
                                @foreach ($prequirurgicos as $estudio)
                                    <option value="{{ $estudio['id'] }}" data-nombre="{{ $estudio['nombre'] }}">
                                        {{ $estudio['nombre'] }}</option>
                                @endforeach
                            </select>
                            <button type="button" id="add-prequirurgico" class="btn btn-outline-primary flex-shrink-0"><i
                                    class="fas fa-plus me-1"></i> Agregar</button>
                        </div>
                        <ul id="prequirurgicos-list" class="list-group"></ul>
                    </div>

                    {{-- Bloque de Estudios de Semen --}}
                    <div class="mb-4 p-3 border rounded">
                        <label class="form-label fw-bold text-primary"><i class="fas fa-tint me-2"></i> Estudios de
                            Semen</label>
                        <div class="d-flex mb-2">
                            <select id="semen-select" class="form-select me-2">
                                <option value="">Seleccione un estudio...</option>
                                @foreach ($semen as $estudio)
                                    <option value="{{ $estudio['id'] }}" data-nombre="{{ $estudio['nombre'] }}">
                                        {{ $estudio['nombre'] }}</option>
                                @endforeach
                            </select>
                            <button type="button" id="add-semen" class="btn btn-outline-primary flex-shrink-0"><i
                                    class="fas fa-plus me-1"></i> Agregar</button>
                        </div>
                        <ul id="semen-list" class="list-group"></ul>
                    </div>


                    <div class="mt-4 pt-3 border-top d-flex justify-content-end">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-2"></i> Guardar Estudios Asignados
                        </button>
                    </div>
                </form>

            </div> {{-- card-body --}}
        </div> {{-- card --}}
    </div> {{-- wrapper --}}
@endsection

@section('scripts')
    {{-- Se mantiene tu llamada al archivo JS externo --}}
    <script src="{{ asset('js/estudios.js') }}"></script>
@endsection
