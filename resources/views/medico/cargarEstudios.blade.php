@extends('layouts.layoutInterno')

@section('title', 'Cargar Estudios')

@section('page-header')
    <div class="page-header">
        <div>
            <h1 class="page-title">Cargar estudios</h1>
            <p class="page-subtitle">Completá o revisá los estudios del tratamiento.</p>
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
        <div class="lg:col-span-2 space-y-6">

            {{-- ESTUDIOS PENDIENTES --}}
            <div class="card p-6">
                <h3 class="text-lg font-semibold mb-4 text-gray-900">
                    Estudios pendientes de carga
                </h3>

                @if ($estudiosPendientes->isEmpty())
                    <div class="text-center py-8">
                        <div class="w-16 h-16 mx-auto mb-4 bg-green-100 rounded-full flex items-center justify-center">
                            <div class="w-8 h-8 bg-green-500 rounded-full"></div>
                        </div>
                        <p class="text-gray-600">No hay estudios pendientes.</p>
                    </div>
                @else
                    <form action="{{ route('tratamiento.guardar-estudios', $tratamiento->id) }}" method="POST"
                        class="space-y-6">
                        @csrf

                        @foreach ($estudiosPendientes as $tipo => $grupo)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <h4 class="form-label mb-3 capitalize">
                                    {{ $tipo }}
                                </h4>

                                @if ($tipo === 'semen')
                                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                                        <h5 class="font-medium text-blue-900 mb-3">Evaluación de viabilidad del semen</h5>

                                        <div class="space-y-3">
                                            <p class="text-sm text-gray-700 mb-3">¿El semen de la pareja es viable para
                                                fertilización?</p>

                                            <div class="flex gap-4">
                                                <label
                                                    class="flex items-center p-3 bg-white rounded-lg border cursor-pointer hover:bg-gray-50 transition-colors">
                                                    <input type="radio" name="viabilidad_semen_{{ $tratamiento->id }}"
                                                        value="positivo" class="mr-3 text-green-600"
                                                        {{ isset($antecedentePareja) && $antecedentePareja->semen_viable === 1 ? 'checked' : '' }}>
                                                    <span class="text-sm font-medium text-green-700">Viable</span>
                                                </label>

                                                <label
                                                    class="flex items-center p-3 bg-white rounded-lg border cursor-pointer hover:bg-gray-50 transition-colors">
                                                    <input type="radio" name="viabilidad_semen_{{ $tratamiento->id }}"
                                                        value="negativo" class="mr-3 text-red-600"
                                                        {{ isset($antecedentePareja) && $antecedentePareja->semen_viable === 0 ? 'checked' : '' }}>
                                                    <span class="text-sm font-medium text-red-700">No viable</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <div class="space-y-3">
                                    @foreach ($grupo as $estudio)
                                        <div class="border border-gray-200 rounded-lg p-3 bg-white">
                                            <label class="form-label text-sm">
                                                {{ $estudio->nombre }}
                                            </label>
                                            <textarea name="resultados[{{ $estudio->id }}]" class="form-input mt-1" rows="2"
                                                placeholder="Ingrese el resultado del estudio..."></textarea>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach

                        <div class="flex justify-end pt-4 border-t border-gray-200">
                            @if (session('rol') == 3)
                                <button disabled class="btn-secondary opacity-50 cursor-not-allowed">
                                    Solo lectura
                                </button>
                            @else
                                <button class="btn-primary">
                                    Guardar Resultados
                                </button>
                            @endif
                        </div>

                    </form>
                @endif
            </div>

            {{-- ESTUDIOS COMPLETADOS --}}
            <div class="card p-6">
                <h3 class="text-lg font-semibold mb-4 text-gray-900">
                    Estudios completados
                </h3>

                @if ($estudiosCompletados->isEmpty())
                    <div class="text-center py-8">
                        <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                            <div class="w-8 h-8 bg-gray-400 rounded-full"></div>
                        </div>
                        <p class="text-gray-500">No hay estudios cargados todavía.</p>
                    </div>
                @else
                    <div class="space-y-6">
                        @foreach ($estudiosCompletados as $tipo => $grupo)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <h4 class="form-label mb-3 capitalize">
                                    {{ $tipo }}
                                </h4>

                                <div class="space-y-3">
                                    @foreach ($grupo as $estudio)
                                        <div class="border border-gray-200 rounded-lg p-3 bg-gray-50">
                                            <div class="flex items-start justify-between mb-2">
                                                <h5 class="font-medium text-gray-900 text-sm">
                                                    {{ $estudio->nombre }}
                                                </h5>
                                                <span
                                                    class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full flex-shrink-0">
                                                    Completado
                                                </span>
                                            </div>
                                            <div class="mt-2 p-3 bg-white rounded border-l-4 border-green-500">
                                                <p class="text-gray-700 whitespace-pre-line text-sm">
                                                    {{ $estudio->resultado }}
                                                </p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>

        <!-- Columna lateral -->
        <div class="space-y-6">

            <!-- Información del Tratamiento -->
            <div class="card p-6">
                <h3 class="text-lg font-semibold mb-4 text-gray-900">
                    Información del Tratamiento
                </h3>

                <div class="space-y-2 text-sm text-gray-700">
                    <p><strong>ID Tratamiento:</strong> #{{ $tratamiento->id }}</p>
                    @if (isset($tratamiento->paciente))
                        <p><strong>Paciente:</strong> {{ $tratamiento->paciente->nombre ?? 'N/A' }}
                            {{ $tratamiento->paciente->apellido ?? '' }}</p>
                        @if (isset($tratamiento->paciente->dni))
                            <p><strong>DNI:</strong> {{ $tratamiento->paciente->dni }}</p>
                        @endif
                    @endif
                    @if (isset($tratamiento->objetivo))
                        <p><strong>Objetivo:</strong> {{ $tratamiento->objetivo->nombre ?? 'N/A' }}</p>
                    @endif
                    @if (isset($tratamiento->estado_tratamiento))
                        <p><strong>Estado:</strong> {{ $tratamiento->estado_tratamiento->nombre ?? 'N/A' }}</p>
                    @endif
                    <p><strong>Fecha Inicio:</strong>
                        {{ \Carbon\Carbon::parse($tratamiento->created_at)->format('d/m/Y') }}</p>
                    @if (isset($tratamiento->medico))
                        <p><strong>Médico Asignado:</strong> {{ $tratamiento->medico->nombre ?? 'N/A' }}
                            {{ $tratamiento->medico->apellido ?? '' }}</p>
                    @endif
                </div>
            </div>

            <!-- Instrucciones -->
            <div class="card p-4 bg-blue-50 border border-blue-200">
                <h4 class="font-medium text-blue-800 mb-2">
                    Instrucciones
                </h4>
                <p class="text-sm text-blue-700">
                    Complete los resultados de los estudios pendientes. Para estudios de semen, indique primero la
                    viabilidad antes de cargar resultados.
                </p>
            </div>

        </div>

    </div>

@endsection
