@extends('layouts.layoutUsuario')
@section('title', 'Solicitar Turno - Fertilia')

@section('content')
    <div class="max-w-6xl mx-auto px-4 py-6">
        <div class="mb-6 pb-4 border-b border-gray-200">
            <a href="{{ route('home') }}" class="btn-secondary mb-4 inline-block">
                <i class="fas fa-arrow-left mr-2"></i>
                Volver
            </a>
            <div class="flex justify-center items-center">
                <div class="text-center">
                    <h1 class="text-3xl font-bold text-gray-800">
                        <i class="fas fa-calendar-plus text-blue-600 mr-3"></i>
                        Solicitar turno
                    </h1>
                    <p class="text-gray-600 mt-2">Complete los datos para agendar su cita</p>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('paciente.store-turno') }}" class="grid lg:grid-cols-2 gap-6">
            @csrf

            <!-- Columna Izquierda: Datos del Paciente y Selecciones -->
            <div class="space-y-6">
                <!-- Datos del Paciente -->
                <div class="bg-white shadow-md rounded-lg p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 border-b border-gray-200 pb-2">
                        <i class="fas fa-user text-blue-600 mr-2"></i>
                        Datos del paciente
                    </h2>

                    <div class="space-y-4">
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nombre completo</label>
                                <p class="text-sm font-medium text-gray-800 p-2 bg-gray-50 rounded-md">{{ $usuario->nombre }} {{ $usuario->apellido }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">DNI</label>
                                <p class="text-sm text-gray-700 p-2 bg-gray-50 rounded-md">{{ $usuario->dni }}</p>
                            </div>
                        </div>
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                <p class="text-sm text-gray-700 p-2 bg-gray-50 rounded-md break-words">{{ $usuario->mail }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
                                <p class="text-sm text-gray-700 p-2 bg-gray-50 rounded-md">{{ $usuario->telefono }}</p>
                            </div>
                        </div>
                        @if ($usuario->obra_social_sigla)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Obra Social</label>
                                <p class="text-sm text-gray-700 p-2 bg-gray-50 rounded-md">{{ $usuario->obra_social_sigla }}
                                    @if ($usuario->numero_afiliado)
                                        <span class="text-xs text-gray-500 ml-2">N° {{ $usuario->numero_afiliado }}</span>
                                    @endif
                                </p>
                            </div>
                        @endif
                    </div>

                    <!-- Campos hidden para el formulario -->
                    <input type="hidden" name="nombre" value="{{ $usuario->nombre }}">
                    <input type="hidden" name="apellido" value="{{ $usuario->apellido }}">
                    <input type="hidden" name="email" value="{{ $usuario->mail }}">
                    <input type="hidden" name="dni" value="{{ $usuario->dni }}">
                    <input type="hidden" name="fecha_nacimiento" value="{{ $usuario->fecha_nacimiento }}">
                    <input type="hidden" name="telefono" value="{{ $usuario->telefono }}">
                    <input type="hidden" name="ocupacion" value="{{ $usuario->ocupacion }}">
                    <input type="hidden" name="obra_social" value="{{ $usuario->obra_social_sigla ?? 'Sin obra social' }}">
                    @if ($usuario->obra_social_id)
                        <input type="hidden" name="numero_afiliado" value="{{ $usuario->numero_afiliado }}">
                    @endif
                    <input type="hidden" name="paciente_id" value="{{ $usuario->id }}">
                </div>

                <!-- Selección de Profesional -->
                <div class="bg-white shadow-md rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b border-gray-200 pb-2">
                        <i class="fas fa-user-md text-blue-600 mr-2"></i>
                        Configuración del turno
                    </h3>
                    
                    <!-- Seleccionar Médico -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Profesional *</label>
                        <select name="medico_id" id="medico_id"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                            required>
                            <option value="">Seleccionar profesional</option>
                            @foreach ($medicos as $medico)
                                <option value="{{ $medico->id }}">Dr/a. {{ $medico->nombre }} {{ $medico->apellido }}
                                </option>
                            @endforeach
                        </select>
                        <!-- El spinner de carga se insertará aquí por JavaScript -->
                    </div>

                    <!-- Tipos de Búsqueda -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">Tipo de búsqueda</label>
                        <div class="space-y-3">
                            <label class="flex items-center text-sm text-gray-700 bg-gray-50 px-4 py-3 rounded-lg border border-gray-200 hover:border-blue-300 transition-colors cursor-pointer">
                                <input type="radio" name="tipo_busqueda" id="busqueda_normal" value="normal" 
                                       class="mr-3 text-blue-600 focus:ring-blue-500" checked>
                                <span class="font-medium">Ver todas las fechas disponibles</span>
                            </label>
                            <label class="flex items-center text-sm text-gray-700 bg-gray-50 px-4 py-3 rounded-lg border border-gray-200 hover:border-blue-300 transition-colors cursor-pointer">
                                <input type="radio" name="tipo_busqueda" id="busqueda_sugerida" value="sugerida" 
                                       class="mr-3 text-blue-600 focus:ring-blue-500">
                                <span class="font-medium">Tengo fecha sugerida</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Columna Derecha: Calendario y Horarios -->
            <div class="bg-white shadow-md rounded-lg p-6 h-fit">
                <h2 class="text-lg font-semibold text-gray-800 mb-6 border-b border-gray-200 pb-2">
                    <i class="fas fa-calendar-alt text-blue-600 mr-2"></i>
                    Calendario de turnos
                </h2>

                <!-- Calendario -->
                <div class="mb-6">
                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <div class="flex justify-between items-center mb-4">
                            <button type="button" id="prev-month" class="p-1 rounded-md hover:bg-gray-100">
                                <i class="fas fa-chevron-left text-gray-600"></i>
                            </button>
                            <h3 class="text-md font-semibold" id="calendar-month-year"></h3>
                            <button type="button" id="next-month" class="p-1 rounded-md hover:bg-gray-100">
                                <i class="fas fa-chevron-right text-gray-600"></i>
                            </button>
                        </div>

                        <div class="grid grid-cols-7 gap-1 mb-2">
                            <div class="text-center text-sm font-medium text-gray-500 py-2">Dom</div>
                            <div class="text-center text-sm font-medium text-gray-500 py-2">Lun</div>
                            <div class="text-center text-sm font-medium text-gray-500 py-2">Mar</div>
                            <div class="text-center text-sm font-medium text-gray-500 py-2">Mié</div>
                            <div class="text-center text-sm font-medium text-gray-500 py-2">Jue</div>
                            <div class="text-center text-sm font-medium text-gray-500 py-2">Vie</div>
                            <div class="text-center text-sm font-medium text-gray-500 py-2">Sáb</div>
                        </div>

                        <div id="calendar-days" class="grid grid-cols-7 gap-1">
                            <!-- Los días se generan dinámicamente con JavaScript -->
                        </div>
                    </div>

                    <input type="hidden" name="fecha_turno" id="fecha-seleccionada" required>
                    <input type="hidden" name="turno_id" id="turno-seleccionado" required>
                    <input type="hidden" name="medico_id" id="medico-seleccionado" required>
                </div>

                <!-- Horarios disponibles -->
                <div id="horarios-container" class="hidden">
                    <h4 class="font-medium text-gray-800 mb-3">Horarios disponibles</h4>
                    <div id="horarios-grid" class="grid grid-cols-4 sm:grid-cols-5 lg:grid-cols-6 gap-2">
                        <!-- Los horarios se cargan dinámicamente -->
                    </div>
                    <input type="hidden" name="hora_turno" id="hora-seleccionada" required>
                </div>

                <!-- Botón de envío -->
                <div class="mt-4 pt-3 border-t border-gray-200">
                    <button type="submit"
                        id="btn-solicitar"
                        disabled
                        class="btn-primary text-white px-6 py-3 rounded-lg font-medium text-center opacity-50 cursor-not-allowed hover:shadow-md transition-all w-full">
                        <i class="fas fa-calendar-check mr-2"></i>
                        Solicitar Turno
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script>
        // Variables globales para las URLs
        window.turnosLibresBaseUrl = '{{ url('/paciente/turnos-libres') }}';
        window.turnosSugeridosBaseUrl = '{{ url('/paciente/turnos-sugeridos') }}';
        window.pacienteId = '{{ $usuario->id }}';
    </script>

    <!-- Importar archivo JavaScript del calendario -->
    <script src="{{ asset('js/calendario.js') }}"></script>


    <!-- Validar Turno -->
    <script>
        function validarTurno() {
            const fecha = document.getElementById('fecha-seleccionada').value;
            const hora = document.getElementById('hora-seleccionada').value;
            const btn = document.getElementById('btn-solicitar');

            if (fecha && hora) {
                btn.disabled = false;
                btn.classList.remove("opacity-50", "cursor-not-allowed");
            } else {
                btn.disabled = true;
                btn.classList.add("opacity-50", "cursor-not-allowed");
            }
        }

        // Observa cambios automáticos
        document.getElementById('fecha-seleccionada').addEventListener('change', validarTurno);
        document.getElementById('hora-seleccionada').addEventListener('change', validarTurno);

        // Por si calendario.js setea valores por JS:
        window.validarTurno = validarTurno;
    </script>

@endsection
