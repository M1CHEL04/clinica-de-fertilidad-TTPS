@extends('layouts.layoutInterno')
@section('title', 'Donación de Gametos')

@section('page-header')
    <div class="page-header">
        <div>
            <h1 class="page-title">Donación de Gametos</h1>
            <p class="page-subtitle">Registrar una nueva donación</p>
        </div>
        <div>
            <a href="{{ route('operador.home') }}" class="btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i> Volver al Inicio
            </a>
        </div>
    </div>
@endsection

@section('content')

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Columna principal - Formulario -->
        <div class="lg:col-span-2">
            <div class="card p-6">
                <h3 class="text-lg font-semibold mb-4 text-gray-900 flex items-center">
                    Registrar nueva donación
                </h3>

                <form action="{{ route('donacion.registrar') }}" method="POST" class="space-y-4">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Tipo de donación -->
                        <div>
                            <label class="form-label">Tipo de donación *</label>
                            <select name="tipo_donacion" class="form-input" required>
                                <option value="">Seleccionar tipo</option>
                                @foreach ($enums['gamete_type'] as $value)
                                    <option value="{{ $value }}">{{ ucfirst(str_replace('_', ' ', $value)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Altura -->
                        <div>
                            <label class="form-label">Altura (cm) *</label>
                            <input type="number" name="altura" class="form-input" min="0" placeholder="170"
                                required>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Color de ojos -->
                        <div>
                            <label class="form-label">Color de ojos *</label>
                            <select name="color_ojos" class="form-input" required>
                                <option value="">Seleccionar color</option>
                                @foreach ($enums['eye_color'] as $value)
                                    <option value="{{ $value }}">{{ ucfirst(str_replace('_', ' ', $value)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Color de pelo -->
                        <div>
                            <label class="form-label">Color de pelo *</label>
                            <select name="color_pelo" class="form-input" required>
                                <option value="">Seleccionar color</option>
                                @foreach ($enums['hair_color'] as $value)
                                    <option value="{{ $value }}">{{ ucfirst(str_replace('_', ' ', $value)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Tipo de pelo -->
                        <div>
                            <label class="form-label">Tipo de pelo *</label>
                            <select name="tipo_pelo" class="form-input" required>
                                <option value="">Seleccionar tipo</option>
                                @foreach ($enums['hair_type'] as $value)
                                    <option value="{{ $value }}">{{ ucfirst(str_replace('_', ' ', $value)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Complexión -->
                        <div>
                            <label class="form-label">Complexión *</label>
                            <select name="complexion" class="form-input" required>
                                <option value="">Seleccionar complexión</option>
                                @foreach ($enums['complexion'] as $value)
                                    <option value="{{ $value }}">{{ ucfirst(str_replace('_', ' ', $value)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Etnicidad -->
                    <div>
                        <label class="form-label">Etnicidad *</label>
                        <select name="etnicidad" class="form-input" required>
                            <option value="">Seleccionar etnicidad</option>
                            @foreach ($enums['ethnicity'] as $value)
                                <option value="{{ $value }}">{{ ucfirst(str_replace('_', ' ', $value)) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Botón submit -->
                    <div class="flex justify-end mt-6 pt-4 border-t border-gray-200">
                        <button type="submit" class="btn-primary">
                            <i class="fas fa-heart mr-2"></i> Registrar Donación
                        </button>
                    </div>

                </form>
            </div>
        </div>

        <!-- Columna lateral -->
        <div class="space-y-6">

            <!-- Información -->
            <div class="card p-6">
                <h3 class="text-lg font-semibold mb-4 text-gray-900 flex items-center">
                    <i class="fas fa-info-circle text-blue-600 mr-2"></i> Información
                </h3>

                <div class="space-y-3 text-sm text-gray-600">
                    <p><strong class="text-gray-900">Donación de Gametos</strong></p>
                    <p>Complete todos los campos fenotípicos del donante para registrar la donación en el banco de gametos.
                    </p>
                    <p>Esta información será utilizada para buscar compatibilidades en futuros tratamientos.</p>
                </div>
            </div>

            <!-- Campos requeridos -->
            <div class="card p-4 bg-yellow-50 border border-yellow-200">
                <h4 class="font-medium text-yellow-800 mb-2 flex items-center">
                    <i class="fas fa-exclamation-triangle text-yellow-600 mr-2"></i>
                    Campos Requeridos
                </h4>
                <p class="text-sm text-yellow-700">
                    Todos los campos marcados con (*) son obligatorios para completar el registro de la donación.
                </p>
            </div>

        </div>

    </div>

@endsection
