// Variables globales
let currentDate = new Date();
let selectedDate = null;
let selectedTime = null;
let availableTimes = [];
let turnosLibres = [];
let turnosSugeridos = [];
let hasSuggestedDate = false;
let suggestedDateStart = null;
let suggestedDateEnd = null;

// Inicializar calendario
document.addEventListener('DOMContentLoaded', function() {
    renderCalendar();

    // Event listener para selección de médico
    document.getElementById('medico_id').addEventListener('change', function() {
        const medicoId = this.value;
        
        // Actualizar campo hidden con el ID del médico
        document.getElementById('medico-seleccionado').value = medicoId || '';
        
        if (medicoId) {
            // Resetear para permitir navegación automática al seleccionar médico
            esPrimeraCarga = false;
            
            // Verificar qué tipo de búsqueda está seleccionada
            const tipoSeleccionado = document.querySelector('input[name="tipo_busqueda"]:checked').value;
            cargarTurnosSegunTipo(medicoId, tipoSeleccionado);
        } else {
            resetCalendar();
        }
    });

    // Event listeners para los radio buttons
    document.querySelectorAll('input[name="tipo_busqueda"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const medicoId = document.getElementById('medico_id').value;
            if (medicoId) {
                // Resetear para permitir navegación automática cuando se cambia el tipo de búsqueda
                esPrimeraCarga = false;
                cargarTurnosSegunTipo(medicoId, this.value);
            }
        });
    });

    document.getElementById('prev-month').addEventListener('click', function() {
        const mesAnterior = new Date(currentDate.getFullYear(), currentDate.getMonth() - 1, 1);
        
        // Solo permitir retroceder si hay turnos en meses anteriores
        if (hayTurnosEnMesesAnteriores(currentDate.getFullYear(), currentDate.getMonth())) {
            currentDate.setMonth(currentDate.getMonth() - 1);
            renderCalendar(true); // Indicar que es navegación manual
        } else {
            // Mostrar notificación explicando por qué no puede retroceder
            mostrarNotificacion('No hay turnos disponibles en meses anteriores.', 'info');
        }
    });

    document.getElementById('next-month').addEventListener('click', function() {
        // Siempre permitir avanzar
        currentDate.setMonth(currentDate.getMonth() + 1);
        renderCalendar(true); // Indicar que es navegación manual
    });
});

// Cargar turnos según el tipo seleccionado (normal o sugerida)
function cargarTurnosSegunTipo(medicoId, tipo) {
    if (tipo === 'sugerida') {
        cargarTurnosSugeridos(medicoId);
    } else {
        cargarTurnosLibres(medicoId);
    }
}

// Cargar turnos sugeridos (fecha sugerida +/- 1 día)
function cargarTurnosSugeridos(medicoId) {
    // Mostrar spinner de carga en lugar de modificar el dropdown
    const medicoSelect = document.getElementById('medico_id');
    mostrarSpinnerCarga('Verificando fecha sugerida...');
    medicoSelect.disabled = true;

    const url = `${window.turnosSugeridosBaseUrl}/${medicoId}/${window.pacienteId}`;

    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.has_suggested_date) {
                turnosLibres = data.data || [];
                hasSuggestedDate = true;
                suggestedDateStart = data.suggested_date_start;
                suggestedDateEnd = data.suggested_date_end;
                turnosSugeridos = [];

                // Validar que todos los turnos tengan fechas válidas
                turnosLibres = turnosLibres.filter(turno => {
                    if (!turno.fecha_hora) {
                        console.warn('Turno sin fecha_hora encontrado:', turno);
                        return false;
                    }

                    const fechaTest = new Date(turno.fecha_hora);
                    if (isNaN(fechaTest.getTime())) {
                        console.warn('Fecha inválida encontrada:', turno.fecha_hora, turno);
                        return false;
                    }

                    return true;
                });

                console.log('Turnos en rango de fecha sugerida cargados:', turnosLibres);
                console.log('Rango de fechas sugeridas:', suggestedDateStart, 'a', suggestedDateEnd, 'Rango completo:', data.date_range);
                
                // Verificar si hay turnos disponibles
                if (turnosLibres.length === 0) {
                    ocultarSpinnerCarga();
                    mostrarNotificacion('No hay turnos disponibles en las fechas sugeridas. Se reseteará la selección.', 'warning');
                    resetearFormularioCompleto();
                    return;
                }
                
                // Resetear para permitir navegación automática
                esPrimeraCarga = false;
                
                ocultarSpinnerCarga();
                renderCalendar();
                medicoSelect.disabled = false;
            } else {
                // Mostrar notificación informando que no hay fecha sugerida
                mostrarNotificacion('No tienes fecha sugerida. Se mostrarán todas las fechas disponibles.', 'info');
                
                // No hay fecha sugerida, cambiar automáticamente a búsqueda normal
                document.getElementById('busqueda_normal').checked = true;
                // Cambiar mensaje del spinner durante la transición
                actualizarMensajeSpinner('Buscando en todas las fechas...');
                
                setTimeout(() => {
                    cargarTurnosLibresSinModificarDropdown(medicoId);
                }, 300);
            }
        })
        .catch(error => {
            console.error('Error al verificar fecha sugerida:', error);
            
            // Mostrar notificación de error
            mostrarNotificacion('Error de conexión. Se mostrarán todas las fechas disponibles.', 'error');
            
            // En caso de error, cambiar a búsqueda normal
            document.getElementById('busqueda_normal').checked = true;
            // Cambiar mensaje del spinner durante la transición
            actualizarMensajeSpinner('Error, buscando en todas las fechas...');
            
            setTimeout(() => {
                cargarTurnosLibresSinModificarDropdown(medicoId);
            }, 300);
        });
}

// Resetear calendario
function resetCalendar() {
    turnosLibres = [];
    turnosSugeridos = [];
    hasSuggestedDate = false;
    suggestedDateStart = null;
    suggestedDateEnd = null;
    availableTimes = [];
    esPrimeraCarga = false; // Resetear la variable de primera carga
    
    // Limpiar campo hidden del médico
    document.getElementById('medico-seleccionado').value = '';
    
    ocultarHorarios();
    renderCalendar();
}

// Cargar turnos libres sin modificar el dropdown (usado cuando se cambia automáticamente desde fecha sugerida)
function cargarTurnosLibresSinModificarDropdown(medicoId) {
    // NO resetear variables de fecha sugerida cuando venimos del auto-fallback
    // Las variables hasSuggestedDate, suggestedDateStart, suggestedDateEnd se mantienen
    turnosSugeridos = [];

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

                    const fechaTest = new Date(turno.fecha_hora);
                    if (isNaN(fechaTest.getTime())) {
                        console.warn('Fecha inválida encontrada:', turno.fecha_hora, turno);
                        return false;
                    }

                    return true;
                });

                console.log('Turnos validados:', turnosLibres);
                
                // Verificar si hay turnos disponibles después del auto-fallback
                if (turnosLibres.length === 0) {
                    ocultarSpinnerCarga();
                    mostrarNotificacion('No hay turnos disponibles para este médico. Se reseteará la selección.', 'warning');
                    resetearFormularioCompleto();
                    return;
                }
                
                // Resetear para permitir navegación automática
                esPrimeraCarga = false;
                
                ocultarSpinnerCarga();
                renderCalendar(); // Re-renderizar calendario con turnos disponibles
            } else {
                ocultarSpinnerCarga();
                mostrarNotificacion('Error al cargar los turnos disponibles', 'error');
                console.error('Error:', data.error);
            }
        })
        .catch(error => {
            console.error('Error en la petición:', error);
            ocultarSpinnerCarga();
            mostrarNotificacion('Error de conexión al cargar los turnos', 'error');
        });
}

// Cargar turnos libres del médico seleccionado (todos los turnos)
function cargarTurnosLibres(medicoId) {
    // Mostrar spinner de carga en lugar de modificar el dropdown
    const medicoSelect = document.getElementById('medico_id');
    mostrarSpinnerCarga('Cargando turnos...');
    medicoSelect.disabled = true;

    // Resetear variables de fecha sugerida SOLO cuando se selecciona manualmente "buscar en todas las fechas"
    hasSuggestedDate = false;
    suggestedDateStart = null;
    suggestedDateEnd = null;
    turnosSugeridos = [];

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

                    const fechaTest = new Date(turno.fecha_hora);
                    if (isNaN(fechaTest.getTime())) {
                        console.warn('Fecha inválida encontrada:', turno.fecha_hora, turno);
                        return false;
                    }

                    return true;
                });

                console.log('Turnos validados:', turnosLibres);
                
                // Verificar si hay turnos disponibles
                if (turnosLibres.length === 0) {
                    ocultarSpinnerCarga();
                    mostrarNotificacion('No hay turnos disponibles para este médico. Se reseteará la selección.', 'warning');
                    resetearFormularioCompleto();
                    return;
                }
                
                // Resetear para permitir navegación automática
                esPrimeraCarga = false;
                
                ocultarSpinnerCarga();
                renderCalendar(); // Re-renderizar calendario con turnos disponibles
            } else {
                ocultarSpinnerCarga();
                mostrarNotificacion('Error al cargar los turnos disponibles', 'error');
                console.error('Error:', data.error);
            }
        })
        .catch(error => {
            console.error('Error en la petición:', error);
            ocultarSpinnerCarga();
            mostrarNotificacion('Error de conexión al cargar los turnos', 'error');
        })
        .finally(() => {
            // Restaurar select
            medicoSelect.disabled = false;
        });
}

// Obtener turnos disponibles para una fecha específica
function getTurnosForDate(date) {
    const fechaString = date.toISOString().split('T')[0];
    
    // Usar directamente todos los turnos libres cargados
    // (ya están filtrados por rango en el backend si es fecha sugerida)
    return turnosLibres.filter(turno => {
        try {
            if (!turno.fecha_hora) {
                console.warn('Turno sin fecha_hora:', turno);
                return false;
            }

            const turnoFecha = new Date(turno.fecha_hora);
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

// Verificar si una fecha está dentro del rango sugerido
function isDateInSuggestedRange(date) {
    if (!hasSuggestedDate || !suggestedDateStart || !suggestedDateEnd) {
        return false;
    }
    
    const dateString = date.toISOString().split('T')[0];
    return dateString >= suggestedDateStart && dateString <= suggestedDateEnd;
}

// Encontrar el primer mes que contenga turnos disponibles
function encontrarPrimerMesConTurnos() {
    if (!turnosLibres || turnosLibres.length === 0) {
        return null;
    }
    
    // Obtener todas las fechas de los turnos y ordenarlas
    const fechasTurnos = turnosLibres.map(turno => {
        try {
            return new Date(turno.fecha_hora);
        } catch (error) {
            console.warn('Error al parsear fecha del turno:', turno.fecha_hora);
            return null;
        }
    }).filter(fecha => fecha !== null && !isNaN(fecha.getTime()));
    
    if (fechasTurnos.length === 0) {
        return null;
    }
    
    // Ordenar fechas de menor a mayor
    fechasTurnos.sort((a, b) => a.getTime() - b.getTime());
    
    // Retornar la primera fecha (más temprana)
    return fechasTurnos[0];
}

// Navegar automáticamente al mes con turnos (siempre al primer mes disponible)
function navegarAMesConTurnos() {
    // Siempre buscar el primer mes con turnos disponibles
    const primerFechaConTurnos = encontrarPrimerMesConTurnos();
    
    if (primerFechaConTurnos) {
        const mesConTurnos = new Date(primerFechaConTurnos.getFullYear(), primerFechaConTurnos.getMonth(), 1);
        
        // Solo navegar si no estamos ya en ese mes
        if (currentDate.getFullYear() !== mesConTurnos.getFullYear() || 
            currentDate.getMonth() !== mesConTurnos.getMonth()) {
            
            currentDate = mesConTurnos;
            console.log('Navegando automáticamente al primer mes con turnos:', currentDate.getMonth() + 1, currentDate.getFullYear());
            return true; // Indica que se navegó a otro mes
        }
    }
    
    return false; // No se necesitó navegar
}

// Verificar si hay turnos en un mes específico
function hayTurnosEnMes(year, month) {
    if (!turnosLibres || turnosLibres.length === 0) {
        return false;
    }
    
    return turnosLibres.some(turno => {
        try {
            const fechaTurno = new Date(turno.fecha_hora);
            return fechaTurno.getFullYear() === year && fechaTurno.getMonth() === month;
        } catch (error) {
            return false;
        }
    });
}

// Verificar si hay turnos en meses anteriores al mes dado
function hayTurnosEnMesesAnteriores(year, month) {
    if (!turnosLibres || turnosLibres.length === 0) {
        return false;
    }
    
    const fechaLimite = new Date(year, month, 1);
    
    return turnosLibres.some(turno => {
        try {
            const fechaTurno = new Date(turno.fecha_hora);
            return fechaTurno < fechaLimite;
        } catch (error) {
            return false;
        }
    });
}

// Variable para controlar si es la primera carga
let esPrimeraCarga = false;

// Renderizar calendario
function renderCalendar(esNavegacionManual = false) {
    // Solo hacer navegación automática si NO es navegación manual y hay turnos
    if (!esNavegacionManual && turnosLibres && turnosLibres.length > 0) {
        // Siempre verificar si necesitamos hacer la navegación automática
        if (!esPrimeraCarga) {
            const seNavego = navegarAMesConTurnos();
            if (seNavego) {
                const monthNames = [
                    'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
                    'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
                ];
                const mesNombre = monthNames[currentDate.getMonth()];
                const año = currentDate.getFullYear();
                mostrarNotificacion(`Mostrando ${mesNombre} ${año} - primer mes con turnos disponibles.`, 'info');
            }
            esPrimeraCarga = true;
        }
    }
    
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
        } else if (hasSuggestedDate && isDateInSuggestedRange(dayDate) && dateHasAvailableSlots(dayDate)) {
            // Día con turnos en rango sugerido - Color amarillo distintivo
            dayElement.className += ' text-black bg-yellow-400 hover:bg-yellow-500 border border-yellow-600 font-semibold shadow-sm';
            dayElement.addEventListener('click', function() {
                selectDate(dayDate, dayElement);
            });
        } else if ((turnosLibres.length > 0 || turnosSugeridos.length > 0) && dateHasAvailableSlots(dayDate)) {
            // Día con turnos disponibles - Color verde distintivo
            dayElement.className += ' text-white bg-green-500 hover:bg-green-600 border border-green-600 font-semibold shadow-sm';
            dayElement.addEventListener('click', function() {
                selectDate(dayDate, dayElement);
            });
        } else if (turnosLibres.length > 0 || turnosSugeridos.length > 0) {
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
        if (!btn.disabled) {
            // Verificar si es fecha en rango sugerido
            const btnDate = new Date(currentDate.getFullYear(), currentDate.getMonth(), parseInt(btn.textContent));
            if (hasSuggestedDate && isDateInSuggestedRange(btnDate) && dateHasAvailableSlots(btnDate)) {
                btn.classList.remove('bg-blue-600');
                btn.classList.add('bg-yellow-400', 'text-black', 'border-yellow-600');
            } else if (dateHasAvailableSlots(btnDate)) {
                btn.classList.remove('bg-blue-600');
                btn.classList.add('bg-green-500', 'text-white', 'border-green-600');
            }
        }
    });

    // Marcar nueva selección con color azul
    element.classList.remove('bg-green-500', 'hover:bg-green-600', 'bg-yellow-400', 'hover:bg-yellow-500', 'border-green-600', 'border-yellow-600', 'text-black');
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
            'p-2 text-xs border border-gray-300 rounded-md hover:border-blue-500 hover:text-blue-600 transition-colors text-center min-w-[60px]';

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

// Función para resetear completamente el formulario cuando no hay turnos
function resetearFormularioCompleto() {
    // Resetear todas las variables globales
    turnosLibres = [];
    turnosSugeridos = [];
    hasSuggestedDate = false;
    suggestedDateStart = null;
    suggestedDateEnd = null;
    availableTimes = [];
    selectedDate = null;
    selectedTime = null;
    esPrimeraCarga = false; // Resetear la variable de primera carga
    
    // Resetear dropdown del médico
    const medicoSelect = document.getElementById('medico_id');
    medicoSelect.selectedIndex = 0; // Volver a "Selecciona un profesional"
    medicoSelect.disabled = false; // Asegurar que esté habilitado después del reseteo
    
    // Limpiar campo hidden del médico
    document.getElementById('medico-seleccionado').value = '';
    
    // Resetear radio buttons al estado inicial (busqueda_normal seleccionado)
    document.getElementById('busqueda_normal').checked = true;
    document.getElementById('busqueda_sugerida').checked = false;
    
    // Ocultar horarios
    ocultarHorarios();
    
    // Limpiar campos de formulario
    document.getElementById('fecha-seleccionada').value = '';
    document.getElementById('hora-seleccionada').value = '';
    document.getElementById('turno-seleccionado').value = '';
    
    // Re-renderizar calendario vacío
    renderCalendar();
}

// Función para mostrar notificaciones estéticas
function mostrarNotificacion(mensaje, tipo = 'info') {
    // Remover notificación existente si la hay
    const notificacionExistente = document.getElementById('notificacion-sistema');
    if (notificacionExistente) {
        notificacionExistente.remove();
    }

    // Crear el contenedor de notificación
    const notificacion = document.createElement('div');
    notificacion.id = 'notificacion-sistema';
    notificacion.className = 'fixed top-4 right-4 z-50 max-w-sm w-full';

    // Definir estilos según el tipo
    let bgColor, borderColor, iconColor, textColor, icon;
    
    switch(tipo) {
        case 'success':
            bgColor = 'bg-green-50';
            borderColor = 'border-green-200';
            iconColor = 'text-green-400';
            textColor = 'text-green-800';
            icon = '✓';
            break;
        case 'error':
            bgColor = 'bg-red-50';
            borderColor = 'border-red-200';
            iconColor = 'text-red-400';
            textColor = 'text-red-800';
            icon = '✕';
            break;
        case 'warning':
            bgColor = 'bg-yellow-50';
            borderColor = 'border-yellow-200';
            iconColor = 'text-yellow-400';
            textColor = 'text-yellow-800';
            icon = '⚠';
            break;
        default: // info
            bgColor = 'bg-blue-50';
            borderColor = 'border-blue-200';
            iconColor = 'text-blue-400';
            textColor = 'text-blue-800';
            icon = 'ℹ';
    }

    notificacion.innerHTML = `
        <div class="${bgColor} border ${borderColor} rounded-lg p-4 shadow-lg transition-all duration-300 transform translate-x-full">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <span class="${iconColor} text-lg font-bold">${icon}</span>
                </div>
                <div class="ml-3 flex-1">
                    <p class="${textColor} text-sm font-medium">${mensaje}</p>
                </div>
                <div class="ml-4 flex-shrink-0">
                    <button onclick="cerrarNotificacion()" class="${textColor} hover:${textColor.replace('800', '900')} text-sm font-bold">&times;</button>
                </div>
            </div>
        </div>
    `;

    // Agregar al DOM
    document.body.appendChild(notificacion);

    // Animar entrada
    setTimeout(() => {
        const contenido = notificacion.querySelector('div');
        contenido.classList.remove('translate-x-full');
        contenido.classList.add('translate-x-0');
    }, 10);

    // Auto-remover después de 5 segundos
    setTimeout(() => {
        cerrarNotificacion();
    }, 5000);
}

// Función para cerrar notificación
function cerrarNotificacion() {
    const notificacion = document.getElementById('notificacion-sistema');
    if (notificacion) {
        const contenido = notificacion.querySelector('div');
        contenido.classList.add('translate-x-full');
        contenido.classList.remove('translate-x-0');
        
        setTimeout(() => {
            notificacion.remove();
        }, 300);
    }
}

// Funciones para manejar el spinner de carga
function mostrarSpinnerCarga(mensaje = 'Cargando...') {
    // Buscar si ya existe un spinner
    let spinnerContainer = document.getElementById('loading-spinner-container');
    
    // Si no existe, crearlo
    if (!spinnerContainer) {
        spinnerContainer = document.createElement('div');
        spinnerContainer.id = 'loading-spinner-container';
        spinnerContainer.className = 'mt-3 flex items-center justify-center p-3 bg-gray-50 rounded-lg border-l-4 border-blue-500';
        spinnerContainer.innerHTML = `
            <div class="flex items-center">
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span id="loading-spinner-text" class="text-sm text-gray-700 font-medium">${mensaje}</span>
            </div>
        `;
        
        // Insertar después del dropdown del médico
        const medicoSelect = document.getElementById('medico_id');
        medicoSelect.parentNode.insertBefore(spinnerContainer, medicoSelect.nextSibling);
    } else {
        // Si ya existe, solo actualizar el mensaje
        document.getElementById('loading-spinner-text').textContent = mensaje;
        spinnerContainer.style.display = 'flex';
    }
}

function ocultarSpinnerCarga() {
    const spinnerContainer = document.getElementById('loading-spinner-container');
    if (spinnerContainer) {
        spinnerContainer.style.display = 'none';
    }
}

function actualizarMensajeSpinner(nuevoMensaje) {
    const spinnerText = document.getElementById('loading-spinner-text');
    if (spinnerText) {
        spinnerText.textContent = nuevoMensaje;
    }
}