@extends('layouts.layoutInterno')
@section('title', 'Inicio Médico - Fertilia')

@section('page-header')
<div class="page-header">
    <div>
        <h1 class="page-title">Mis Pacientes</h1>
        <p class="page-subtitle">Gestión y seguimiento de pacientes asignados</p>
    </div>
</div>
@endsection

@section('content')
<div x-data="{ 
    open: false, 
    tratamientos: [], 
    pacienteNombre: '',
    confirmModal: false,
    tratamientoParaCancelar: null
}" x-cloak>
    <!-- Estadísticas Rápidas -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <div class="stat-card">
            <div class="flex items-center justify-between p-4">
                <div>
                    <p class="stat-label">Total Pacientes</p>
                    <p class="stat-number text-xl">{{ count($pacientes) }}</p>
                </div>
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-users text-blue-600 text-lg"></i>
                </div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="flex items-center justify-between p-4">
                <div>
                    <p class="stat-label">Consultas Hoy</p>
                    <p class="stat-number text-xl">8</p>
                </div>
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-calendar-check text-green-600 text-lg"></i>
                </div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="flex items-center justify-between p-4">
                <div>
                    <p class="stat-label">En Tratamiento</p>
                    <p class="stat-number text-xl">{{ $pacientes->filter(fn($p) => strtolower($p->estado_tratamiento) === 'activo')->count() }}</p>
                </div>
                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-microscope text-purple-600 text-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros y Búsqueda -->
    <div class="card p-6 mb-6">
        <div class="flex flex-col md:flex-row gap-4">
            <!-- Búsqueda -->
            <div class="flex-1">
                <label class="form-label">Buscar Paciente</label>
                <div class="relative">
                    <input type="text" 
                           id="search-input"
                           class="form-input pl-10" 
                           placeholder="Buscar por nombre o DNI...">
                    <div class="absolute left-3 top-1/2 transform -translate-y-1/2">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                </div>
            </div>
            
            <!-- Filtro por Estado -->
            <!-- <div class="w-full md:w-48">
                <label class="form-label">Estado</label>
                <select class="form-input">
                    <option value="">Todos</option>
                    <option value="activo">Activo</option>
                    <option value="tratamiento">En Tratamiento</option>
                    <option value="seguimiento">Seguimiento</option>
                    <option value="completado">Completado</option>
                </select>
            </div> -->
            
            <!-- Botón Limpiar -->
            <div class="flex items-end">
                <button class="btn-secondary">
                    <i class="fas fa-refresh mr-2"></i>
                    Limpiar
                </button>
            </div>
        </div>
    </div>

    <!-- Lista de Pacientes -->
    <div class="card">
        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>Paciente</th>
                        <th>DNI</th>
                        <th>Edad</th>
                        <!-- <th>Estado</th> -->
                        <th>Contacto</th>
                        <!-- <th>Inicio</th> -->
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="patients-table">
                @forelse ($pacientes as $paciente)
                    <tr>
                        <td>
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center mr-3">
                                    <span class="text-white text-sm font-medium">
                                        {{ strtoupper(substr($paciente->nombre, 0, 1)) }}{{ strtoupper(substr($paciente->apellido, 0, 1)) }}
                                    </span>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $paciente->nombre }} {{ $paciente->apellido }}</p>
                                    <p class="text-sm text-gray-500">{{ $paciente->mail }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="text-gray-700">{{ $paciente->dni ?? '—' }}</td>
                        <td class="text-gray-700">
                            @if ($paciente->fecha_nacimiento)
                                {{ \Carbon\Carbon::parse($paciente->fecha_nacimiento)->age }} años
                            @else
                                —
                            @endif
                        </td>
                        <!-- <td>
                            <span class="badge 
                                @if($paciente->estado_tratamiento == 'Activo') badge-success
                                @elseif($paciente->estado_tratamiento == 'Completado') badge-info
                                @else badge-warning @endif">
                                {{ $paciente->estado_tratamiento }}
                            </span>
                        </td> -->
                        <td class="text-gray-700">{{ $paciente->telefono ?? '—' }}</td>
                        <!-- <td class="text-gray-700">
                            {{ $paciente->fecha_inicio ? \Carbon\Carbon::parse($paciente->fecha_inicio)->format('d/m/Y') : '—' }}
                        </td> -->
                        <td>
                            <div class="flex space-x-2">
                                <!-- Botón ojo: abre modal -->
                                <button 
                                    @click="
                                        fetch('/medico/paciente/{{ $paciente->paciente_id }}/tratamientos')
                                            .then(res => res.json())
                                            .then(data => {
                                                tratamientos = data.tratamientos;
                                                pacienteNombre = '{{ $paciente->nombre }} {{ $paciente->apellido }}';
                                                open = true;
                                            });
                                    "
                                    class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg" 
                                    title="Ver Historial">
                                    <i class="fas fa-eye"></i>
                                </button>

                                <a href="{{ route('medico.primerConsulta.create', $paciente->paciente_id) }}"
                                class="p-2 text-green-600 hover:bg-green-50 rounded-lg" 
                                title="Nueva Consulta">
                                    <i class="fas fa-plus"></i>
                                </a>

                                <button class="p-2 text-gray-600 hover:bg-gray-50 rounded-lg" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-gray-500 py-4">No hay pacientes asignados a este médico.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Tratamientos -->
   <div x-show="open" x-transition class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-30 z-50 p-4">
    <div 
        class="bg-white text-gray-900 rounded-2xl w-full md:w-3/4 max-h-[80vh] overflow-y-auto shadow-xl border border-gray-200"
        @click.outside="open = false"
    >
        <!-- Header -->
        <div class="flex justify-between items-center border-b border-gray-200 px-6 py-4">
            <h2 class="text-xl font-bold">
                Tratamientos de <span x-text="pacienteNombre" class="text-indigo-600"></span>
            </h2>
            <button @click="open = false" class="text-gray-400 hover:text-gray-900 text-2xl font-bold">&times;</button>
        </div>

        <!-- Tabla de tratamientos -->
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full border border-gray-200">
                    <thead class="bg-gray-50 sticky top-0">
                        <tr>
                            <th class="p-3 text-left text-sm font-medium text-gray-500">Objetivo</th>
                            <th class="p-3 text-left text-sm font-medium text-gray-500">Estado</th>
                            <th class="p-3 text-left text-sm font-medium text-gray-500">Inicio</th>
                            <th class="p-3 text-left text-sm font-medium text-gray-500">Acción</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <template x-for="tratamiento in tratamientos" :key="tratamiento.id">
                            <tr class="hover:bg-gray-100 transition-colors">
                                <td class="p-3 text-sm" x-text="tratamiento.objetivo"></td>
                                <td class="p-3 text-sm" x-text="tratamiento.estado_tratamiento"></td>
                                <td class="p-3 text-sm" x-text="new Date(tratamiento.fecha_inicio).toLocaleDateString()"></td>
                                <td class="p-3 text-sm">
                                    <a 
                                        :href="`/medico/paciente/${tratamiento.id}/tratamiento`" 
                                        class="px-3 py-1 rounded-lg bg-indigo-600 hover:bg-indigo-500 transition-colors text-white text-sm font-medium"
                                    >
                                        Ver detalle
                                    </a>
                                    <template x-if="tratamiento.estado_tratamiento === 'Activo'">
                                        <button type="button" 
                                                @click="tratamientoParaCancelar = tratamiento; confirmModal = true"
                                                class="px-3 py-1 rounded-lg bg-red-600 hover:bg-red-500 transition-colors text-white text-sm font-medium ml-2">
                                            Cancelar tratamiento
                                        </button>
                                    </template>
                                </td>
                            </tr>
                        </template>
                        <tr x-show="tratamientos.length === 0">
                            <td colspan="4" class="text-center text-gray-400 py-4">No hay tratamientos disponibles.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Footer -->
        <div class="flex justify-end px-6 py-4 border-t border-gray-200">
            <button @click="open = false" class="px-4 py-2 rounded-lg bg-gray-300 hover:bg-gray-400 text-gray-900 transition-colors">
                Cerrar
            </button>
        </div>
    </div>
</div>

    <!-- Modal de confirmación -->
    @include('medico.modals.modalConfirmarCancelacion')

</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<script>
// Función de búsqueda en tiempo real
document.getElementById('search-input').addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    const rows = document.querySelectorAll('#patients-table tr');
    
    rows.forEach(row => {
        const name = row.cells[0].textContent.toLowerCase();
        const dni = row.cells[1].textContent.toLowerCase();
        
        if (name.includes(searchTerm) || dni.includes(searchTerm)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

// Limpiar filtros
document.querySelector('.btn-secondary').addEventListener('click', function() {
    document.getElementById('search-input').value = '';
    document.querySelector('select').value = '';
    
    const rows = document.querySelectorAll('#patients-table tr');
    rows.forEach(row => {
        row.style.display = '';
    });
});
</script>
@endsection
