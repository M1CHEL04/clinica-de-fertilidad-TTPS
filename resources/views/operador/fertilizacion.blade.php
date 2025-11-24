@extends('layouts.layoutInterno')
@section('title', 'Fertilización - Fertilia')

@section('page-header')
<div class="page-header">
    <div>
        <h1 class="page-title">Gestión de Fertilización</h1>
        <p class="page-subtitle">Registro y seguimiento del proceso de fertilización de ovocitos</p>
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

    <!-- Columna principal -->
    <div class="lg:col-span-2 space-y-6">

        <!-- FERTILIZACIONES REGISTRADAS -->
        <div class="card p-6">
            <h3 class="text-lg font-semibold mb-4 text-gray-900 flex items-center">
                <i class="fas fa-seedling text-green-600 mr-2"></i> Fertilizaciones Registradas
            </h3>

            <div class="max-h-96 overflow-y-auto pr-1">

                {{-- @forelse (($fertilizaciones ?? []) as $fertilizacion) --}}
                {{-- Ejemplo de contenido cuando hay fertilizaciones --}}
                <div x-data="{ detail: false }" class="border rounded-md mb-3">

                    <!-- HEADER DE LA FERTILIZACIÓN -->
                    <div class="flex justify-between items-center bg-gray-50 px-3 py-2 hover:bg-gray-100 rounded-t-md">
                        <div class="flex flex-col">
                            <span class="font-medium">Fertilización #FERT_2025_001</span>
                            <span class="text-sm text-gray-500">Ovocitos procesados: 3</span>
                        </div>

                        <div class="flex items-center gap-2">
                            <!-- Botón historial -->
                            <button onclick="document.getElementById('modal-fertilizacion-1').classList.remove('hidden')"
                                    class="text-blue-600 hover:text-blue-800" 
                                    title="Ver Historial">
                                <i class="fas fa-eye"></i>
                            </button>
                            
                            <!-- Botón editar -->
                            <button type="button"
                                    onclick="abrirModalEditFertilizacion(1)"
                                    class="text-green-600 hover:text-green-800"
                                    title="Editar Fertilización">
                                <i class="fas fa-edit"></i>
                            </button>

                            <!-- Botón desplegar detalle -->
                            <button @click="detail = !detail" class="text-gray-600 hover:text-gray-800">
                                <i class="fas fa-chevron-down" :class="{'rotate-180': detail}"></i>
                            </button>
                        </div>
                    </div>

                    <!-- DETALLE DE LA FERTILIZACIÓN -->
                    <div x-show="detail" x-transition class="p-3 bg-gray-50 border-t text-gray-700">
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <p><strong>Fecha:</strong> 21/11/2025</p>
                                <p><strong>Método:</strong> FIV Convencional</p>
                                <p><strong>Ovocitos utilizados:</strong> 3</p>
                            </div>
                            <div>
                                <p><strong>Embriones obtenidos:</strong> 2</p>
                                <p><strong>Tasa de fertilización:</strong> 66.7%</p>
                                <p><strong>Estado:</strong> <span class="text-green-600 font-medium">Exitoso</span></p>
                            </div>
                        </div>
                    </div>

                    <!-- MODAL HISTORIAL FERTILIZACIÓN -->
                    <div id="modal-fertilizacion-1"
                         class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                        <div class="bg-white rounded-xl w-full max-w-2xl max-h-[80vh] p-6 flex flex-col">

                            <!-- HEADER -->
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-semibold text-gray-900">Historial Fertilización #FERT_2025_001</h3>
                                <button onclick="document.getElementById('modal-fertilizacion-1').classList.add('hidden')"
                                        class="text-gray-500 hover:text-gray-700 text-xl font-bold">&times;</button>
                            </div>

                            <!-- CONTENIDO SCROLLEABLE -->
                            <div class="overflow-y-auto flex-1 pr-2">
                                <div class="space-y-3">
                                    <div class="p-3 bg-gray-50 rounded border-l-4 border-blue-500">
                                        <p class="font-medium text-gray-900">Inicio del proceso</p>
                                        <p class="text-sm text-gray-600">21/11/2025 10:30 - Se inició el proceso de fertilización</p>
                                    </div>
                                    <!-- Más registros de historial aquí -->
                                </div>
                            </div>

                            <!-- FOOTER -->
                            <div class="flex justify-end mt-4">
                                <button onclick="document.getElementById('modal-fertilizacion-1').classList.add('hidden')"
                                        class="btn-secondary">Cerrar</button>
                            </div>
                        </div>
                    </div>

                </div>
                {{-- @empty --}}
                <!-- Mensaje cuando no hay fertilizaciones -->
                <div class="text-center py-8">
                    <div class="mb-4">
                        <i class="fas fa-seedling text-gray-400 text-5xl"></i>
                    </div>
                    <p class="text-gray-500 text-lg">Aún no hay fertilizaciones registradas.</p>
                    <p class="text-gray-400 text-sm">Use el botón "Nueva Fertilización" para comenzar.</p>
                </div>
                {{-- @endforelse --}}

            </div>
        </div>

        <!-- EMBRIONES OBTENIDOS -->
        <div class="card p-6">
            <h3 class="text-lg font-semibold mb-4 text-gray-900 flex items-center">
                <i class="fas fa-microscope text-purple-600 mr-2"></i> Embriones Obtenidos
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <!-- Ejemplo de embrión -->
                <div class="border rounded-lg p-4 bg-gradient-to-r from-purple-50 to-blue-50">
                    <div class="flex justify-between items-start mb-3">
                        <h4 class="font-medium text-gray-900">EMB_001</h4>
                        <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-medium">
                            Excelente
                        </span>
                    </div>
                    <div class="text-sm text-gray-600 space-y-1">
                        <p><strong>Origen:</strong> FERT_2025_001</p>
                        <p><strong>Grado:</strong> AA</p>
                        <p><strong>Estado:</strong> Desarrollo</p>
                    </div>
                    <div class="flex gap-2 mt-3">
                        <button class="flex-1 text-xs bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700">
                            Ver detalle
                        </button>
                        <button class="flex-1 text-xs bg-purple-600 text-white px-3 py-1 rounded hover:bg-purple-700">
                            Criopreservar
                        </button>
                    </div>
                </div>
                <!-- Más embriones aquí -->
            </div>
        </div>

    </div>

    <!-- Columna lateral -->
    <div class="space-y-6">

        <!-- NUEVA FERTILIZACIÓN -->
        <div class="card p-6">
            <h3 class="text-lg font-semibold mb-4 text-gray-900 flex items-center">
                <i class="fas fa-plus-circle text-green-600 mr-2"></i> Nueva Acción
            </h3>

            <div class="space-y-3">
                <button onclick="document.getElementById('modalFertilizacion').classList.remove('hidden')"
                    class="btn-primary w-full flex items-center justify-center gap-2">
                    <i class="fas fa-seedling"></i> Nueva Fertilización
                </button>
                
                <button onclick="document.getElementById('modalEmbrion').classList.remove('hidden')"
                    class="btn-secondary w-full flex items-center justify-center gap-2">
                    <i class="fas fa-microscope"></i> Gestionar Embriones
                </button>
            </div>
        </div>

        <!-- ESTADÍSTICAS -->
        <div class="card p-6">
            <h3 class="text-lg font-semibold mb-4 text-gray-900 flex items-center">
                <i class="fas fa-chart-bar text-blue-600 mr-2"></i> Estadísticas
            </h3>

            <div class="space-y-4">
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-gray-600">Fertilizaciones hoy:</span>
                    <span class="font-semibold text-gray-900">3</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-gray-600">Tasa de éxito:</span>
                    <span class="font-semibold text-green-600">72%</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-gray-600">Embriones activos:</span>
                    <span class="font-semibold text-purple-600">8</span>
                </div>
                <div class="flex justify-between items-center py-2">
                    <span class="text-gray-600">En criopreservación:</span>
                    <span class="font-semibold text-blue-600">12</span>
                </div>
            </div>
        </div>

        <!-- INFORMACIÓN -->
        <div class="card p-4 bg-green-50 border border-green-200">
            <p class="text-sm text-gray-700">
                <strong>Nota:</strong> Registre cada proceso de fertilización y monitoree el desarrollo de embriones para un seguimiento completo del tratamiento.
            </p>
        </div>

    </div>
</div>

<!-- MODAL NUEVA FERTILIZACIÓN -->
<div id="modalFertilizacion"
     class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">

    <div class="bg-white rounded-xl shadow-lg w-full max-w-3xl p-6 max-h-[90vh] flex flex-col">

        <!-- HEADER -->
        <h3 class="text-xl font-semibold mb-4 flex items-center text-gray-900">
            <i class="fas fa-seedling text-green-600 mr-2"></i> Registrar Nueva Fertilización
        </h3>

        <form id="formFertilizacion" method="POST" action="#" class="flex flex-col h-full">
            @csrf

            <!-- CUERPO SCROLLEABLE -->
            <div class="flex-1 overflow-y-auto max-h-[65vh] pr-2 space-y-6">

                <!-- Información básica -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Fecha de Fertilización *</label>
                        <input type="date" 
                               class="form-input" 
                               name="fecha_fertilizacion" 
                               id="fechaFertilizacion" 
                               required>
                    </div>
                    <div>
                        <label class="form-label">Hora *</label>
                        <input type="time" 
                               class="form-input" 
                               name="hora_fertilizacion" 
                               id="horaFertilizacion" 
                               required>
                    </div>
                </div>

                <!-- Método de fertilización -->
                <div>
                    <label class="form-label">Método de Fertilización *</label>
                    <select class="form-input" name="metodo_fertilizacion" id="metodoFertilizacion" required>
                        <option value="">Seleccionar método</option>
                        <option value="fiv_convencional">FIV Convencional</option>
                        <option value="icsi">ICSI (Inyección Intracitoplasmática)</option>
                        <option value="picsi">PICSI</option>
                        <option value="imsi">IMSI</option>
                    </select>
                </div>

                <!-- Selección de ovocitos -->
                <div>
                    <label class="form-label">Ovocitos Disponibles</label>
                    <div class="border rounded-lg p-4 max-h-48 overflow-y-auto bg-gray-50">
                        <div class="space-y-2">
                            <!-- Lista de ovocitos disponibles -->
                            <label class="flex items-center p-2 hover:bg-gray-100 rounded">
                                <input type="checkbox" name="ovocitos_seleccionados[]" value="1" class="mr-3">
                                <div class="flex-1">
                                    <span class="font-medium">OVO_20251121_001</span>
                                    <span class="text-sm text-gray-500 ml-2">- Calidad: Excelente</span>
                                </div>
                            </label>
                            <!-- Más ovocitos aquí -->
                        </div>
                    </div>
                </div>

                <!-- Observaciones -->
                <div>
                    <label class="form-label">Observaciones</label>
                    <textarea class="form-input" 
                              name="observaciones" 
                              rows="3" 
                              placeholder="Observaciones del proceso..."></textarea>
                </div>

            </div>

            <!-- FOOTER -->
            <div class="flex justify-end gap-3 mt-4">
                <button type="button" 
                        onclick="cerrarModalFertilizacion()" 
                        class="btn-secondary">
                    Cancelar
                </button>
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save mr-2"></i>
                    Registrar Fertilización
                </button>
            </div>

        </form>
    </div>
</div>

<!-- MODAL GESTIÓN EMBRIONES -->
<div id="modalEmbrion"
     class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">

    <div class="bg-white rounded-xl shadow-lg w-full max-w-2xl p-6">

        <!-- HEADER -->
        <h3 class="text-xl font-semibold mb-4 flex items-center text-gray-900">
            <i class="fas fa-microscope text-purple-600 mr-2"></i> Gestión de Embriones
        </h3>

        <div class="space-y-4">
            <p class="text-gray-600">Seleccione una opción para gestionar los embriones:</p>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <button class="p-4 border border-blue-300 rounded-lg hover:bg-blue-50 text-left">
                    <i class="fas fa-eye text-blue-600 mb-2"></i>
                    <h4 class="font-medium text-gray-900">Ver Estado</h4>
                    <p class="text-sm text-gray-600">Consultar desarrollo actual</p>
                </button>
                
                <button class="p-4 border border-purple-300 rounded-lg hover:bg-purple-50 text-left">
                    <i class="fas fa-snowflake text-purple-600 mb-2"></i>
                    <h4 class="font-medium text-gray-900">Criopreservar</h4>
                    <p class="text-sm text-gray-600">Almacenar embriones</p>
                </button>
            </div>
        </div>

        <!-- FOOTER -->
        <div class="flex justify-end mt-6">
            <button onclick="cerrarModalEmbrion()" class="btn-secondary">
                Cerrar
            </button>
        </div>

    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<script>

/* -------------------------
   MODAL FERTILIZACIÓN
------------------------- */
function abrirModalFertilizacion() {
    document.getElementById('modalFertilizacion').classList.remove('hidden');
}

function cerrarModalFertilizacion() {
    document.getElementById('modalFertilizacion').classList.add('hidden');
}

/* -------------------------
   MODAL EMBRIONES
------------------------- */
function abrirModalEmbrion() {
    document.getElementById('modalEmbrion').classList.remove('hidden');
}

function cerrarModalEmbrion() {
    document.getElementById('modalEmbrion').classList.add('hidden');
}

/* -------------------------
   MODAL EDITAR FERTILIZACIÓN
------------------------- */
function abrirModalEditFertilizacion(fertilizacionId) {
    // Implementar lógica de edición
    console.log('Editando fertilización:', fertilizacionId);
}

/* -------------------------
   INICIALIZACIÓN
------------------------- */
document.addEventListener('DOMContentLoaded', function() {
    // Establecer fecha actual por defecto
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('fechaFertilizacion').value = today;
    
    // Establecer hora actual por defecto
    const now = new Date();
    const timeString = now.toTimeString().slice(0, 5);
    document.getElementById('horaFertilizacion').value = timeString;
});

</script>

@endsection