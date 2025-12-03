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
                    <i class="fas fa-seedling text-green-600 mr-2"></i> Fertilizaciones registradas
                </h3>

                <div class="max-h-96 overflow-y-auto pr-1">

                    @forelse ($fertilizaciones as $fertilizacion)
                        <div x-data="{ detail: false }" class="border rounded-md mb-3">

                            <!-- HEADER DE LA FERTILIZACIÓN -->
                            <div
                                class="flex justify-between items-center bg-gray-50 px-3 py-2 hover:bg-gray-100 rounded-t-md">
                                <div class="flex flex-col">
                                    <span class="font-medium">Fertilización #FERT_{{ $fertilizacion->id }}</span>
                                    <span class="text-sm text-gray-500">{{ $fertilizacion->fecha_fertilizacion }} -
                                        {{ $fertilizacion->tipo_fertilizacion->nombre }}</span>
                                </div>

                                <div class="flex items-center gap-2">
                                    <!-- Botón desplegar detalle -->
                                    <button @click="detail = !detail" class="text-gray-600 hover:text-gray-800">
                                        <i class="fas fa-chevron-down" :class="{ 'rotate-180': detail }"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- DETALLE DE LA FERTILIZACIÓN -->
                            <div x-show="detail" x-transition class="p-3 bg-gray-50 border-t text-gray-700">
                                <div class="grid grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <p><strong>Fecha:</strong>
                                            {{ \Carbon\Carbon::parse($fertilizacion->fecha_fertilizacion)->format('d/m/Y') }}
                                        </p>
                                        <p><strong>Método:</strong> {{ $fertilizacion->tipo_fertilizacion->nombre }}</p>
                                        <p><strong>Operador:</strong> {{ $fertilizacion->operador->nombre ?? 'N/A' }}
                                            {{ $fertilizacion->operador->apellido ?? '' }}</p>
                                    </div>
                                    <div>
                                        <p><strong>Embriones obtenidos:</strong> {{ $fertilizacion->embrion->count() }}</p>
                                        <p><strong>Tratamiento:</strong> #{{ $fertilizacion->tratamiento_id }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <!-- Mensaje cuando no hay fertilizaciones -->
                        <div class="text-center py-8">
                            <div class="mb-4">
                                <i class="fas fa-seedling text-gray-400 text-5xl"></i>
                            </div>
                            <p class="text-gray-500 text-lg">Aún no hay fertilizaciones registradas.</p>
                            <p class="text-gray-400 text-sm">Use el botón "Nueva Fertilización" para comenzar.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- EMBRIONES OBTENIDOS -->
            <div class="card p-6">
                <h3 class="text-lg font-semibold mb-4 text-gray-900 flex items-center">
                    <i class="fas fa-microscope text-purple-600 mr-2"></i> Embriones Obtenidos
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @forelse ($embriones as $embrion)
                        <div
                            class="border rounded-lg p-3 bg-gradient-to-r from-purple-50 to-blue-50 shadow-sm hover:shadow-md transition-shadow">
                            <div class="flex justify-between items-start mb-3">
                                <div class="flex-1 pr-2">
                                    <h4 class="font-medium text-gray-900 text-sm mb-1 truncate">
                                        {{ $embrion->identificador }}</h4>
                                    <p class="text-xs text-gray-600">FERT_{{ $embrion->fertilizacion->id }}</p>
                                </div>
                                @php
                                    $colorCalidad = match ($embrion->calidad_morfologica) {
                                        '1' => 'bg-red-100 text-red-800',
                                        '2' => 'bg-orange-100 text-orange-800',
                                        '3' => 'bg-yellow-100 text-yellow-800',
                                        '4' => 'bg-blue-100 text-blue-800',
                                        '5' => 'bg-green-100 text-green-800',
                                        default => 'bg-gray-100 text-gray-800',
                                    };
                                    $textoCalidad = match ($embrion->calidad_morfologica) {
                                        '1' => 'Deficiente',
                                        '2' => 'Regular',
                                        '3' => 'Bueno',
                                        '4' => 'Muy Bueno',
                                        '5' => 'Excelente',
                                        default => 'Sin evaluar',
                                    };
                                @endphp
                                <span class="{{ $colorCalidad }} px-2 py-1 rounded-full text-xs font-medium flex-shrink-0">
                                    {{ $textoCalidad }}
                                </span>
                            </div>

                            <div class="text-xs text-gray-600 space-y-1 mb-3">
                                <div class="grid grid-cols-2 gap-2">
                                    <p><strong>Calidad:</strong> Grado {{ $embrion->calidad_morfologica }}</p>
                                </div>
                                <p><strong>PGT:</strong>
                                    @if ($embrion->pgt_positivo !== null)
                                        <span class="{{ $embrion->pgt_positivo ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $embrion->pgt_positivo ? 'Positivo' : 'Negativo' }}
                                        </span>
                                    @else
                                        No realizado
                                    @endif
                                </p>
                            </div>

                            <div class="flex gap-1 mt-3">
                                <button
                                    class="flex-1 text-xs px-2 py-1 bg-white border border-blue-200 text-blue-700 rounded-md hover:bg-blue-50 transition-colors flex items-center justify-center gap-1"
                                    onclick="abrirModalDetalle({{ $embrion->id }})">
                                    <i class="fas fa-eye text-xs"></i>
                                    <span>Detalle</span>
                                </button>

                                @php
                                    // Determinar el estado del embrión y su estilo
                                    if ($embrion->motivo_descarte) {
                                        $estadoTexto = 'Descartado';
                                        $estadoIcono = 'fas fa-trash';
                                        $estadoClase = 'bg-red-100 text-red-700';
                                    } elseif ($embrion->transferir) {
                                        $estadoTexto = 'Para Transferir';
                                        $estadoIcono = 'fas fa-arrow-right';
                                        $estadoClase = 'bg-green-100 text-green-700';
                                    } elseif ($embrion->criopreservado) {
                                        $estadoTexto = 'Criopreservado';
                                        $estadoIcono = 'fas fa-snowflake';
                                        $estadoClase = 'bg-blue-100 text-blue-700';
                                    } elseif ($embrion->utilizado) {
                                        $estadoTexto = 'Utilizado';
                                        $estadoIcono = 'fas fa-check-circle';
                                        $estadoClase = 'bg-yellow-100 text-yellow-700';
                                    }
                                @endphp

                                <div
                                    class="flex-1 text-xs px-2 py-1 {{ $estadoClase }} rounded-md flex items-center justify-center gap-1">
                                    <i class="{{ $estadoIcono }} text-xs"></i>
                                    <span class="truncate">{{ $estadoTexto }}</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-12">
                            <div class="mb-4">
                                <i class="fas fa-microscope text-gray-400 text-6xl"></i>
                            </div>
                            <p class="text-gray-500 text-xl mb-2">Aún no hay embriones registrados.</p>
                            <p class="text-gray-400">Los embriones aparecerán después de realizar fertilizaciones.</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

        <!-- Columna lateral -->
        <div class="space-y-6">

            <!-- NUEVA FERTILIZACIÓN -->
            <div class="card p-6">
                <h3 class="text-lg font-semibold mb-4 text-gray-900 flex items-center">
                    <i class="fas fa-plus-circle text-green-600 mr-2"></i> Nueva Fertilización
                </h3>

                <div class="space-y-3">
                    <a href="{{ route('fertilizacion.nueva', ['paciente_id' => $paciente->id]) }}"
                        class="btn-primary w-full flex items-center justify-center gap-2">
                        <i class="fas fa-seedling"></i> Nueva Fertilización
                    </a>
                </div>
            </div>

            <!-- INFORMACIÓN DEL PACIENTE -->
            <div class="card p-6">
                <h3 class="text-lg font-semibold mb-4 text-gray-900 flex items-center">
                    <i class="fas fa-user text-blue-600 mr-2"></i> Información del Paciente
                </h3>

                <div class="space-y-2 text-sm text-gray-700">
                    <p><strong>Nombre:</strong> {{ $paciente->nombre }} {{ $paciente->apellido }}</p>
                    <p><strong>DNI:</strong> {{ $paciente->dni }}</p>
                    <p><strong>Email:</strong> {{ $paciente->mail }}</p>
                    <p><strong>Teléfono:</strong> {{ $paciente->telefono }}</p>
                </div>
            </div>

            <!-- INFORMACIÓN -->
            <div class="card p-4 bg-green-50 border border-green-200">
                <p class="text-sm text-gray-700">
                    <strong>Nota:</strong> Registre cada proceso de fertilización y monitoree el desarrollo de embriones
                    para un seguimiento completo del tratamiento.
                </p>
            </div>

        </div>
    </div>

    <!-- MODAL DETALLE EMBRIÓN -->
    <div id="modalDetalleEmbrion" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden" onclick="cerrarModalDetalle()">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto"
                onclick="event.stopPropagation()">
                <div class="flex justify-between items-center p-6 border-b">
                    <h3 class="text-xl font-semibold text-gray-900">
                        <i class="fas fa-microscope text-purple-600 mr-2"></i>
                        Detalle del Embrión
                    </h3>
                    <button onclick="cerrarModalDetalle()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <div id="contenidoModalDetalle" class="p-6">
                    <!-- El contenido se cargará dinámicamente -->
                    <div class="flex items-center justify-center py-12">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-purple-600"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL CAMBIO DE ESTADO -->
    <div id="modalCambioEstado" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden" onclick="cerrarModalCambio()">
        <div class="flex items-start justify-center min-h-screen p-4 pt-20">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full max-h-[calc(100vh-8rem)] overflow-y-auto"
                onclick="event.stopPropagation()">
                <div class="flex justify-between items-center p-6 border-b sticky top-0 bg-white z-10">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-exchange-alt text-orange-600 mr-2"></i>
                        Cambiar Estado del Embrión
                    </h3>
                    <button onclick="cerrarModalCambio()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form id="formCambioEstado" method="POST" action="{{ route('embrion.update') }}" class="p-6">
                    @csrf
                    <input type="hidden" id="embrionIdCambio" name="embrion_id" value="">

                    <div class="space-y-6">
                        <div class="relative">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nueva Acción *</label>
                            <select name="nueva_accion" id="nuevaAccion"
                                class="w-full border border-gray-300 rounded-lg px-3 py-3 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent appearance-none bg-white"
                                required onchange="toggleMotivoDescarte()">
                                <option value="" disabled selected>Seleccionar acción</option>
                                <option value="transferir">Transferir</option>
                                <option value="descartar">Descartar</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 pt-8">
                                <i class="fas fa-chevron-down text-gray-400"></i>
                            </div>
                        </div>

                        <div id="motivoDescarteDiv" class="hidden">
                            <label class="block text-sm font-medium text-red-700 mb-2">Motivo de Descarte *</label>
                            <textarea name="motivo_descarte" id="motivoDescarte"
                                class="w-full border border-red-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent resize-none"
                                rows="4" placeholder="Especifique el motivo del descarte..."></textarea>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 mt-8 pt-4 border-t">
                        <button type="button" onclick="cerrarModalCambio()"
                            class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                            Cancelar
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors">
                            <i class="fas fa-save mr-2"></i>Guardar Cambio
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>



@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        // Variables globales para manejo de modales
        let embrionActual = null;

        /* -------------------------
           FUNCIONES AUXILIARES
        ------------------------- */
        function getCalidadTexto(grado) {
            switch (grado) {
                case '1':
                    return 'Muy Baja';
                case '2':
                    return 'Baja';
                case '3':
                    return 'Media';
                case '4':
                    return 'Buena';
                case '5':
                    return 'Excelente';
                default:
                    return 'Sin evaluar';
            }
        }

        function getEstadoActual(embrion) {
            if (embrion.motivo_descarte) return 'Descartado';
            if (embrion.utilizado) return 'Utilizado';
            if (embrion.transferir) return 'Marcado para transferir';
            if (embrion.criopreservado) return 'Criopreservado';
            return 'En desarrollo';
        }

        function getDescripcionEstado(embrion) {
            if (embrion.motivo_descarte) {
                return 'Este embrión ha sido descartado y no puede ser utilizado.';
            }
            if (embrion.utilizado) {
                return 'Este embrión ya ha sido utilizado en un tratamiento.';
            }
            if (embrion.transferir) {
                return 'Este embrión está marcado para transferencia.';
            }
            if (embrion.criopreservado) {
                return 'Este embrión está criopreservado. Puede transferirlo o descartarlo.';
            }
            return 'Este embrión está en desarrollo y aún no está criopreservado.';
        }

        /* -------------------------
           MODAL DETALLE EMBRIÓN
        ------------------------- */
        async function abrirModalDetalle(embrionId) {
            const modal = document.getElementById('modalDetalleEmbrion');
            const contenido = document.getElementById('contenidoModalDetalle');

            // Mostrar modal con loader
            modal.classList.remove('hidden');

            try {
                // Cargar datos del embrión (aquí deberías hacer una petición AJAX real)
                const embrion = @json($embriones).find(e => e.id == embrionId);
                embrionActual = embrion;

                if (!embrion) {
                    contenido.innerHTML = `
                <div class="text-center text-red-600">
                    <i class="fas fa-exclamation-triangle text-4xl mb-4"></i>
                    <p>No se pudo cargar la información del embrión.</p>
                </div>`;
                    return;
                }

                // Renderizar información del embrión
                const pgtInfo = embrion.realizo_PGT ?
                    `<div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                <h4 class="font-semibold text-blue-800 mb-2 flex items-center">
                    <i class="fas fa-dna mr-2"></i>
                    Análisis PGT
                </h4>
                <p class="text-sm text-blue-700">
                    <strong>Resultado:</strong> ${embrion.pgt_positivo !== null ? 
                        (embrion.pgt_positivo ? '<span class="text-green-600 font-medium">Positivo</span>' : '<span class="text-red-600 font-medium">Negativo</span>') : 
                        '<span class="text-gray-600">Sin resultado</span>'}
                </p>
            </div>` : '';

                const calidadInfo = embrion.calidad_morfologica ?
                    `<div class="bg-purple-50 border border-purple-200 rounded-lg p-4 mb-4">
                <h4 class="font-semibold text-purple-800 mb-2 flex items-center">
                    <i class="fas fa-microscope mr-2"></i>
                    Calidad Morfológica
                </h4>
                <p class="text-sm text-purple-700">
                    <strong>Grado:</strong> ${embrion.calidad_morfologica} - ${getCalidadTexto(embrion.calidad_morfologica)}
                </p>
            </div>` : '';

                const motivoDescarteInfo = embrion.motivo_descarte ?
                    `<div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
                <h4 class="font-semibold text-red-800 mb-2 flex items-center">
                    <i class="fas fa-trash mr-2"></i>
                    Motivo de Descarte
                </h4>
                <p class="text-sm text-red-700">${embrion.motivo_descarte}</p>
            </div>` : '';

                // Determinar el origen del semen
                let origenSemen = 'No especificado';
                if (embrion.semen_fresco === 1) {
                    origenSemen = 'Semen en fresco';
                } else if (embrion.semen_dni !== null && embrion.semen_dni !== '') {
                    origenSemen = `Semen criopreservado`;
                } else if (embrion.gameto_id !== null) {
                    origenSemen = `Semen donado`;
                }

                const compatibilidadInfo = embrion.compatibilidad_gameto ?
                    `<div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
                <h4 class="font-semibold text-green-800 mb-2 flex items-center">
                    <i class="fas fa-percentage mr-2"></i>
                    Compatibilidad del Gameto
                </h4>
                <p class="text-sm text-green-700">
                    <strong>Compatibilidad:</strong> ${(embrion.compatibilidad_gameto * 100).toFixed(2)}%
                </p>
            </div>` : '';

                contenido.innerHTML = `
            <div class="space-y-6">
                <!-- Información General -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h4 class="font-semibold text-gray-900 mb-3 flex items-center">
                        <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                        Información General
                    </h4>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div class="space-y-2">
                            <p><strong>Identificador Embrión:</strong> ${embrion.identificador}</p>
                            <p><strong>Identificador Ovocito:</strong> ${embrion.ovocito ? embrion.ovocito.identificador : 'N/A'}</p>
                            <p><strong>Fertilización:</strong> FERT_${embrion.fertilizacion.id}</p>
                        </div>
                        <div class="space-y-2">
                            <p><strong>Fecha Creación:</strong> ${new Date(embrion.created_at).toLocaleDateString()}</p>
                            <p><strong>Estado Actual:</strong> ${getEstadoActual(embrion)}</p>
                            <p><strong>Origen del Semen:</strong> ${origenSemen}</p>
                            <p><strong>Utilizado:</strong> <span class="${embrion.utilizado ? 'text-green-600 font-medium' : 'text-gray-600'}">${embrion.utilizado ? 'Sí' : 'No'}</span></p>
                        </div>
                    </div>
                </div>
                
                ${pgtInfo}
                ${calidadInfo}
                ${motivoDescarteInfo}
                ${compatibilidadInfo}
                
                <!-- Estado y Acciones - Solo visible si está criopreservado -->
                ${embrion.criopreservado ? `
                                                <div class="bg-blue-50 rounded-lg p-4">
                                                    <h4 class="font-semibold text-gray-900 mb-3 flex items-center">
                                                        <i class="fas fa-exchange-alt text-orange-600 mr-2"></i>
                                                        Gestión de Estado
                                                    </h4>
                                                    <p class="text-sm text-gray-600 mb-4">
                                                        Este embrión está criopreservado y disponible para transferencia o descarte. 
                                                        Puede cambiar su estado usando el botón de abajo.
                                                    </p>
                                                    
                                                    ${!embrion.transferir && !embrion.motivo_descarte ? `
                    <button onclick="abrirModalCambioEstado()" class="bg-orange-600 text-white px-4 py-2 rounded-lg hover:bg-orange-700 transition-colors">
                        <i class="fas fa-exchange-alt mr-2"></i>
                        Cambiar Estado
                    </button>` : `
                    <div class="text-center py-2">
                        <span class="text-sm text-gray-500 italic">
                            ${embrion.transferir ? 'Embrión marcado para transferir' : 'Embrión descartado'}
                        </span>
                    </div>`}
                                                </div>` : `
                                                <!-- Información de Estado Actual -->
                                                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                                    <h4 class="font-semibold text-gray-900 mb-2 flex items-center">
                                                        <i class="fas fa-info-circle text-yellow-600 mr-2"></i>
                                                        Estado Actual
                                                    </h4>
                                                    <p class="text-sm text-gray-600">
                                                        ${embrion.motivo_descarte ? 
                                                            'Este embrión ha sido descartado y no está disponible para uso.' :
                                                            embrion.utilizado ? 
                                                            'Este embrión ya ha sido utilizado en un tratamiento previo.' :
                                                            embrion.transferir ? 
                                                            'Este embrión está marcado para transferencia inmediata.' :
                                                            'Este embrión está en desarrollo. Debe ser criopreservado antes de poder gestionar su estado.'}
                                                    </p>
                                                </div>`}
            </div>`;

            } catch (error) {
                contenido.innerHTML = `
            <div class="text-center text-red-600">
                <i class="fas fa-exclamation-triangle text-4xl mb-4"></i>
                <p>Error al cargar la información del embrión.</p>
            </div>`;
            }
        }

        function cerrarModalDetalle() {
            document.getElementById('modalDetalleEmbrion').classList.add('hidden');
            embrionActual = null;
        }

        /* -------------------------
           MODAL CAMBIO DE ESTADO
        ------------------------- */
        function abrirModalCambioEstado() {
            if (!embrionActual) return;

            document.getElementById('embrionIdCambio').value = embrionActual.id;
            document.getElementById('modalCambioEstado').classList.remove('hidden');

            // Reset form
            document.getElementById('nuevaAccion').value = '';
            document.getElementById('motivoDescarte').value = '';
            document.getElementById('motivoDescarteDiv').classList.add('hidden');
        }

        function cerrarModalCambio() {
            document.getElementById('modalCambioEstado').classList.add('hidden');
        }

        function toggleMotivoDescarte() {
            const accion = document.getElementById('nuevaAccion').value;
            const motivoDiv = document.getElementById('motivoDescarteDiv');
            const motivoTextarea = document.getElementById('motivoDescarte');

            if (accion === 'descartar') {
                motivoDiv.classList.remove('hidden');
                motivoTextarea.required = true;
            } else {
                motivoDiv.classList.add('hidden');
                motivoTextarea.required = false;
                motivoTextarea.value = '';
            }
        }

        /* -------------------------
           FUNCIONES EMBRIONES
        ------------------------- */
        function criopreservarEmbrion(embrionId) {
            if (confirm('¿Está seguro de que desea criopreservar este embrión?')) {
                // Implementar lógica para criopreservar embrión
                console.log('Criopreservar embrión:', embrionId);
                // Aquí deberías hacer una petición POST al controlador
            }
        }

        // Cerrar modales con ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                cerrarModalDetalle();
                cerrarModalCambio();
            }
        });
    </script>

@endsection
