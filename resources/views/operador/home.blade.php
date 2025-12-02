@extends('layouts.layoutInterno')
@section('title', 'Inicio Operador - Fertilia')

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
    
        // CRIOPRESERVACIÓN
        openCryo: false,
        selectedPacienteId: null,
        selectedDni: null,
        cryoRoute: '{{ route('criopreservar.semen.store') }}',
    
        openCryoModal(id) {
            this.selectedPacienteId = id;
            this.openCryo = true;
        }
    }" x-cloak>
        <!-- Estadísticas Rápidas -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="card p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Total pacientes</p>
                        <p class="text-2xl font-bold text-gray-900">{{ count($pacientes) }}</p>
                    </div>
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-users text-blue-600 text-lg"></i>
                    </div>
                </div>
            </div>

            <div class="card p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Tratamientos activos</p>
                        <p class="text-2xl font-bold text-gray-900">
                            {{ $pacientes->filter(fn($p) => strtolower($p->estado_tratamiento) === 'activo')->count() }}</p>
                    </div>
                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-microscope text-purple-600 text-lg"></i>
                    </div>
                </div>
            </div>

            <div class="card p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Pacientes con pareja (masculina)</p>
                        <p class="text-2xl font-bold text-gray-900">
                            @php
                                $pacientesConPareja = 0;
                                foreach ($pacientes as $paciente) {
                                    $user = \App\Models\User::find($paciente->paciente_id);
                                    if ($user && $user->obtenerDniPareja()) {
                                        $pacientesConPareja++;
                                    }
                                }
                            @endphp
                            {{ $pacientesConPareja }}
                        </p>
                    </div>
                    <div class="w-10 h-10 bg-pink-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-heart text-pink-600 text-lg"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sección Donación de Gametos -->
        <div class="card p-6 mb-6">
            <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">

                <div>
                    <h2 class="text-xl font-bold text-gray-900">Donación de Gametos</h2>
                    <p class="text-gray-600 mt-1">
                        Registro de donaciones de gametos.
                    </p>
                </div>

                <div>
                    <a href="{{ route('donacion.nueva') }}"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-semibold rounded-md shadow hover:bg-blue-700 transition">
                        <i class="fas fa-vial mr-2"></i>
                        Donar gametos
                    </a>
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
                        <input type="text" id="search-input" class="form-input pl-10"
                            placeholder="Buscar por nombre o DNI...">
                        <div class="absolute left-3 top-1/2 transform -translate-y-1/2">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                    </div>
                </div>

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
                                        <div
                                            class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center mr-3">
                                            <span class="text-white text-sm font-medium">
                                                {{ strtoupper(substr($paciente->nombre ?? '', 0, 1)) }}{{ strtoupper(substr($paciente->apellido ?? '', 0, 1)) }}
                                            </span>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $paciente->nombre }}
                                                {{ $paciente->apellido }}</p>
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
                                @if ($paciente->estado_tratamiento == 'Activo') badge-success
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
                                        <!-- Ver Historial -->
                                        <button
                                            @click="
                                        fetch('/operador/paciente/{{ $paciente->paciente_id }}/tratamientos')
                                            .then(res => res.json())
                                            .then(data => {
                                                tratamientos = data.tratamientos;
                                                pacienteNombre = '{{ $paciente->nombre }} {{ $paciente->apellido }}';
                                                open = true;
                                            });
                                    "
                                            class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg" title="Ver Historial">
                                            <i class="fas fa-eye"></i>
                                        </button>

                                        <!-- AGREGAR PUNCIÓN (SOLO ROL 3) -->
                                        @if (auth()->user()->rol_id == 3)
                                            <a href="{{ route('puncion.form', ['paciente_id' => $paciente->paciente_id]) }}"
                                                class="p-2 text-purple-600 hover:bg-purple-50 rounded-lg"
                                                title="Registrar Punción">
                                                <i class="fas fa-syringe"></i>
                                            </a>
                                        @endif

                                        @if (auth()->user()->rol_id == 3)
                                            <!-- FERTILIZACIÓN -->
                                            <a href="{{ route('operador.fertilizacion', ['paciente_id' => $paciente->paciente_id]) }}"
                                                class="p-2 text-green-600 hover:bg-green-50 rounded-lg"
                                                title="Fertilización">
                                                <i class="fas fa-seedling"></i>
                                            </a>
                                        @endif
                                        <!-- CRIOPRESERVAR SEMEN (PAREJA) -->
                                        @php
                                            $user = \App\Models\User::find($paciente->paciente_id); //polemico esto
                                            $dniPareja = $user ? $user->obtenerDniPareja() : null;

                                        @endphp
                                        @if (auth()->user()->rol_id == 3 && $dniPareja)
                                            <button @click="openCryoModal({{ $paciente->paciente_id }})"
                                                class="p-2 text-cyan-600 hover:bg-cyan-50 rounded-lg"
                                                title="Criopreservar Semen de la Pareja">
                                                <i class="fas fa-icicles"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-gray-500 py-4">No hay pacientes asignados a este
                                    médico.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal Tratamientos -->
        <div x-show="open" x-transition
            class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-30 z-50 p-4">
            <div class="bg-white text-gray-900 rounded-2xl w-full md:w-3/4 max-h-[80vh] overflow-y-auto shadow-xl border border-gray-200"
                @click.outside="open = false">
                <!-- Header -->
                <div class="flex justify-between items-center border-b border-gray-200 px-6 py-4">
                    <h2 class="text-xl font-bold">
                        Tratamientos de <span x-text="pacienteNombre" class="text-indigo-600"></span>
                    </h2>
                    <button @click="open = false"
                        class="text-gray-400 hover:text-gray-900 text-2xl font-bold">&times;</button>
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
                                        <td class="p-3 text-sm"
                                            x-text="new Date(tratamiento.fecha_inicio).toLocaleDateString()"></td>
                                        <td class="p-3 text-sm">
                                            <a :href="`/operador/paciente/${tratamiento.id}/tratamiento`"
                                                class="px-3 py-1 rounded-lg bg-indigo-600 hover:bg-indigo-500 transition-colors text-white text-sm font-medium">
                                                Ver detalle
                                            </a>
                                        </td>
                                    </tr>
                                </template>
                                <tr x-show="tratamientos.length === 0">
                                    <td colspan="4" class="text-center text-gray-400 py-4">No hay tratamientos
                                        disponibles.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Footer -->
                <div class="flex justify-end px-6 py-4 border-t border-gray-200">
                    <button @click="open = false"
                        class="px-4 py-2 rounded-lg bg-gray-300 hover:bg-gray-400 text-gray-900 transition-colors">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
        <!-- Modal CRIOPRESERVAR SEMEN -->
        <div x-show="openCryo" x-transition
            class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6" @click.outside="openCryo = false">

                <h2 class="text-xl font-semibold text-gray-900 mb-2">
                    Confirmar Criopreservación
                </h2>

                <p class="text-gray-700 mb-4">
                    ¿Está seguro que desea criopreservar semen de la pareja del paciente?
                </p>

                <form method="POST" :action="cryoRoute">
                    @csrf

                    <input type="hidden" name="paciente_id" :value="selectedPacienteId">

                    <div class="flex justify-end gap-3 mt-6">
                        <button type="button" class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300"
                            @click="openCryo = false">
                            Cancelar
                        </button>

                        <button type="submit" class="px-4 py-2 bg-cyan-600 text-white rounded-lg hover:bg-cyan-700">
                            Confirmar
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
