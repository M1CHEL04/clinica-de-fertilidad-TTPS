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

        <form method="POST" action="{{ route('paciente.store-turno') }}" class="grid lg:grid-cols-2 gap-8">
            @csrf

            <!-- Datos del Paciente -->
            <div class="bg-white shadow-md rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4 border-b border-gray-200 pb-2">
                    <i class="fas fa-user text-blue-600 mr-2"></i>
                    Datos del paciente
                </h2>

                <div class="grid md:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                        <input type="text" name="nombre" value="{{ Auth::user()->nombre }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-gray-50"
                            readonly>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Apellido</label>
                        <input type="text" name="apellido" value="{{ Auth::user()->apellido }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-gray-50"
                            readonly>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" value="{{ Auth::user()->mail }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-gray-50"
                            readonly>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">DNI</label>
                        <input type="text" name="dni" value="{{ Auth::user()->dni }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-gray-50"
                            readonly>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Fecha de nacimiento</label>
                        <input type="date" name="fecha_nacimiento" value="{{ Auth::user()->fecha_nacimiento }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-gray-50"
                            readonly>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
                        <input type="tel" name="telefono" value="{{ Auth::user()->telefono }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-gray-50"
                            readonly>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Ocupación</label>
                        <input type="text" name="ocupacion" value="{{ Auth::user()->ocupacion }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-gray-50"
                            readonly>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Obra Social</label>
                        <input type="text" name="obra_social"
                            value="{{ Auth::user()->obra_social_nombre ?? 'Sin obra social' }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-gray-50"
                            readonly>
                    </div>
                    @if (Auth::user()->obra_social_id)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Número de afiliado</label>
                            <input type="text" name="numero_afiliado" value="{{ Auth::user()->numero_afiliado }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-gray-50"
                                readonly>
                        </div>
                    @endif
                </div>

                <!-- Selección de Profesional -->
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Profesional *</label>
                    <select name="medico_id" id="medico_id"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                        required>
                        <option value="">Seleccionar profesional</option>
                        @foreach ($medicos as $medico)
                            <option value="{{ $medico->id }}">Dr/a. {{ $medico->nombre }} {{ $medico->apellido }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Calendario -->
            <div class="bg-white shadow-md rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4 border-b border-gray-200 pb-2">
                    <i class="fas fa-calendar-alt text-blue-600 mr-2"></i>
                    Seleccionar fecha y hora
                </h2>

                <!-- Calendario -->
                <div class="mb-6">
                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <div class="flex justify-between items-center mb-4">
                            <button type="button" id="prev-month" class="p-2 rounded-lg hover:bg-gray-100">
                                <i class="fas fa-chevron-left text-gray-600"></i>
                            </button>
                            <h3 class="text-lg font-semibold" id="calendar-month-year"></h3>
                            <button type="button" id="next-month" class="p-2 rounded-lg hover:bg-gray-100">
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
                    <input type="hidden" name="paciente_id" value="{{ Auth::user()->id }}" required>
                </div>

                <!-- Horarios disponibles -->
                <div id="horarios-container" class="hidden">
                    <h4 class="font-medium text-gray-800 mb-3">Horarios disponibles</h4>
                    <div id="horarios-grid" class="grid grid-cols-3 gap-2">
                        <!-- Los horarios se cargan dinámicamente -->
                    </div>
                    <input type="hidden" name="hora_turno" id="hora-seleccionada" required>
                </div>

                <!-- Botón de envío -->
                <div class="mt-6 pt-4 border-t border-gray-200">
                    <button type="submit"
                        class="btn-primary text-white px-6 py-2.5 rounded-lg font-medium text-center hover:shadow-md transition-all w-full">
                        <i class="fas fa-calendar-check mr-2"></i>
                        Solicitar Turno
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script>
        // Variable global para la URL base de turnos libres
        window.turnosLibresBaseUrl = '{{ url('/paciente/turnos-libres') }}';
    </script>

    <!-- Importar archivo JavaScript del calendario -->
    <script src="{{ asset('js/calendario.js') }}"></script>
@endsection
