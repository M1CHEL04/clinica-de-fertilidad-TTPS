@extends('layouts.layoutInterno')
@section('title', 'Protocolo de Estimulación - Fertilia')

@section('page-header')
    <div class="page-header">
        <div>
            <h1 class="page-title">Protocolo de Estimulación</h1>
            <p class="page-subtitle">Gestión del protocolo y documentación del tratamiento</p>
        </div>
        @if (session('rol') == 3)
            <div>
                <a href="{{ route('operador.tratamiento.detalle', $tratamiento->id) }}" class="btn-secondary">
                    <i class="fas fa-arrow-left mr-2"></i> Volver al Tratamiento
                </a>
            </div>
        @elseif (session('rol') == 5)
            <div>
                <a href="{{ route('jefe.tratamiento.detalle', $tratamiento->id) }}" class="btn-secondary">
                    <i class="fas fa-arrow-left mr-2"></i> Volver al Tratamiento
                </a>
            </div>
        @else
            <div>
                <a href="{{ route('medico.tratamiento.detalle', $tratamiento->id) }}" class="btn-secondary">
                    <i class="fas fa-arrow-left mr-2"></i> Volver al Tratamiento
                </a>
            </div>
        @endif
    </div>
@endsection

@section('content')

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Columna principal -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Protocolo de Estimulación -->
            <div class="card p-6">
                <h3 class="text-lg font-semibold mb-4 text-gray-900 flex items-center">
                    <i class="fas fa-pills text-blue-600 mr-2"></i> Protocolo de Estimulación
                </h3>

                <!-- Medicaciones existentes -->
                @if ($protocolo->count() > 0)
                    <div class="space-y-3 mb-6">
                        @foreach ($protocolo as $med)
                            <div class="bg-gray-50 p-4 rounded-lg border">
                                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                    <div>
                                        <p class="font-medium text-gray-600">Tipo</p>
                                        <p class="text-gray-900">{{ $med->tipoMedicacion->nombre }}</p>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-600">Dosis</p>
                                        <p class="text-gray-900">{{ $med->dosis }}</p>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-600">Tiempo</p>
                                        <p class="text-gray-900">{{ $med->tiempo }} días</p>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-600">Droga</p>
                                        <p class="text-gray-900">{{ $med->droga }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                <!-- Formulario para agregar medicación -->
                <form method="POST"
                    action="{{ session('rol') == 5
                        ? route('jefe.tratamiento.guardar-protocolo', $tratamiento->id)
                        : route('tratamiento.guardar-protocolo', $tratamiento->id) }}"
                    class="space-y-6">
                    @csrf

                    <div class="border border-gray-200 rounded-lg p-4">
                        <label class="form-label flex items-center mb-3">
                            <i class="fas fa-plus-circle text-blue-600 mr-2"></i>
                            Agregar nueva medicación
                        </label>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="form-label">Tipo de medicación</label>
                                <select name="tipo_medicacion_id" class="form-input">
                                    <option value="" selected disabled>Seleccionar tipo de medicación</option>
                                    @foreach ($tiposMedicacion as $tipo)
                                        <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="form-label">Dosis</label>
                                <input type="text" name="dosis" class="form-input" placeholder="Ej: 150 UI">
                            </div>

                            <div>
                                <label class="form-label">Tiempo (días)</label>
                                <input type="number" name="tiempo" class="form-input" placeholder="Ej: 10">
                            </div>

                            <div>
                                <label class="form-label">Droga</label>
                                <input type="text" name="droga" class="form-input" placeholder="Nombre comercial">
                            </div>
                        </div>
                    </div>

                    <!-- Botón de guardar -->
                    <div class="flex justify-end pt-4 border-t border-gray-200">
                        <button type="submit" class="btn-primary">
                            <i class="fas fa-plus mr-2"></i> Agregar medicación
                        </button>
                    </div>

                </form>
            </div>

            <!-- Consentimiento Informado -->
            <div class="card p-6">
                <h3 class="text-lg font-semibold mb-4 text-gray-900 flex items-center">
                    <i class="fas fa-file-signature text-blue-600 mr-2"></i> Consentimiento Informado
                </h3>

                @if ($tratamiento->consentimiento_pdf)
                    <div class="bg-green-50 border border-green-200 p-4 rounded-lg">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <i class="fas fa-check-circle text-green-600 text-xl mr-3"></i>
                                <div>
                                    <p class="font-medium text-green-900">Documento cargado correctamente</p>
                                    <p class="text-sm text-green-600">PDF firmado disponible para descarga</p>
                                </div>
                            </div>
                            <a href="{{ route('tratamiento.descargar-consentimiento', $tratamiento->id) }}"
                                class="btn-primary">
                                <i class="fas fa-download mr-2"></i>
                                Descargar
                            </a>
                        </div>
                    </div>
                @else
                    <form 
    action="{{ session('rol') == 5 
        ? route('jefe.tratamiento.subir-consentimiento', $tratamiento->id) 
        : route('tratamiento.subir-consentimiento', $tratamiento->id) 
    }}" 
    method="POST"
    enctype="multipart/form-data"
>

                        @csrf

                        <div class="border border-gray-200 rounded-lg p-4">
                            <label class="form-label flex items-center mb-3">
                                <i class="fas fa-upload text-blue-600 mr-2"></i>
                                Subir PDF firmado
                            </label>
                            <input type="file" name="consentimiento" accept="application/pdf" class="form-input">
                        </div>

                        <div class="flex justify-end pt-4 border-t border-gray-200">
                            <button type="submit" class="btn-primary">
                                <i class="fas fa-upload mr-2"></i>
                                Subir consentimiento
                            </button>
                        </div>
                    </form>
                @endif
            </div>

            <!-- Orden Médica -->
            <div class="card p-6">
                <h3 class="text-lg font-semibold mb-4 text-gray-900 flex items-center">
                    <i class="fas fa-prescription text-blue-600 mr-2"></i> Orden Médica
                </h3>

                @if (!$tratamiento->consentimiento_pdf)
                    <div class="bg-gray-50 p-6 rounded-lg text-center">
                        <i class="fas fa-lock text-gray-400 text-2xl mb-3"></i>
                        <p class="text-gray-500 mb-2">Función bloqueada</p>
                        <p class="text-gray-600 text-sm">Debe cargar el consentimiento informado para habilitar el envío de
                            la orden médica</p>
                    </div>
                @else
                    <form 
    action="{{ session('rol') == 5 
        ? route('jefe.tratamiento.enviar-orden-medica', $tratamiento->id) 
        : route('tratamiento.enviar-orden-medica', $tratamiento->id) 
    }}" 
    method="POST"
>

                        @csrf
                        <p class="text-gray-600 mb-4">El consentimiento ha sido cargado. Puede proceder a enviar la orden
                            médica.</p>
                        <button type="submit" class="btn-primary">
                            <i class="fas fa-paper-plane mr-2"></i>
                            Enviar orden médica
                        </button>
                    </form>
                @endif
            </div>

        </div>

        <!-- Columna lateral -->
        <div class="space-y-6">

            <!-- Información del Tratamiento -->
            <div class="card p-6">
                <h3 class="text-lg font-semibold mb-4 text-gray-900 flex items-center">
                    <i class="fas fa-info-circle text-blue-600 mr-2"></i> Información del Tratamiento
                </h3>

                <div class="space-y-2 text-sm text-gray-700">
                    <p><strong>Paciente:</strong> {{ $tratamiento->historiaClinica->paciente->nombre ?? 'N/A' }}
                        {{ $tratamiento->historiaClinica->paciente->apellido ?? '' }}
                    </p>
                    <p><strong>DNI:</strong> {{ $tratamiento->historiaClinica->paciente->dni ?? 'N/A' }}</p>
                    <p><strong>Etapa:</strong> {{ $tratamiento->etapa->nombre ?? 'N/A' }}</p>
                </div>
            </div>

            <!-- Instrucciones -->
            <div class="card p-4 bg-blue-50 border border-blue-200">
                <h4 class="font-medium text-blue-800 mb-2 flex items-center">
                    <i class="fas fa-lightbulb text-blue-600 mr-2"></i>
                    Instrucciones
                </h4>
                <div class="text-sm text-blue-700 space-y-2">
                    <p>1. Complete el protocolo de estimulación con las medicaciones necesarias.</p>
                    <p>2. Cargue el consentimiento informado firmado por el paciente.</p>
                    <p>3. Una vez cargado el consentimiento, podrá enviar la orden médica.</p>
                </div>
            </div>

        </div>

    </div>

@endsection
