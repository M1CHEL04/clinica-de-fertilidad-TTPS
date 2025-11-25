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

                @forelse ($fertilizaciones as $fertilizacion)
                <div x-data="{ detail: false }" class="border rounded-md mb-3">

                    <!-- HEADER DE LA FERTILIZACIÓN -->
                    <div class="flex justify-between items-center bg-gray-50 px-3 py-2 hover:bg-gray-100 rounded-t-md">
                        <div class="flex flex-col">
                            <span class="font-medium">Fertilización #FERT_{{ $fertilizacion->id }}</span>
                            <span class="text-sm text-gray-500">{{ $fertilizacion->fecha_fertilizacion }} - {{ $fertilizacion->tipo_fertilizacion->nombre }}</span>
                        </div>

                        <div class="flex items-center gap-2">
                            <!-- Botón historial -->
                            <button onclick="document.getElementById('modal-fertilizacion-{{ $fertilizacion->id }}').classList.remove('hidden')"
                                    class="text-blue-600 hover:text-blue-800" 
                                    title="Ver Historial">
                                <i class="fas fa-eye"></i>
                            </button>
                            
                            <!-- Botón editar -->
                            <button type="button"
                                    onclick="abrirModalEditFertilizacion({{ $fertilizacion->id }})"
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
                                <p><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($fertilizacion->fecha_fertilizacion)->format('d/m/Y') }}</p>
                                <p><strong>Método:</strong> {{ $fertilizacion->tipo_fertilizacion->nombre }}</p>
                                <p><strong>Operador:</strong> {{ $fertilizacion->operador->nombre ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p><strong>Embriones obtenidos:</strong> {{ $fertilizacion->embrion->count() }}</p>
                                <p><strong>Tratamiento:</strong> #{{ $fertilizacion->tratamiento_id }}</p>
                                <p><strong>Estado:</strong> <span class="text-green-600 font-medium">Completado</span></p>
                            </div>
                        </div>
                    </div>

                    <!-- MODAL HISTORIAL FERTILIZACIÓN -->
                    <div id="modal-fertilizacion-{{ $fertilizacion->id }}"
                         class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                        <div class="bg-white rounded-xl w-full max-w-2xl max-h-[80vh] p-6 flex flex-col">

                            <!-- HEADER -->
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-semibold text-gray-900">Historial Fertilización #FERT_{{ $fertilizacion->id }}</h3>
                                <button onclick="document.getElementById('modal-fertilizacion-{{ $fertilizacion->id }}').classList.add('hidden')"
                                        class="text-gray-500 hover:text-gray-700 text-xl font-bold">&times;</button>
                            </div>

                            <!-- CONTENIDO SCROLLEABLE -->
                            <div class="overflow-y-auto flex-1 pr-2">
                                <div class="space-y-3">
                                    <div class="p-3 bg-gray-50 rounded border-l-4 border-blue-500">
                                        <p class="font-medium text-gray-900">Fertilización realizada</p>
                                        <p class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($fertilizacion->fecha_fertilizacion)->format('d/m/Y H:i') }} - Método: {{ $fertilizacion->tipo_fertilizacion->nombre }}</p>
                                    </div>
                                    <div class="p-3 bg-gray-50 rounded border-l-4 border-green-500">
                                        <p class="font-medium text-gray-900">Resultados</p>
                                        <p class="text-sm text-gray-600">Se obtuvieron {{ $fertilizacion->embrion->count() }} embriones</p>
                                    </div>
                                </div>
                            </div>

                            <!-- FOOTER -->
                            <div class="flex justify-end mt-4">
                                <button onclick="document.getElementById('modal-fertilizacion-{{ $fertilizacion->id }}').classList.add('hidden')"
                                        class="btn-secondary">Cerrar</button>
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

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse ($embriones as $embrion)
                <div class="border rounded-lg p-4 bg-gradient-to-r from-purple-50 to-blue-50">
                    <div class="flex justify-between items-start mb-3">
                        <h4 class="font-medium text-gray-900">{{ $embrion->identificador }}</h4>
                        @php
                            $colorCalidad = match($embrion->calidad_morfologica) {
                                '1' => 'bg-red-100 text-red-800',
                                '2' => 'bg-orange-100 text-orange-800', 
                                '3' => 'bg-yellow-100 text-yellow-800',
                                '4' => 'bg-blue-100 text-blue-800',
                                '5' => 'bg-green-100 text-green-800',
                                default => 'bg-gray-100 text-gray-800'
                            };
                            $textoCalidad = match($embrion->calidad_morfologica) {
                                '1' => 'Deficiente',
                                '2' => 'Regular',
                                '3' => 'Bueno', 
                                '4' => 'Muy Bueno',
                                '5' => 'Excelente',
                                default => 'Sin evaluar'
                            };
                        @endphp
                        <span class="{{ $colorCalidad }} px-2 py-1 rounded-full text-xs font-medium">
                            {{ $textoCalidad }}
                        </span>
                    </div>
                    <div class="text-sm text-gray-600 space-y-1">
                        <p><strong>Origen:</strong> FERT_{{ $embrion->fertilizacion->id }}</p>
                        <p><strong>Calidad:</strong> Grado {{ $embrion->calidad_morfologica }}</p>
                        <p><strong>Estado:</strong> {{ $embrion->estado->nombre ?? 'En desarrollo' }}</p>
                        @if($embrion->guardado)
                        <p><strong>Ubicación:</strong> Rack {{ $embrion->guardado->id_rack }}</p>
                        @endif
                    </div>
                    <div class="flex gap-2 mt-3">
                        <button class="flex-1 text-xs bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700"
                                onclick="verDetalleEmbrion({{ $embrion->id }})">
                            Ver detalle
                        </button>
                        @if(!$embrion->guardado)
                        <button class="flex-1 text-xs bg-purple-600 text-white px-3 py-1 rounded hover:bg-purple-700"
                                onclick="criopreservarEmbrion({{ $embrion->id }})">
                            Criopreservar
                        </button>
                        @else
                        <span class="flex-1 text-xs bg-gray-400 text-white px-3 py-1 rounded text-center">
                            Criopreservado
                        </span>
                        @endif
                    </div>
                </div>
                @empty
                <div class="col-span-full text-center py-8">
                    <div class="mb-4">
                        <i class="fas fa-microscope text-gray-400 text-5xl"></i>
                    </div>
                    <p class="text-gray-500 text-lg">Aún no hay embriones registrados.</p>
                    <p class="text-gray-400 text-sm">Los embriones aparecerán después de realizar fertilizaciones.</p>
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
                <strong>Nota:</strong> Registre cada proceso de fertilización y monitoree el desarrollo de embriones para un seguimiento completo del tratamiento.
            </p>
        </div>

    </div>
</div>



@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<script>

/* -------------------------
   FUNCIONES EMBRIONES
------------------------- */
function verDetalleEmbrion(embrionId) {
    // Implementar lógica para ver detalle del embrión
    console.log('Ver detalle embrión:', embrionId);
}

function criopreservarEmbrion(embrionId) {
    // Implementar lógica para criopreservar embrión
    console.log('Criopreservar embrión:', embrionId);
}

/* -------------------------
   MODAL EDITAR FERTILIZACIÓN
------------------------- */
function abrirModalEditFertilizacion(fertilizacionId) {
    // Implementar lógica de edición
    console.log('Editando fertilización:', fertilizacionId);
}

</script>

@endsection