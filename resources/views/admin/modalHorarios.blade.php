<!-- Modal para Gestionar Horarios -->
<div id="modalHorarios" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <!-- Header del Modal -->
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                    <i class="fas fa-clock text-blue-600 mr-2"></i>
                    Gestionar Horarios
                </h3>
                <button type="button" onclick="closeModalHorarios()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- Información del Médico -->
            <div class="mb-4 p-3 bg-blue-50 rounded-lg border border-blue-200">
                <p class="text-sm text-gray-600">Médico:</p>
                <p class="font-medium text-gray-900" id="nombreMedico">Dr/a. [Nombre]</p>
            </div>

            <!-- Formulario -->
            <form id="formHorarios" action="{{ route('admin.set_horarios') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" id="medico_id" name="medico_id" value="">

                <!-- Mensaje de Error -->
                <div id="errorMessage" class="hidden p-3 bg-red-50 border border-red-200 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>
                        <span class="text-red-700 text-sm" id="errorText"></span>
                    </div>
                </div>

                <!-- Día de la Semana -->
                <div>
                    <label for="dia_semana" class="block text-sm font-medium text-gray-700 mb-1">
                        Día de la Semana
                    </label>
                    <select id="dia_semana" name="dia_semana"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                        required>
                        <option value="">Seleccionar día</option>
                        <option value=1>Lunes</option>
                        <option value=2>Martes</option>
                        <option value=3>Miércoles</option>
                        <option value=4>Jueves</option>
                        <option value=5>Viernes</option>
                        <option value=6>Sábado</option>
                        <option value=0>Domingo</option>
                    </select>
                </div>

                <!-- Hora de Inicio -->
                <div>
                    <label for="hora_inicio" class="block text-sm font-medium text-gray-700 mb-1">
                        Hora de inicio
                    </label>
                    <input type="time" id="hora_inicio" name="hora_inicio"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                        required>
                </div>

                <!-- Hora de Fin -->
                <div>
                    <label for="hora_fin" class="block text-sm font-medium text-gray-700 mb-1">
                        Hora de fin
                    </label>
                    <input type="time" id="hora_fin" name="hora_fin"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                        required>
                </div>

                <!-- Botones del Modal -->
                <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                    <button type="button" onclick="closeModalHorarios()" class="btn-secondary px-4 py-2 text-sm">
                        Cancelar
                    </button>
                    <button type="submit" class="btn-primary px-4 py-2 text-sm text-white">
                        <i class="fas fa-plus mr-1"></i>
                        Agregar horario
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openModalHorarios(medicoId, nombre, apellido) {
        // Mostrar el modal
        document.getElementById('modalHorarios').classList.remove('hidden');

        // Establecer el ID del médico
        document.getElementById('medico_id').value = medicoId;

        // Mostrar el nombre del médico
        document.getElementById('nombreMedico').textContent = `Dr/a. ${nombre} ${apellido}`;

        // Limpiar el formulario y ocultar errores
        document.getElementById('formHorarios').reset();
        hideErrorMessage();
        document.getElementById('medico_id').value = medicoId; // Volver a establecer el ID después del reset
    }

    function closeModalHorarios() {
        // Ocultar el modal
        document.getElementById('modalHorarios').classList.add('hidden');

        // Limpiar el formulario y ocultar errores
        document.getElementById('formHorarios').reset();
        hideErrorMessage();
    }

    function showErrorMessage(message) {
        const errorContainer = document.getElementById('errorMessage');
        const errorText = document.getElementById('errorText');

        errorText.textContent = message;
        errorContainer.classList.remove('hidden');

        // Scroll al inicio del modal para mostrar el error
        errorContainer.scrollIntoView({
            behavior: 'smooth',
            block: 'nearest'
        });
    }

    function hideErrorMessage() {
        document.getElementById('errorMessage').classList.add('hidden');
    }

    // Cerrar modal al hacer clic fuera de él
    document.getElementById('modalHorarios').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModalHorarios();
        }
    });

    // Validación de horarios
    document.getElementById('formHorarios').addEventListener('submit', function(e) {
        const horaInicio = document.getElementById('hora_inicio').value;
        const horaFin = document.getElementById('hora_fin').value;

        // Ocultar mensaje de error previo
        hideErrorMessage();

        if (horaInicio && horaFin && horaInicio >= horaFin) {
            e.preventDefault();
            showErrorMessage('La hora de inicio debe ser menor que la hora de fin.');
            return false;
        }
    });

    // Ocultar error cuando el usuario modifica los campos
    document.getElementById('hora_inicio').addEventListener('input', hideErrorMessage);
    document.getElementById('hora_fin').addEventListener('input', hideErrorMessage);
</script>
