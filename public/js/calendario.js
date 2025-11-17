// Variables globales
let currentDate = new Date();
let selectedDate = null;
let selectedTime = null;
let availableTimes = [];
let turnosLibres = [];

// Inicializar calendario
document.addEventListener('DOMContentLoaded', function() {
    renderCalendar();

    // Event listener para selección de médico
    document.getElementById('medico_id').addEventListener('change', function() {
        const medicoId = this.value;
        if (medicoId) {
            cargarTurnosLibres(medicoId);
        } else {
            turnosLibres = [];
            availableTimes = [];
            ocultarHorarios();
            renderCalendar(); // Re-renderizar calendario sin turnos
        }
    });

    document.getElementById('prev-month').addEventListener('click', function() {
        currentDate.setMonth(currentDate.getMonth() - 1);
        renderCalendar();
    });

    document.getElementById('next-month').addEventListener('click', function() {
        currentDate.setMonth(currentDate.getMonth() + 1);
        renderCalendar();
    });
});

// Cargar turnos libres del médico seleccionado
function cargarTurnosLibres(medicoId) {
    // Mostrar indicador de carga
    const medicoSelect = document.getElementById('medico_id');
    const originalText = medicoSelect.options[medicoSelect.selectedIndex].text;
    medicoSelect.options[medicoSelect.selectedIndex].text = 'Cargando turnos...';
    medicoSelect.disabled = true;

    // Construir URL usando la variable global definida en la vista
    const url = `${window.turnosLibresBaseUrl}/${medicoId}`;

    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                turnosLibres = data.data;
                console.log('Turnos libres cargados:', turnosLibres);

                // Validar que todos los turnos tengan fechas válidas
                turnosLibres = turnosLibres.filter(turno => {
                    if (!turno.fecha_hora) {
                        console.warn('Turno sin fecha_hora encontrado:', turno);
                        return false;
                    }

                    // Intentar crear una fecha para validar
                    const fechaTest = new Date(turno.fecha_hora);
                    if (isNaN(fechaTest.getTime())) {
                        console.warn('Fecha inválida encontrada:', turno.fecha_hora, turno);
                        return false;
                    }

                    return true;
                });

                console.log('Turnos validados:', turnosLibres);
                renderCalendar(); // Re-renderizar calendario con turnos disponibles
            } else {
                alert('Error al cargar los turnos disponibles');
                console.error('Error:', data.error);
            }
        })
        .catch(error => {
            console.error('Error en la petición:', error);
            alert('Error de conexión al cargar los turnos');
        })
        .finally(() => {
            // Restaurar select
            medicoSelect.options[medicoSelect.selectedIndex].text = originalText;
            medicoSelect.disabled = false;
        });
}

// Obtener turnos disponibles para una fecha específica
function getTurnosForDate(date) {
    const fechaString = date.toISOString().split('T')[0];
    return turnosLibres.filter(turno => {
        try {
            // Validar que el turno tenga fecha_hora
            if (!turno.fecha_hora) {
                console.warn('Turno sin fecha_hora:', turno);
                return false;
            }

            const turnoFecha = new Date(turno.fecha_hora);

            // Verificar que la fecha sea válida
            if (isNaN(turnoFecha.getTime())) {
                console.warn('Fecha inválida en turno:', turno.fecha_hora);
                return false;
            }

            const turnoFechaString = turnoFecha.toISOString().split('T')[0];
            return turnoFechaString === fechaString;
        } catch (error) {
            console.error('Error al procesar fecha del turno:', turno, error);
            return false;
        }
    });
}

// Verificar si una fecha tiene turnos disponibles
function dateHasAvailableSlots(date) {
    try {
        return getTurnosForDate(date).length > 0;
    } catch (error) {
        console.error('Error al verificar turnos disponibles para fecha:', date, error);
        return false;
    }
}

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
        dayElement.className = 'h-10 w-10 rounded-lg text-sm font-medium transition-colors';
        dayElement.textContent = day;

        const dayDate = new Date(currentDate.getFullYear(), currentDate.getMonth(), day);

        // Deshabilitar días pasados
        if (dayDate < today.setHours(0, 0, 0, 0)) {
            dayElement.disabled = true;
            dayElement.className += ' text-gray-400 cursor-not-allowed bg-gray-100';
        } else if (turnosLibres.length > 0 && dateHasAvailableSlots(dayDate)) {
            // Día con turnos disponibles - Color verde distintivo
            dayElement.className += ' text-white bg-green-500 hover:bg-green-600 border border-green-600 font-semibold shadow-sm';
            dayElement.addEventListener('click', function() {
                selectDate(dayDate, dayElement);
            });
        } else if (turnosLibres.length > 0) {
            // Médico seleccionado pero sin turnos este día
            dayElement.disabled = true;
            dayElement.className += ' text-gray-400 cursor-not-allowed bg-gray-50';
        } else {
            // No hay médico seleccionado
            dayElement.disabled = true;
            dayElement.className += ' text-gray-500 cursor-not-allowed hover:bg-gray-100';
            dayElement.title = 'Selecciona un profesional primero';
        }

        daysContainer.appendChild(dayElement);
    }
}

// Seleccionar fecha
function selectDate(date, element) {
    // Limpiar selección anterior
    document.querySelectorAll('#calendar-days button').forEach(btn => {
        btn.classList.remove('bg-blue-600', 'text-white');
        // Restaurar colores originales para días disponibles
        if (btn.classList.contains('text-white') && !btn.disabled) {
            btn.classList.remove('bg-blue-600');
            btn.classList.add('bg-green-500', 'text-white');
        }
    });

    // Marcar nueva selección con color azul
    element.classList.remove('bg-green-500', 'hover:bg-green-600');
    element.classList.add('bg-blue-600', 'text-white');

    selectedDate = date;
    document.getElementById('fecha-seleccionada').value = date.toISOString().split('T')[0];

    // Mostrar horarios disponibles para esta fecha
    showAvailableTimesForDate(date);
}

// Mostrar horarios disponibles para una fecha específica
function showAvailableTimesForDate(date) {
    const container = document.getElementById('horarios-container');
    const grid = document.getElementById('horarios-grid');

    const turnosDelDia = getTurnosForDate(date);

    if (turnosDelDia.length === 0) {
        container.classList.add('hidden');
        return;
    }

    container.classList.remove('hidden');
    grid.innerHTML = '';

    turnosDelDia.forEach(turno => {
        const timeButton = document.createElement('button');
        timeButton.type = 'button';
        timeButton.className =
            'p-2 text-sm border border-gray-300 rounded-lg hover:border-blue-500 hover:text-blue-600 transition-colors';

        // Extraer la hora directamente del string fecha_hora sin conversión de timezone
        // formato: '2025-11-18T18:00:00+00:00'
        const fechaHoraStr = turno.fecha_hora;
        const horaStr = fechaHoraStr.split('T')[1].split('+')[0]; // Obtiene "18:00:00"
        const horaFormateada = horaStr.substring(0, 5); // Obtiene "18:00"

        timeButton.textContent = horaFormateada;
        timeButton.dataset.turnoId = turno.id;
        timeButton.dataset.hora = horaStr; // HH:MM:SS format

        timeButton.addEventListener('click', function() {
            selectTime(horaStr, timeButton, turno.id);
        });

        grid.appendChild(timeButton);
    });
}

// Seleccionar hora
function selectTime(time, element, turnoId) {
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
    
    // Establecer el ID del turno seleccionado
    document.getElementById('turno-seleccionado').value = turnoId;
}

// Ocultar horarios
function ocultarHorarios() {
    const container = document.getElementById('horarios-container');
    container.classList.add('hidden');

    // Limpiar selecciones
    document.getElementById('fecha-seleccionada').value = '';
    document.getElementById('hora-seleccionada').value = '';
    document.getElementById('turno-seleccionado').value = '';
    selectedDate = null;
    selectedTime = null;
}