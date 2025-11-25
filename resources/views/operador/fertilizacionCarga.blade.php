@extends('layouts.layoutInterno')
@section('title', 'Nueva Fertilización - Fertilia')

@section('page-header')
<div class="page-header">
    <div>
        <h1 class="page-title">Nueva fertilización</h1>
        <p class="page-subtitle">{{ $paciente->nombre }} {{ $paciente->apellido }} - DNI: {{ $paciente->dni }}</p>
    </div>
    <div>
        <a href="{{ route('operador.fertilizacion', ['paciente_id' => $paciente->id]) }}" class="btn-secondary">
            <i class="fas fa-arrow-left mr-2"></i> Volver a fertilización
        </a>
    </div>
</div>
@endsection

@section('content')
<form id="formFertilizacion" method="POST" action="{{ route('fertilizacion.guardar') }}" class="space-y-6">
    @csrf
    <input type="hidden" name="paciente_id" value="{{ $paciente->id }}">
    <input type="hidden" name="tratamiento_id" value="{{ $tratamiento_id }}">
    <input type="hidden" name="tratamiento_id" value="{{ $tratamiento_id }}">

    <!-- DATOS DE LA FERTILIZACIÓN -->
    <div class="card p-6">
        <h3 class="text-lg font-semibold mb-4 text-gray-900 flex items-center">
            <i class="fas fa-seedling text-green-600 mr-2"></i> Datos de la fertilización
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Fecha -->
            <div>
                <label class="form-label">Fecha de fertilización *</label>
                <input type="date" 
                       class="form-input" 
                       name="fecha_fertilizacion" 
                       id="fechaFertilizacion" 
                       required>
            </div>

            <!-- Hora -->
            <div>
                <label class="form-label">Hora *</label>
                <input type="time" 
                       class="form-input" 
                       name="hora_fertilizacion" 
                       id="horaFertilizacion" 
                       required>
            </div>

            <!-- Método de fertilización -->
            <div>
                <label class="form-label">Método de fertilización *</label>
                <select class="form-input" name="tipo_fertilizacion_id" id="tipoFertilizacion" required>
                    <option value="">Seleccionar método</option>
                    @foreach($tiposFertilizacion as $tipo)
                        <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- OVOCITOS DISPONIBLES -->
    <div class="card p-6">
        <h3 class="text-lg font-semibold mb-4 text-gray-900 flex items-center">
            <i class="fas fa-egg text-yellow-600 mr-2"></i> Ovocitos maduros disponibles
        </h3>

        <div id="ovocitosContainer" class="space-y-4">
            @if($ovocitosDisponibles->count() > 0)
                @foreach($ovocitosDisponibles as $ovocito)
                <div class="border rounded-lg p-4 bg-gradient-to-r from-yellow-50 to-orange-50">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   id="ovocito_{{ $ovocito->id }}" 
                                   name="ovocitos_seleccionados[]" 
                                   value="{{ $ovocito->id }}"
                                   class="mr-3 ovocito-checkbox"
                                   onchange="toggleOvocito({{ $ovocito->id }}, '{{ $ovocito->identificador }}', '{{ $ovocito->calidad_morfologica ?? 'Sin evaluar' }}')">
                            <label for="ovocito_{{ $ovocito->id }}" class="font-medium text-gray-900">
                                {{ $ovocito->identificador }}
                            </label>
                        </div>
                        <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-xs">
                            Maduro
                        </span>
                    </div>
                    <div class="text-sm text-gray-600">
                        <p><strong>Calidad morfológica:</strong> {{ $ovocito->calidad_morfologica ?? 'Sin evaluar' }}</p>
                    </div>
                </div>
                @endforeach
            @else
                <div class="text-center py-8 bg-gray-50 rounded-lg">
                    <i class="fas fa-exclamation-circle text-gray-400 text-3xl mb-2"></i>
                    <p class="text-gray-500">No hay ovocitos maduros disponibles para fertilización</p>
                    <a href="{{ route('operador.fertilizacion', ['paciente_id' => $paciente->id]) }}" class="text-blue-600 hover:underline text-sm">
                        Volver a la vista de fertilización
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- DATOS DE EMBRIONES -->
    <div class="card p-6" id="embrionesSection" style="display: none;">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900">Configuración de Embriones</h3>
            <span class="text-sm text-gray-500" id="contadorEmbriones">(0 embriones)</span>
        </div>

        <div id="embrionesContainer" class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-4">
            <!-- Los embriones se generarán dinámicamente aquí -->
        </div>

        <div class="mt-6 p-4 bg-gray-50 border-l-4 border-blue-500">
            <p class="text-sm text-gray-700">
                <strong>Nota:</strong> Complete todos los campos obligatorios para cada embrión. 
                La información registrada será utilizada para el seguimiento del tratamiento.
            </p>
        </div>
    </div>

    <!-- BOTONES DE ACCIÓN -->
    <div class="card p-6">
        <div class="flex justify-between items-center">
            <div class="text-sm text-gray-600">
                <p><strong>Paciente:</strong> {{ $paciente->nombre }} {{ $paciente->apellido }}</p>
                <p><strong>Proceso:</strong> <span id="estadoProceso">Configurando fertilización</span></p>
            </div>
            
            <div class="flex gap-3">
                <a href="{{ route('operador.fertilizacion', ['paciente_id' => $paciente->id]) }}" 
                   class="btn-secondary">
                    Cancelar
                </a>
                <button type="submit" class="btn-primary" id="btnGuardar" disabled>
                    <i class="fas fa-save mr-2"></i>
                    Guardar fertilización
                </button>
            </div>
        </div>
    </div>
</form>
@endsection

@section('scripts')
<script>
let ovocitosSeleccionados = [];
let contadorEmbriones = 0;

/* -------------------------
   GENERAR ID EMBRIÓN
------------------------- */
function generarIdEmbrion() {
    // Obtener datos del paciente desde la página
    const pacienteNombreCompleto = "{{ $paciente->nombre }} {{ $paciente->apellido }}";
    const paciente = pacienteNombreCompleto.trim().split(/\s+/);
    
    const nombre = paciente[0] ?? "---";
    const apellido = paciente.slice(1).join(" ") || "---";

    // Obtener fecha de fertilización
    const fecha = document.getElementById("fechaFertilizacion").value;
    if (!fecha) {
        return null;
    }

    const f = fecha.replaceAll("-", ""); // YYYYMMDD
    contadorEmbriones++;

    const random = Math.floor(Math.random() * 9999999)
        .toString()
        .padStart(7, "0");

    return `EMB_${f}_${apellido.substring(0,3).toUpperCase()}_${nombre.substring(0,3).toUpperCase()}_${contadorEmbriones}_${random}`;
}

/* -------------------------
   INICIALIZACIÓN
------------------------- */
document.addEventListener('DOMContentLoaded', function() {
    // Establecer fecha y hora actual por defecto
    const today = new Date().toISOString().split('T')[0];
    const timeString = new Date().toTimeString().slice(0, 5);
    
    document.getElementById('fechaFertilizacion').value = today;
    document.getElementById('horaFertilizacion').value = timeString;
    
    // Actualizar estado inicial del proceso
    const totalOvocitos = {{ $ovocitosDisponibles->count() }};
    if (totalOvocitos > 0) {
        document.getElementById('estadoProceso').textContent = `${totalOvocitos} ovocitos disponibles`;
    } else {
        document.getElementById('estadoProceso').textContent = 'No hay ovocitos disponibles';
        document.getElementById('btnGuardar').disabled = true;
    }
});

/* -------------------------
   TOGGLE OVOCITO SELECCIÓN
------------------------- */
function toggleOvocito(ovocitoId, identificador, calidad) {
    const checkbox = document.getElementById(`ovocito_${ovocitoId}`);
    
    if (checkbox.checked) {
        // Agregar ovocito seleccionado
        ovocitosSeleccionados.push({
            id: ovocitoId,
            identificador: identificador,
            calidad: calidad
        });
        
        // Crear embrión correspondiente
        crearEmbrion(ovocitoId, identificador, calidad);
    } else {
        // Remover ovocito seleccionado
        ovocitosSeleccionados = ovocitosSeleccionados.filter(ovo => ovo.id !== ovocitoId);
        
        // Remover embrión correspondiente
        removerEmbrion(ovocitoId);
    }
    
    actualizarEstadoProceso();
}

/* -------------------------
   CREAR EMBRIÓN
------------------------- */
function crearEmbrion(ovocitoId, identificador, calidad) {
    const embrionId = generarIdEmbrion();
    if (!embrionId) {
        alert("Debe completar la fecha de fertilización antes de seleccionar ovocitos.");
        // Desmarcar el checkbox
        document.getElementById(`ovocito_${ovocitoId}`).checked = false;
        return;
    }
    
    const embrionHtml = `
        <div class="border rounded-lg p-0 bg-white shadow-sm" id="embrion_${ovocitoId}">
            <div class="border-b px-4 py-3 bg-gray-50">
                <h4 class="font-semibold text-gray-900 text-sm">${embrionId}</h4>
                <p class="text-xs text-gray-600">Origen: ${identificador}</p>
            </div>
            
            <div class="p-4 space-y-4">
                <input type="hidden" name="embriones[${ovocitoId}][ovocito_id]" value="${ovocitoId}">
                <input type="hidden" name="embriones[${ovocitoId}][identificador]" value="${embrionId}">
                
                <!-- Calidad morfológica -->
                <div>
                    <label class="form-label">Calidad morfológica *</label>
                    <select name="embriones[${ovocitoId}][calidad_morfologica]" class="form-input" required>
                        <option value="" selected disabled>Seleccionar calidad</option>
                        <option value="1">1 - Muy baja</option>
                        <option value="2">2 - Baja</option>
                        <option value="3">3 - Media</option>
                        <option value="4">4 - Buena</option>
                        <option value="5">5 - Excelente</option>
                    </select>
                </div>

                <!-- Fuente de gametos -->
                <div>
                    <label class="form-label">Origen de los gametos *</label>
                    <div class="space-y-2">
                        <label class="flex items-center p-2 bg-gray-50 rounded border cursor-pointer hover:bg-gray-100">
                            <input type="radio" name="embriones[${ovocitoId}][fuente_semen]" value="pareja" 
                                   class="mr-3 fuente-semen-radio" data-ovocito="${ovocitoId}" required>
                            <span class="text-sm text-gray-700">Semen de la pareja</span>
                        </label>
                        <label class="flex items-center p-2 bg-gray-50 rounded border cursor-pointer hover:bg-gray-100">
                            <input type="radio" name="embriones[${ovocitoId}][fuente_semen]" value="donado" 
                                   class="mr-3 fuente-semen-radio" data-ovocito="${ovocitoId}" required>
                            <span class="text-sm text-gray-700">Gametos donados</span>
                        </label>
                    </div>
                    <input type="hidden" name="embriones[${ovocitoId}][semen_dni]" id="semen_dni_${ovocitoId}" value="">
                    <input type="hidden" name="embriones[${ovocitoId}][gameto_id]" id="gameto_id_${ovocitoId}" value="">
                </div>

                <!-- Acción a realizar -->
                <div>
                    <label class="form-label">Destino *</label>
                    <select name="embriones[${ovocitoId}][accion]" class="form-input accion-select" 
                            data-ovocito="${ovocitoId}" required>
                        <option value="" selected disabled>Seleccionar destino</option>
                        <option value="transferir">Transferir</option>
                        <option value="criopreservar">Criopreservar</option>
                        <option value="descartar">Descartar</option>
                    </select>
                </div>

                <!-- Motivo de descarte (condicional) -->
                <div id="motivo_descarte_${ovocitoId}" class="hidden">
                    <label class="block text-sm font-medium text-red-700 mb-1">Motivo de Descarte *</label>
                    <textarea name="embriones[${ovocitoId}][motivo_descarte]" 
                              class="form-input border-red-300 focus:border-red-500 focus:ring-red-500" 
                              rows="3" 
                              placeholder="Especifique el motivo del descarte..."></textarea>
                </div>
            </div>
        </div>
    `;
    
    document.getElementById('embrionesContainer').insertAdjacentHTML('beforeend', embrionHtml);
    document.getElementById('embrionesSection').style.display = 'block';
    
    // Actualizar contador basado en elementos reales
    const totalEmbriones = document.querySelectorAll('#embrionesContainer > div').length;
    document.getElementById('contadorEmbriones').textContent = `(${totalEmbriones} embriones)`;
    
    // Agregar event listeners para este embrión específico
    agregarEventListenersEmbrion(ovocitoId);
}

/* -------------------------
   EVENT LISTENERS EMBRIÓN
------------------------- */
function agregarEventListenersEmbrion(ovocitoId) {
    // Event listeners para radio buttons de fuente de semen
    const radioButtons = document.querySelectorAll(`input[name="embriones[${ovocitoId}][fuente_semen]"]`);
    radioButtons.forEach(radio => {
        radio.addEventListener('change', function() {
            manejarCambioFuenteSemen(ovocitoId, this.value);
        });
    });
    
    // Event listener para select de acción
    const selectAccion = document.querySelector(`select[name="embriones[${ovocitoId}][accion]"]`);
    if (selectAccion) {
        selectAccion.addEventListener('change', function() {
            manejarCambioAccion(ovocitoId, this.value);
        });
    }
}

/* -------------------------
   MANEJAR CAMBIO FUENTE SEMEN
------------------------- */
function manejarCambioFuenteSemen(ovocitoId, tipoFuente) {
    const semenDniInput = document.getElementById(`semen_dni_${ovocitoId}`);
    const gametoIdInput = document.getElementById(`gameto_id_${ovocitoId}`);
    
    if (tipoFuente === 'pareja') {
        // Usar DNI del paciente (suponiendo que es la pareja)
        const dniPaciente = "{{ $paciente->dni }}";
        semenDniInput.value = dniPaciente;
        gametoIdInput.value = '';
    } else if (tipoFuente === 'donado') {
        // Limpiar DNI y preparar para gameto donado
        semenDniInput.value = '';
        // Aquí podrías agregar lógica para seleccionar gameto donado específico
        // Por ahora usamos un valor genérico
        gametoIdInput.value = 'DONADO_' + Date.now();
    }
}

/* -------------------------
   MANEJAR CAMBIO ACCIÓN
------------------------- */
function manejarCambioAccion(ovocitoId, accion) {
    const motivoDescarteDiv = document.getElementById(`motivo_descarte_${ovocitoId}`);
    const motivoDescarteTextarea = document.querySelector(`textarea[name="embriones[${ovocitoId}][motivo_descarte]"]`);
    
    if (accion === 'descartar') {
        motivoDescarteDiv.classList.remove('hidden');
        motivoDescarteTextarea.required = true;
    } else {
        motivoDescarteDiv.classList.add('hidden');
        motivoDescarteTextarea.required = false;
        motivoDescarteTextarea.value = '';
    }
}

/* -------------------------
   REMOVER EMBRIÓN
------------------------- */
function removerEmbrion(ovocitoId) {
    const embrionElement = document.getElementById(`embrion_${ovocitoId}`);
    if (embrionElement) {
        embrionElement.remove();
        // Nota: No decrementamos contadorEmbriones para mantener secuencia única
        const totalEmbriones = document.querySelectorAll('#embrionesContainer > div').length;
        document.getElementById('contadorEmbriones').textContent = `(${totalEmbriones} embriones)`;
        
        if (totalEmbriones === 0) {
            document.getElementById('embrionesSection').style.display = 'none';
        }
    }
}

/* -------------------------
   ACTUALIZAR ESTADO PROCESO
------------------------- */
function actualizarEstadoProceso() {
    const estadoProceso = document.getElementById('estadoProceso');
    const btnGuardar = document.getElementById('btnGuardar');
    
    if (ovocitosSeleccionados.length === 0) {
        estadoProceso.textContent = 'Seleccione ovocitos para fertilizar';
        btnGuardar.disabled = true;
        btnGuardar.className = 'btn-secondary';
    } else {
        estadoProceso.textContent = `${ovocitosSeleccionados.length} ovocitos seleccionados para fertilización`;
        btnGuardar.disabled = false;
        btnGuardar.className = 'btn-primary';
    }
}

/* -------------------------
   VALIDACIÓN DEL FORMULARIO
------------------------- */
document.getElementById('formFertilizacion').addEventListener('submit', function(e) {
    if (ovocitosSeleccionados.length === 0) {
        e.preventDefault();
        alert('Debe seleccionar al menos un ovocito para fertilizar');
        return;
    }
    
    // Validar que todos los embriones tengan los campos obligatorios
    const errores = [];
    
    ovocitosSeleccionados.forEach(ovo => {
        // Validar calidad morfológica
        const calidad = document.querySelector(`select[name="embriones[${ovo.id}][calidad_morfologica]"]`);
        if (!calidad || !calidad.value) {
            errores.push(`${ovo.identificador}: Falta calidad morfológica`);
        }
        
        // Validar fuente de semen
        const fuenteSemen = document.querySelector(`input[name="embriones[${ovo.id}][fuente_semen]"]:checked`);
        if (!fuenteSemen) {
            errores.push(`${ovo.identificador}: Debe seleccionar fuente de semen`);
        }
        
        // Validar acción
        const accion = document.querySelector(`select[name="embriones[${ovo.id}][accion]"]`);
        if (!accion || !accion.value) {
            errores.push(`${ovo.identificador}: Debe seleccionar una acción`);
        }
        
        // Validar motivo de descarte si la acción es descartar
        if (accion && accion.value === 'descartar') {
            const motivoDescarte = document.querySelector(`textarea[name="embriones[${ovo.id}][motivo_descarte]"]`);
            if (!motivoDescarte || !motivoDescarte.value.trim()) {
                errores.push(`${ovo.identificador}: Debe especificar motivo de descarte`);
            }
        }
    });
    
    if (errores.length > 0) {
        e.preventDefault();
        alert(`Errores encontrados:\n\n${errores.join('\n')}`);
        return;
    }
});
</script>
@endsection