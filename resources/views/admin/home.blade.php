@extends('layouts.layoutInterno')
@section('title', 'Panel Admin - Fertilia')

@section('page-header')
    <div class="flex justify-between items-center mb-8 pb-4 border-b border-gray-200">
        <div>
            <h1 class="page-title">Panel de administración</h1>
            <p class="page-subtitle">Gestión de usuarios internos del sistema</p>
        </div>
        <div class="ml-auto flex items-center gap-3">
           
        <div class="ml-auto">
            <a href="{{ route('admin.create_user') }}" class="btn-primary inline-flex items-center">
                <i class="fas fa-user-plus mr-2"></i>
                Registrar personal
            </a>
        </div>
    </div>
@endsection

@section('content')
    <!-- Estadísticas Generales -->

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <div class="stat-card">
            <div class="flex items-center justify-between p-4">
                <div>
                    <p class="stat-label">Total personal (activo)</p>
                    <p class="stat-number text-xl">{{ $totalActivo }}</p>
                </div>
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-users text-blue-600 text-lg"></i>
                </div>
            </div>
        </div>
        <div class="stat-card">
            <div class="flex items-center justify-between p-4">
                <div>
                    <p class="stat-label">Médicos (activos)</p>
                    <p class="stat-number text-xl">{{ $medicos }}</p>
                </div>
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-user-md text-green-600 text-lg"></i>
                </div>
            </div>
        </div>
        <div class="stat-card">
            <div class="flex items-center justify-between p-4">
                <div>
                    <p class="stat-label">Operadores Lab (activos)</p>
                    <p class="stat-number text-xl">{{ $operadores }}</p>
                </div>
                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-flask text-purple-600 text-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros y Búsqueda -->
    <form method="GET" action="{{ route('admin.home') }}" class="card p-6 mb-6">
        <div class="flex flex-col md:flex-row gap-4">
            <!-- Búsqueda -->
            <div class="flex-1">
                <label class="form-label">Buscar Personal</label>
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-input pl-10"
                        placeholder="Buscar por nombre o email...">
                    <div class="absolute left-3 top-1/2 transform -translate-y-1/2">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                </div>
            </div>

            <!-- Filtro por Rol -->
            <div class="w-full md:w-48">
                <label class="form-label">Rol</label>
                <select class="form-input" name="rol">
                    <option value="">Todos</option>
                    <option value="medico" {{ request('rol') == 'medico' ? 'selected' : '' }}>Médico</option>
                    <option value="operador" {{ request('rol') == 'operador' ? 'selected' : '' }}>Operador Lab</option>
                    <option value="admin" {{ request('rol') == 'admin' ? 'selected' : '' }}>Administrador</option>
                </select>
            </div>

            <!-- Filtro por Estado -->
            <div class="w-full md:w-48">
                <label class="form-label">Estado</label>
                <select class="form-input" name="estado">
                    <option value="">Todos</option>
                    <option value="activo" {{ request('estado') == 'activo' ? 'selected' : '' }}>Activo</option>
                    <option value="inactivo" {{ request('estado') == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                </select>
            </div>

            <!-- Botón Limpiar y Buscar -->
            <div class="flex items-end gap-2">
                <button type="submit" class="btn-primary">
                    <i class="fas fa-search mr-2"></i>
                    Buscar
                </button>
                <a href="{{ route('admin.home') }}" class="btn-secondary">
                    <i class="fas fa-refresh mr-2"></i>
                    Limpiar
                </a>
            </div>
        </div>
    </form>

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
                        <th class="text-center align-middle w-24">Acciones</th>
                    </tr>
                </thead>
                <tbody id="staff-table">
                    @foreach ($usuarios as $usuario)
                        <tr>
                            <td>
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center mr-3">
                                        <span
                                            class="text-white text-sm font-medium">{{ strtoupper(substr($usuario->nombre, 0, 1)) }}{{ strtoupper(substr($usuario->apellido, 0, 1)) }}</span>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $usuario->nombre }}
                                            {{ $usuario->apellido }}</p>
                                        <p class="text-sm text-gray-500">{{ $usuario->rol->nombre ?? 'Sin rol' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="text-gray-700">{{ $usuario->mail }}</td>
                            <td>
                                <span class="badge badge-success">{{ ucfirst($usuario->rol->nombre ?? 'Sin rol') }}</span>
                            </td>
                            <td>
                                <span
                                    class="badge {{ $usuario->activo ? 'badge-success' : 'badge-error' }}">{{ $usuario->activo ? 'Activo' : 'Inactivo' }}</span>
                            </td>
                            <td class="text-gray-700">{{ $usuario->created_at->format('d/m/Y') }}</td>
                            <td class="text-center align-middle w-24">
                                <div class="flex items-center justify-center py-2 min-h-[60px]">
                                    <!-- Contenedor con ancho fijo para mantener consistencia -->
                                    <div class="flex items-center justify-center gap-2 w-20">
                                        @if ($usuario->rol && $usuario->rol->nombre === 'medico')
                                            <button type="button"
                                                class="w-9 h-9 flex items-center justify-center rounded-full bg-blue-50 hover:bg-blue-100 transition-colors duration-150 shadow-sm border border-blue-100"
                                                title="Gestionar Horarios"
                                                onclick="openModalHorarios('{{ $usuario->id }}', '{{ $usuario->nombre }}', '{{ $usuario->apellido }}')">
                                                <i class="fas fa-clock text-blue-600 text-base"></i>
                                            </button>
                                        @endif

                                        @if ($usuario->activo)
                                            <button type="button"
                                                class="w-9 h-9 flex items-center justify-center rounded-full bg-red-50 hover:bg-red-100 transition-colors duration-150 shadow-sm border border-red-100"
                                                title="Dar de Baja"
                                                onclick="openModalBajaPersonal('{{ $usuario->id }}', '{{ route('admin.baja_user', $usuario->id) }}', '{{ $usuario->nombre }}', '{{ $usuario->apellido }}', '{{ $usuario->rol->nombre ?? 'Sin rol' }}')">
                                                <i class="fas fa-user-slash text-red-600 text-base"></i>
                                            </button>
                                        @else
                                            <button type="button"
                                                class="w-9 h-9 flex items-center justify-center rounded-full bg-green-50 hover:bg-green-100 transition-colors duration-150 shadow-sm border border-green-100"
                                                title="Dar de Alta"
                                                onclick="openModalAltaPersonal('{{ $usuario->id }}', '{{ route('admin.alta_user', $usuario->id) }}', '{{ $usuario->nombre }}', '{{ $usuario->apellido }}', '{{ $usuario->rol->nombre ?? 'Sin rol' }}')">
                                                <i class="fas fa-user-check text-green-600 text-base"></i>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Paginación real -->
        <div class="flex items-center justify-between px-6 py-4 border-t border-gray-200">
            <div class="text-sm text-gray-500">
                Mostrando {{ $usuarios->firstItem() }}-{{ $usuarios->lastItem() }} de {{ $usuarios->total() }} usuarios
            </div>
            <div>
                {{ $usuarios->links() }}
            </div>
        </div>
    </div>
    @include('admin.modalBajaPersonal')
    @include('admin.modalAltaPersonal')
    @include('admin.modalHorarios')
@endsection

@section('scripts')

@endsection
