@extends('layouts.layoutInterno')
@section('title', 'Asignar Estudios Médicos')

@section('styles')
    <style>
        .list-group-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 12px;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            margin-bottom: 4px;
            font-size: 14px;
        }

        .list-group-item .btn {
            padding: 4px 8px;
            font-size: 12px;
        }

        .btn-outline-danger {
            color: #dc3545;
            border-color: #dc3545;
            background-color: white;
        }

        .btn-outline-danger:hover {
            color: white;
            background-color: #dc3545;
            border-color: #dc3545;
        }
    </style>
@endsection

@section('page-header')
    <div class="page-header">
        <div>
            <h1 class="page-title">Asignar estudios médicos</h1>
            <p class="page-subtitle">Seleccionar estudios requeridos para el paciente {{ $paciente->nombre ?? 'N/A' }}</p>
        </div>
        <div>
            <a href="{{ route('medico.tratamiento.detalle', $tratamiento->id) }}" class="btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i> Volver al Tratamiento
            </a>
        </div>
    </div>
@endsection


@section('content')

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Columna principal -->
        <div class="lg:col-span-2">
            <div class="card p-6">
                <h3 class="text-lg font-semibold mb-4 text-gray-900 flex items-center">
                    <i class="fas fa-microscope text-blue-600 mr-2"></i> Asignación de Estudios
                </h3>

                <form action="{{ route('estudios.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <input type="hidden" name="paciente_id" value="{{ $paciente->id }}">

                    <!-- Estudios Ginecológicos -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <label class="form-label flex items-center mb-3">
                            <i class="fas fa-venus text-blue-600 mr-2"></i>
                            Estudios Ginecológicos
                        </label>
                        <div class="flex gap-3 mb-3">
                            <select id="ginecologicos-select" class="form-input flex-1">
                                <option value="">Seleccione un estudio...</option>
                                @foreach ($ginecologicos as $estudio)
                                    <option value="{{ $estudio['id'] }}" data-nombre="{{ $estudio['nombre'] }}">
                                        {{ $estudio['nombre'] }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="button" id="add-ginecologico" class="btn-secondary">
                                <i class="fas fa-plus mr-1"></i> Agregar
                            </button>
                        </div>
                        <ul id="ginecologicos-list" class="space-y-2"></ul>
                    </div>

                    <!-- Estudios Hormonales -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <label class="form-label flex items-center mb-3">
                            <i class="fas fa-flask text-blue-600 mr-2"></i>
                            Estudios Hormonales
                        </label>
                        <div class="flex gap-3 mb-3">
                            <select id="hormonales-select" class="form-input flex-1">
                                <option value="">Seleccione un estudio...</option>
                                @foreach ($hormonales as $estudio)
                                    <option value="{{ $estudio['id'] }}" data-nombre="{{ $estudio['nombre'] }}">
                                        {{ $estudio['nombre'] }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="button" id="add-hormonal" class="btn-secondary">
                                <i class="fas fa-plus mr-1"></i> Agregar
                            </button>
                        </div>
                        <ul id="hormonales-list" class="space-y-2"></ul>
                    </div>

                    <!-- Prequirúrgicos -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <label class="form-label flex items-center mb-3">
                            <i class="fas fa-heartbeat text-blue-600 mr-2"></i>
                            Prequirúrgicos
                        </label>
                        <div class="flex gap-3 mb-3">
                            <select id="prequirurgicos-select" class="form-input flex-1">
                                <option value="">Seleccione un estudio...</option>
                                @foreach ($prequirurgicos as $estudio)
                                    <option value="{{ $estudio['id'] }}" data-nombre="{{ $estudio['nombre'] }}">
                                        {{ $estudio['nombre'] }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="button" id="add-prequirurgico" class="btn-secondary">
                                <i class="fas fa-plus mr-1"></i> Agregar
                            </button>
                        </div>
                        <ul id="prequirurgicos-list" class="space-y-2"></ul>
                    </div>

                    <!-- Estudios de Semen -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <label class="form-label flex items-center mb-3">
                            <i class="fas fa-tint text-blue-600 mr-2"></i>
                            Estudios de Semen
                        </label>
                        <div class="flex gap-3 mb-3">
                            <select id="semen-select" class="form-input flex-1">
                                <option value="">Seleccione un estudio...</option>
                                @foreach ($semen as $estudio)
                                    <option value="{{ $estudio['id'] }}" data-nombre="{{ $estudio['nombre'] }}">
                                        {{ $estudio['nombre'] }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="button" id="add-semen" class="btn-secondary">
                                <i class="fas fa-plus mr-1"></i> Agregar
                            </button>
                        </div>
                        <ul id="semen-list" class="space-y-2"></ul>
                    </div>

                    <!-- Botón de guardar -->
                    <div class="flex justify-end pt-4 border-t border-gray-200">
                        <button type="submit" class="btn-primary">
                            <i class="fas fa-save mr-2"></i> Guardar estudios asignados
                        </button>
                    </div>

                </form>
            </div>
        </div>

        <!-- Columna lateral -->
        <div class="space-y-6">

            <!-- Información del Paciente -->
            <div class="card p-6">
                <h3 class="text-lg font-semibold mb-4 text-gray-900 flex items-center">
                    <i class="fas fa-user text-blue-600 mr-2"></i> Información del Paciente
                </h3>

                <div class="space-y-2 text-sm text-gray-700">
                    <p><strong>Nombre:</strong> {{ $paciente->nombre ?? 'N/A' }}</p>
                    @if (isset($paciente->apellido))
                        <p><strong>Apellido:</strong> {{ $paciente->apellido }}</p>
                    @endif
                    @if (isset($paciente->dni))
                        <p><strong>DNI:</strong> {{ $paciente->dni }}</p>
                    @endif
                </div>
            </div>

            <!-- Instrucciones -->
            <div class="card p-4 bg-blue-50 border border-blue-200">
                <h4 class="font-medium text-blue-800 mb-2 flex items-center">
                    <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                    Instrucciones
                </h4>
                <p class="text-sm text-blue-700">
                    Seleccione los estudios requeridos de cada categoría y luego haga clic en "Agregar" para incluirlos en
                    la lista.
                </p>
            </div>

        </div>

    </div>
@endsection

@section('scripts')
    {{-- Se mantiene tu llamada al archivo JS externo --}}
    <script src="{{ asset('js/estudios.js') }}"></script>
@endsection
