@extends('layouts.layoutInterno')
@section('title', 'Panel Admin - Fertilia')

@section('page-header')
    <div class="flex justify-between items-center mb-8 pb-4 border-b border-gray-200">
        <div>
            <h1 class="page-title">Panel de administración</h1>
            <p class="page-subtitle">Gestión de usuarios internos del sistema</p>
        </div>
        <a href="/jefe/home" class="btn-primary inline-flex items-center">
            <i class="mr-2"></i> Volver atrás
        </a>
    </div>
@endsection

@section('content')

    <!-- Filtros -->
    <form method="GET" action="{{ route('jefe.usuarios.index') }}" class="card p-6 mb-6">

        <div class="flex flex-col md:flex-row gap-4">
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


            <div class="flex items-end gap-2">
            <button type="submit" class="btn-primary">
                <i class="fas fa-search mr-2"></i> Buscar
            </button>

            <!-- Ruta correcta -->
            <a href="{{ route('jefe.usuarios.index') }}" class="btn-secondary">
                <i class="fas fa-refresh mr-2"></i> Limpiar
            </a>
        </div>

        </div>
    </form>

    <!-- Tabla -->
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
                        <th class="text-center w-32">Pagos</th>
                        <th class="text-center w-24">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($usuarios as $usuario)
                        @php
                            $pagos = $pagosPorPaciente[$usuario->id] ?? collect([]);
                            $total = $pagos->sum(function ($p) {
                                return $p['monto'] ?? $p['monto_total'] ?? 0;
                            });
                        @endphp

                        <tr>
                            <!-- Usuario -->
                            <td>
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center mr-3">
                                        <span class="text-white text-sm font-medium">
                                            {{ strtoupper(substr($usuario->nombre,0,1)) }}{{ strtoupper(substr($usuario->apellido,0,1)) }}
                                        </span>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $usuario->nombre }} {{ $usuario->apellido }}</p>
                                        <p class="text-sm text-gray-500">{{ $usuario->rol->nombre ?? 'Sin rol' }}</p>
                                    </div>
                                </div>
                            </td>

                            <td>{{ $usuario->mail }}</td>
                            <td><span class="badge badge-success">{{ ucfirst($usuario->rol->nombre ?? 'Sin rol') }}</span></td>

                            <td>
                                <span class="badge {{ $usuario->activo ? 'badge-success' : 'badge-error' }}">
                                    {{ $usuario->activo ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>

                            <td>{{ $usuario->created_at->format('d/m/Y') }}</td>

                            <!-- Pagos -->
                            <td class="text-center">
                                <div class="text-sm">
                                    

                                    @if($pagos->count())
                                        <div class="mt-2">
                                            <button type="button"
                                                class="btn-secondary inline-flex items-center text-xs px-2 py-1"
                                                onclick="openPagosModal({{ $usuario->id }})">
                                                <i class="fas fa-eye mr-2"></i> Ver pagos
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            </td>

                            <td class="text-center">
                                <div class="flex justify-center gap-2">
                                    @if ($usuario->rol && $usuario->rol->nombre === 'medico')
                                        <button class="icon-btn-blue"
                                            onclick="openModalHorarios('{{ $usuario->id }}','{{ $usuario->nombre }}','{{ $usuario->apellido }}')">
                                            <i class="fas fa-clock"></i>
                                        </button>
                                    @endif

                                    @if ($usuario->activo)
                                        <button class="icon-btn-red"
                                            onclick="openModalBajaPersonal('{{ $usuario->id }}', '{{ route('admin.baja_user',$usuario->id) }}','{{ $usuario->nombre }}','{{ $usuario->apellido }}','{{ $usuario->rol->nombre }}')">
                                            <i class="fas fa-user-slash"></i>
                                        </button>
                                    @else
                                        <button class="icon-btn-green"
                                            onclick="openModalAltaPersonal('{{ $usuario->id }}', '{{ route('admin.alta_user',$usuario->id) }}','{{ $usuario->nombre }}','{{ $usuario->apellido }}','{{ $usuario->rol->nombre }}')">
                                            <i class="fas fa-user-check"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div class="flex justify-between px-6 py-4 border-t border-gray-200">
            <div class="text-sm text-gray-500">
                Mostrando {{ $usuarios->firstItem() }}–{{ $usuarios->lastItem() }} de {{ $usuarios->total() }} usuarios
            </div>
            <div>{{ $usuarios->links() }}</div>
        </div>
    </div>

    @include('admin.modalBajaPersonal')
    @include('admin.modalAltaPersonal')
    @include('admin.modalHorarios')

    <!-- Modal Pagos -->
    <div id="modalPagos" class="hidden fixed inset-0 bg-black bg-opacity-30 z-50 p-4">
        <div class="bg-white w-full md:w-3/4 mx-auto rounded-2xl shadow-xl p-6 max-h-[80vh] overflow-y-auto">
            <h2 class="text-xl font-bold mb-4">Pagos del usuario</h2>
            <div id="modalPagosBody"></div>

            <div class="text-right pt-4">
                <button onclick="closePagosModal()" class="px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400">Cerrar</button>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
<script>
function openPagosModal(id) {
    const modal = document.getElementById("modalPagos");
    const body = document.getElementById("modalPagosBody");

    const pagos = @json($pagosPorPaciente);
    const lista = pagos[id] ?? [];

    if (!lista.length) {
        body.innerHTML = "<p class='text-gray-500'>No hay pagos.</p>";
        modal.classList.remove("hidden");
        return;
    }

    // --------------------------
    // CÁLCULO DE COBRADO Y DEUDA
    // --------------------------
    let deuda = 0;
    let cobrado = 0;

    lista.forEach(p => {
        const cobertura = p.obra_social?.cobertura ?? 0;
        const total = p.monto_total ?? p.monto;
        const montoPaciente = total * (1 - cobertura);

        if (p.estado_paciente === "pagado") {
            cobrado += montoPaciente;
        } else {
            deuda += montoPaciente;
        }
    });

    const deudaFormateada = deuda.toLocaleString("es-AR", { minimumFractionDigits: 2 });
    const cobradoFormateado = cobrado.toLocaleString("es-AR", { minimumFractionDigits: 2 });

    let html = `
    <!-- Resumen -->
    <div class="mb-4 grid grid-cols-3 gap-4">

        <div class="bg-gray-100 rounded-lg p-4 flex flex-col shadow-sm text-center">
            <span class="text-xs text-gray-500 uppercase tracking-wide">Cantidad de pagos</span>
            <span class="text-2xl font-semibold text-gray-800 mt-1">${lista.length}</span>
        </div>

        <div class="bg-gray-100 rounded-lg p-4 flex flex-col shadow-sm text-center">
            <span class="text-xs text-gray-500 uppercase tracking-wide">Total cobrado</span>
            <span class="text-2xl font-semibold text-gray-800 mt-1">$ ${cobradoFormateado}</span>
        </div>

        <div class="bg-red-100 rounded-lg p-4 flex flex-col shadow-sm text-center">
            <span class="text-xs text-red-600 uppercase tracking-wide">Deuda del paciente</span>
            <span class="text-2xl font-semibold text-red-700 mt-1">$ ${deudaFormateada}</span>
        </div>

    </div>

    <!-- Tabla -->
    <table class="min-w-full divide-y divide-gray-300">
        <thead class="bg-gray-200 text-gray-700">
            <tr>
                <th class="p-2 text-left text-sm font-medium">ID</th>
                <th class="p-2 text-left text-sm font-medium">Fecha</th>
                <th class="p-2 text-left text-sm font-medium">Monto total</th>
                <th class="p-2 text-left text-sm font-medium">Monto paciente</th>
                <th class="p-2 text-left text-sm font-medium">Obra Social</th>
                <th class="p-2 text-left text-sm font-medium">Estado pago</th>
                <th class="p-2 text-left text-sm font-medium">Acción</th>
            </tr>
        </thead>
        <tbody>
    `;

    // --------------------------
    // FILAS DE LA TABLA
    // --------------------------
    lista.forEach(p => {
        const fecha = p.fecha ?? p.created_at ?? '—';

        const total = p.monto_total ?? p.monto ?? 0;
        const cobertura = p.obra_social?.cobertura ?? 0;

        const totalFormateado = total.toLocaleString("es-AR", { minimumFractionDigits: 2 });
        const montoPaciente = (total * (1 - cobertura));
        const montoPacienteFormateado = montoPaciente.toLocaleString("es-AR", { minimumFractionDigits: 2 });

        const obra = p.obra_social?.nombre ?? '—';
        const estado = p.estado_paciente ?? '—';
        const puedePagar = estado === "pendiente";

        html += `
        <tr class="border-b">
            <td class="p-2">${p.id ?? '—'}</td>
            <td class="p-2">${fecha}</td>
            <td class="p-2">$ ${totalFormateado}</td>
            <td class="p-2">$ ${montoPacienteFormateado}</td>
            <td class="p-2">${obra}</td>
            <td class="p-2">${estado}</td>
            <td class="p-2">
                <div class="flex justify-center">
                    ${
                        puedePagar
                        ? `<a href="/jefe/pago/${p.id}/marcar-pagado" class="btn-primary px-3 py-1 text-sm">Pagar</a>`
                        : `<span class="text-gray-400">—</span>`
                    }
                </div>
            </td>
        </tr>
        `;
    });

    html += "</tbody></table>";

    body.innerHTML = html;
    modal.classList.remove("hidden");
}

function closePagosModal() {
    document.getElementById("modalPagos").classList.add("hidden");
}
</script>
@endsection
