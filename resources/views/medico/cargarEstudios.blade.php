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

    <div class="space-y-6">

        {{-- ESTUDIOS PENDIENTES --}}
        <div class="card p-6">
            <h2 class="text-xl font-semibold mb-6 text-gray-900 flex items-center">
                <i class="fas fa-flask text-blue-600 mr-3"></i>
                Estudios pendientes de carga
            </h2>

            @if ($estudiosPendientes->isEmpty())
                <div class="text-center py-8">
                    <i class="fas fa-check-circle text-green-500 text-4xl mb-3"></i>
                    <p class="text-gray-600">No hay estudios pendientes.</p>
                </div>
            @else
                <form action="{{ route('tratamiento.guardar-estudios', $tratamiento->id) }}" method="POST">
                    @csrf

                    <div class="space-y-6">

                        @foreach ($estudiosPendientes as $tipo => $grupo)
                            {{-- CABECERA DEL TIPO --}}
                            <div class="border-b border-gray-200 pb-3">
                                <h3 class="text-lg font-semibold text-gray-900 capitalize">
                                    {{ $tipo }}
                                </h3>
                            </div>

                            @if ($tipo === 'semen')
                                {{-- Campos específicos para análisis de semen --}}
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                                    <h4 class="font-semibold text-blue-900 mb-3">Evaluación de viabilidad del semen</h4>

                                    <div class="space-y-3">
                                        <p class="text-sm text-gray-700 mb-3">¿El semen de la pareja es viable para
                                            fertilización?</p>

                                        <div class="flex space-x-4">
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

                            <div class="grid gap-3">
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
                        @endforeach

                    </div>

                    <div class="flex justify-end mt-6 pt-4 border-t border-gray-200">
                        @if (session('rol') == 3)
                            <button disabled class="btn-secondary opacity-50 cursor-not-allowed">
                                <i class="fas fa-eye mr-2"></i> Solo lectura
                            </button>
                        @else
                            <button class="btn-primary">
                                <i class="fas fa-save mr-2"></i> Guardar Resultados
                            </button>
                        @endif
                    </div>

                </form>

            @endif
        </div>


        {{-- ESTUDIOS YA CARGADOS --}}
        <div class="card p-6">
            <h2 class="text-xl font-semibold mb-6 text-gray-900 flex items-center">
                <i class="fas fa-clipboard-check text-green-600 mr-3"></i>
                Estudios completados
            </h2>

            @if ($estudiosCompletados->isEmpty())
                <div class="text-center py-8">
                    <i class="fas fa-file-medical text-gray-400 text-4xl mb-3"></i>
                    <p class="text-gray-500">No hay estudios cargados todavía.</p>
                </div>
            @else
                <div class="space-y-6">

                    @foreach ($estudiosCompletados as $tipo => $grupo)
                        <div class="border-b border-gray-200 pb-3">
                            <h3 class="text-lg font-semibold text-gray-900 capitalize">
                                {{ $tipo }}
                            </h3>
                        </div>

                        <div class="grid gap-3">
                            @foreach ($grupo as $estudio)
                                <div class="border border-gray-200 rounded-lg p-3 bg-gray-50">
                                    <div class="flex items-start justify-between mb-2">
                                        <h4 class="font-medium text-gray-900 text-sm">
                                            {{ $estudio->nombre }}
                                        </h4>
                                        <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">
                                            Completado
                                        </span>
                                    </div>
                                    <div class="mt-2 p-2 bg-white rounded border-l-4 border-green-500">
                                        <p class="text-gray-700 whitespace-pre-line text-sm">
                                            {{ $estudio->resultado }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach

                </div>

            @endif
        </div>

    </div>

@endsection
