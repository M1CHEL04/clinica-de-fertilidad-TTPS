@extends('layouts.layoutInterno')
@section('title', 'Panel Admin - Fertilia')

@section('page-header')
<div class="flex justify-between items-center mb-8 pb-4 border-b border-gray-200">
    <div>
        <h1 class="page-title">Panel de Administración</h1>
        <p class="page-subtitle">Gestión de usuarios internos del sistema</p>
    </div>
    <div class="ml-auto">
        <button class="btn-primary">
            <i class="fas fa-user-plus mr-2"></i>
            Registrar Personal
        </button>
    </div>
</div>
@endsection

@section('content')
<!-- Estadísticas Generales -->

<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
    <div class="stat-card">
        <div class="flex items-center justify-between p-4">
            <div>
                <p class="stat-label">Total Personal</p>
                <p class="stat-number text-xl">12</p>
            </div>
            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-users text-blue-600 text-lg"></i>
            </div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="flex items-center justify-between p-4">
            <div>
                <p class="stat-label">Médicos</p>
                <p class="stat-number text-xl">6</p>
            </div>
            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-user-md text-green-600 text-lg"></i>
            </div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="flex items-center justify-between p-4">
            <div>
                <p class="stat-label">Operadores Lab</p>
                <p class="stat-number text-xl">4</p>
            </div>
            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-flask text-purple-600 text-lg"></i>
            </div>
        </div>
    </div>
</div>

<!-- Filtros y Búsqueda -->
<div class="card p-6 mb-6">
    <div class="flex flex-col md:flex-row gap-4">
        <!-- Búsqueda -->
        <div class="flex-1">
            <label class="form-label">Buscar Personal</label>
            <div class="relative">
                <input type="text" 
                       id="search-input"
                       class="form-input pl-10" 
                       placeholder="Buscar por nombre o email...">
                <div class="absolute left-3 top-1/2 transform -translate-y-1/2">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
            </div>
        </div>
        
        <!-- Filtro por Rol -->
        <div class="w-full md:w-48">
            <label class="form-label">Rol</label>
            <select class="form-input" id="role-filter">
                <option value="">Todos</option>
                <option value="medico">Médico</option>
                <option value="operador">Operador Lab</option>
                <option value="admin">Administrador</option>
            </select>
        </div>
        
        <!-- Filtro por Estado -->
        <div class="w-full md:w-48">
            <label class="form-label">Estado</label>
            <select class="form-input" id="status-filter">
                <option value="">Todos</option>
                <option value="activo">Activo</option>
                <option value="inactivo">Inactivo</option>
            </select>
        </div>
        
        <!-- Botón Limpiar -->
        <div class="flex items-end">
            <button class="btn-secondary" id="clear-filters">
                <i class="fas fa-refresh mr-2"></i>
                Limpiar
            </button>
        </div>
    </div>
</div>

<!-- Lista de Personal -->
<div class="card">
    <div class="overflow-x-auto">
        <table class="table">
            <thead>
                <tr>
                    <th>Usuario</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Estado</th>
                    <th>Fecha Registro</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="staff-table">
                <tr>
                    <td>
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center mr-3">
                                <span class="text-white text-sm font-medium">DR</span>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">Dr. Roberto Mendez</p>
                                <p class="text-sm text-gray-500">Especialista en Fertilidad</p>
                            </div>
                        </div>
                    </td>
                    <td class="text-gray-700">roberto.mendez@fertilia.com</td>
                    <td>
                        <span class="badge badge-success">Médico</span>
                    </td>
                    <td>
                        <span class="badge badge-success">Activo</span>
                    </td>
                    <td class="text-gray-700">15/09/2025</td>
                    <td>
                        <div class="flex space-x-2">
                            <button class="p-2 text-red-600 hover:bg-red-50 rounded-lg" title="Dar de Baja">
                                <i class="fas fa-user-slash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                
                <tr>
                    <td>
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-green-600 rounded-full flex items-center justify-center mr-3">
                                <span class="text-white text-sm font-medium">AL</span>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">Ana López</p>
                                <p class="text-sm text-gray-500">Operadora de Laboratorio</p>
                            </div>
                        </div>
                    </td>
                    <td class="text-gray-700">ana.lopez@fertilia.com</td>
                    <td>
                        <span class="badge badge-info">Operador Lab</span>
                    </td>
                    <td>
                        <span class="badge badge-success">Activo</span>
                    </td>
                    <td class="text-gray-700">20/08/2025</td>
                    <td>
                        <div class="flex space-x-2">
                            <button class="p-2 text-red-600 hover:bg-red-50 rounded-lg" title="Dar de Baja">
                                <i class="fas fa-user-slash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                
                <tr>
                    <td>
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-purple-600 rounded-full flex items-center justify-center mr-3">
                                <span class="text-white text-sm font-medium">CS</span>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">Carlos Silva</p>
                                <p class="text-sm text-gray-500">Administrador del Sistema</p>
                            </div>
                        </div>
                    </td>
                    <td class="text-gray-700">carlos.silva@fertilia.com</td>
                    <td>
                        <span class="badge badge-warning">Administrador</span>
                    </td>
                    <td>
                        <span class="badge badge-success">Activo</span>
                    </td>
                    <td class="text-gray-700">01/07/2025</td>
                    <td>
                        <div class="flex space-x-2">
                            <button class="p-2 text-red-600 hover:bg-red-50 rounded-lg" title="Dar de Baja">
                                <i class="fas fa-user-slash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                
                <tr>
                    <td>
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-gray-400 rounded-full flex items-center justify-center mr-3">
                                <span class="text-white text-sm font-medium">MR</span>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">María Rodríguez</p>
                                <p class="text-sm text-gray-500">Ex-Operadora de Laboratorio</p>
                            </div>
                        </div>
                    </td>
                    <td class="text-gray-700">maria.rodriguez@fertilia.com</td>
                    <td>
                        <span class="badge badge-info">Operador Lab</span>
                    </td>
                    <td>
                        <span class="badge badge-error">Inactivo</span>
                    </td>
                    <td class="text-gray-700">10/05/2025</td>
                    <td>
                        <div class="flex space-x-2">
                            <button class="p-2 text-green-600 hover:bg-green-50 rounded-lg" title="Reactivar">
                                <i class="fas fa-user-check"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                
                <tr>
                    <td>
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-pink-600 rounded-full flex items-center justify-center mr-3">
                                <span class="text-white text-sm font-medium">LG</span>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">Dra. Laura García</p>
                                <p class="text-sm text-gray-500">Especialista en Reproducción</p>
                            </div>
                        </div>
                    </td>
                    <td class="text-gray-700">laura.garcia@fertilia.com</td>
                    <td>
                        <span class="badge badge-success">Médico</span>
                    </td>
                    <td>
                        <span class="badge badge-success">Activo</span>
                    </td>
                    <td class="text-gray-700">25/06/2025</td>
                    <td>
                        <div class="flex space-x-2">
                            <button class="p-2 text-red-600 hover:bg-red-50 rounded-lg" title="Dar de Baja">
                                <i class="fas fa-user-slash"></i>
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
            Mostrando 1-5 de 12 usuarios
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
    filterTable();
});

document.getElementById('role-filter').addEventListener('change', function() {
    filterTable();
});

document.getElementById('status-filter').addEventListener('change', function() {
    filterTable();
});

function filterTable() {
    const searchTerm = document.getElementById('search-input').value.toLowerCase();
    const roleFilter = document.getElementById('role-filter').value.toLowerCase();
    const statusFilter = document.getElementById('status-filter').value.toLowerCase();
    const rows = document.querySelectorAll('#staff-table tr');
    
    rows.forEach(row => {
        const name = row.cells[0].textContent.toLowerCase();
        const email = row.cells[1].textContent.toLowerCase();
        const role = row.cells[2].textContent.toLowerCase();
        const status = row.cells[3].textContent.toLowerCase();
        
        const matchesSearch = name.includes(searchTerm) || email.includes(searchTerm);
        const matchesRole = roleFilter === '' || role.includes(roleFilter);
        const matchesStatus = statusFilter === '' || status.includes(statusFilter);
        
        if (matchesSearch && matchesRole && matchesStatus) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

// Limpiar filtros
document.getElementById('clear-filters').addEventListener('click', function() {
    document.getElementById('search-input').value = '';
    document.getElementById('role-filter').value = '';
    document.getElementById('status-filter').value = '';
    
    // Mostrar todas las filas
    const rows = document.querySelectorAll('#staff-table tr');
    rows.forEach(row => {
        row.style.display = '';
    });
});

</script>
@endsection