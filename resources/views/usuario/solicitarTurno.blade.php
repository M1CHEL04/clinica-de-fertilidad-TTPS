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
                    <select name="medico_id"
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
        // Variables globales
        let currentDate = new Date();
        let selectedDate = null;
        let selectedTime = null;

        // Horarios disponibles (se pueden obtener del backend)
        const availableTimes = ['09:00', '09:30', '10:00', '10:30', '11:00', '11:30', '14:00', '14:30', '15:00', '15:30',
            '16:00', '16:30'
        ];

        // Inicializar calendario
        document.addEventListener('DOMContentLoaded', function() {
            renderCalendar();

            document.getElementById('prev-month').addEventListener('click', function() {
                currentDate.setMonth(currentDate.getMonth() - 1);
                renderCalendar();
            });

            document.getElementById('next-month').addEventListener('click', function() {
                currentDate.setMonth(currentDate.getMonth() + 1);
                renderCalendar();
            });
        });

        // Renderizar calendario
        function renderCalendar() {
            const monthNames = [
                'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
                'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
            ];

            const firstDay = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
            const lastDay = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0);
            const today = new Date();

            document.getElementById('calendar-month-year').textContent =
                `${monthNames[currentDate.getMonth()]} ${currentDate.getFullYear()}`;

            const daysContainer = document.getElementById('calendar-days');
            daysContainer.innerHTML = '';

            // Días vacíos al inicio
            for (let i = 0; i < firstDay.getDay(); i++) {
                const emptyDay = document.createElement('div');
                emptyDay.className = 'h-10';
                daysContainer.appendChild(emptyDay);
            }

            // Días del mes
            for (let day = 1; day <= lastDay.getDate(); day++) {
                const dayElement = document.createElement('button');
                dayElement.type = 'button';
                dayElement.className = 'h-10 w-10 rounded-lg text-sm font-medium transition-colors hover:bg-blue-100';
                dayElement.textContent = day;

                const dayDate = new Date(currentDate.getFullYear(), currentDate.getMonth(), day);

                // Deshabilitar días pasados y fines de semana
                if (dayDate < today.setHours(0, 0, 0, 0) || dayDate.getDay() === 0 || dayDate.getDay() === 6) {
                    dayElement.disabled = true;
                    dayElement.className += ' text-gray-400 cursor-not-allowed';
                } else {
                    dayElement.className += ' text-gray-700 hover:text-blue-600';
                    dayElement.addEventListener('click', function() {
                        selectDate(dayDate, dayElement);
                    });
                }

                daysContainer.appendChild(dayElement);
            }
        }

        // Seleccionar fecha
        function selectDate(date, element) {
            // Limpiar selección anterior
            document.querySelectorAll('#calendar-days button').forEach(btn => {
                btn.classList.remove('bg-blue-600', 'text-white');
                btn.classList.add('text-gray-700');
            });

            // Marcar nueva selección
            element.classList.add('bg-blue-600', 'text-white');
            element.classList.remove('text-gray-700');

            selectedDate = date;
            document.getElementById('fecha-seleccionada').value = date.toISOString().split('T')[0];

            // Mostrar horarios
            showAvailableTimes();
        }

        // Mostrar horarios disponibles
        function showAvailableTimes() {
            const container = document.getElementById('horarios-container');
            const grid = document.getElementById('horarios-grid');

            container.classList.remove('hidden');
            grid.innerHTML = '';

            availableTimes.forEach(time => {
                const timeButton = document.createElement('button');
                timeButton.type = 'button';
                timeButton.className =
                    'p-2 text-sm border border-gray-300 rounded-lg hover:border-blue-500 hover:text-blue-600 transition-colors';
                timeButton.textContent = time;

                timeButton.addEventListener('click', function() {
                    selectTime(time, timeButton);
                });

                grid.appendChild(timeButton);
            });
        }

        // Seleccionar hora
        function selectTime(time, element) {
            // Limpiar selección anterior
            document.querySelectorAll('#horarios-grid button').forEach(btn => {
                btn.classList.remove('bg-blue-600', 'text-white', 'border-blue-600');
                btn.classList.add('border-gray-300');
            });

            // Marcar nueva selección
            element.classList.add('bg-blue-600', 'text-white', 'border-blue-600');
            element.classList.remove('border-gray-300');

            selectedTime = time;
            document.getElementById('hora-seleccionada').value = time;
        }
    </script>
@endsection
