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
<!-- Estadísticas Rápidas -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
    <div class="stat-card">
        <div class="flex items-center justify-between p-4">
            <div>
                <p class="stat-label">Total Pacientes</p>
                <p class="stat-number text-xl">24</p>
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
                <p class="stat-number text-xl">16</p>
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
        <div class="w-full md:w-48">
            <label class="form-label">Estado</label>
            <select class="form-input">
                <option value="">Todos</option>
                <option value="activo">Activo</option>
                <option value="tratamiento">En Tratamiento</option>
                <option value="seguimiento">Seguimiento</option>
                <option value="completado">Completado</option>
            </select>
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
                    <th>Estado</th>
                    <th>Último Contacto</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="patients-table">
                <tr>
                    <td>
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center mr-3">
                                <span class="text-white text-sm font-medium">MG</span>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">María González</p>
                                <p class="text-sm text-gray-500">maria.gonzalez@email.com</p>
                            </div>
                        </div>
                    </td>
                    <td class="text-gray-700">12.345.678</td>
                    <td class="text-gray-700">32 años</td>
                    <td>
                        <span class="badge badge-success">En Tratamiento</span>
                    </td>
                    <td class="text-gray-700">15/10/2025</td>
                    <td>
                        <div class="flex space-x-2">
                            <button class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg" title="Ver Historial">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="p-2 text-green-600 hover:bg-green-50 rounded-lg" title="Nueva Consulta">
                                <i class="fas fa-plus"></i>
                            </button>
                            <button class="p-2 text-gray-600 hover:bg-gray-50 rounded-lg" title="Editar">
                                <i class="fas fa-edit"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                
                <tr>
                    <td>
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-purple-600 rounded-full flex items-center justify-center mr-3">
                                <span class="text-white text-sm font-medium">AL</span>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">Ana López</p>
                                <p class="text-sm text-gray-500">ana.lopez@email.com</p>
                            </div>
                        </div>
                    </td>
                    <td class="text-gray-700">23.456.789</td>
                    <td class="text-gray-700">28 años</td>
                    <td>
                        <span class="badge badge-info">Seguimiento</span>
                    </td>
                    <td class="text-gray-700">12/10/2025</td>
                    <td>
                        <div class="flex space-x-2">
                            <button class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg" title="Ver Historial">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="p-2 text-green-600 hover:bg-green-50 rounded-lg" title="Nueva Consulta">
                                <i class="fas fa-plus"></i>
                            </button>
                            <button class="p-2 text-gray-600 hover:bg-gray-50 rounded-lg" title="Editar">
                                <i class="fas fa-edit"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                
                <tr>
                    <td>
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-green-600 rounded-full flex items-center justify-center mr-3">
                                <span class="text-white text-sm font-medium">CM</span>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">Carlos Martínez</p>
                                <p class="text-sm text-gray-500">carlos.martinez@email.com</p>
                            </div>
                        </div>
                    </td>
                    <td class="text-gray-700">34.567.890</td>
                    <td class="text-gray-700">35 años</td>
                    <td>
                        <span class="badge badge-warning">Pendiente</span>
                    </td>
                    <td class="text-gray-700">10/10/2025</td>
                    <td>
                        <div class="flex space-x-2">
                            <button class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg" title="Ver Historial">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="p-2 text-green-600 hover:bg-green-50 rounded-lg" title="Nueva Consulta">
                                <i class="fas fa-plus"></i>
                            </button>
                            <button class="p-2 text-gray-600 hover:bg-gray-50 rounded-lg" title="Editar">
                                <i class="fas fa-edit"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                
                <tr>
                    <td>
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-pink-600 rounded-full flex items-center justify-center mr-3">
                                <span class="text-white text-sm font-medium">LR</span>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">Laura Rodríguez</p>
                                <p class="text-sm text-gray-500">laura.rodriguez@email.com</p>
                            </div>
                        </div>
                    </td>
                    <td class="text-gray-700">45.678.901</td>
                    <td class="text-gray-700">30 años</td>
                    <td>
                        <span class="badge badge-success">En Tratamiento</span>
                    </td>
                    <td class="text-gray-700">08/10/2025</td>
                    <td>
                        <div class="flex space-x-2">
                            <button class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg" title="Ver Historial">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="p-2 text-green-600 hover:bg-green-50 rounded-lg" title="Nueva Consulta">
                                <i class="fas fa-plus"></i>
                            </button>
                            <button class="p-2 text-gray-600 hover:bg-gray-50 rounded-lg" title="Editar">
                                <i class="fas fa-edit"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    
    <!-- Paginación -->
    <div class="flex items-center justify-between px-6 py-4 border-t border-gray-200">
        <div class="text-sm text-gray-500">
            Mostrando 1-4 de 24 pacientes
        </div>
        <div class="flex space-x-2">
            <button class="px-3 py-2 text-sm text-gray-500 hover:text-gray-700 disabled:opacity-50" disabled>
                Anterior
            </button>
            <button class="px-3 py-2 text-sm bg-blue-600 text-white rounded-lg">1</button>
            <button class="px-3 py-2 text-sm text-gray-500 hover:text-gray-700">2</button>
            <button class="px-3 py-2 text-sm text-gray-500 hover:text-gray-700">3</button>
            <button class="px-3 py-2 text-sm text-gray-500 hover:text-gray-700">
                Siguiente
            </button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
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
    
    // Mostrar todas las filas
    const rows = document.querySelectorAll('#patients-table tr');
    rows.forEach(row => {
        row.style.display = '';
    });
});
</script>
@endsection