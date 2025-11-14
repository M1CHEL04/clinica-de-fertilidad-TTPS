@extends('layouts.layoutInterno')
@section('title', 'Monitoreos - Fertilia')

@section('page-header')
<div class="page-header">
    <div>
        <h1 class="page-title">Monitoreos del Tratamiento</h1>
        <p class="page-subtitle">Control y seguimiento del tratamiento actual</p>
    </div>
    <div>
        <a href="{{ route('medico.tratamiento.detalle', $tratamiento->id) }}" class="btn-secondary">
            <i class="fas fa-arrow-left mr-2"></i> Volver al Detalle
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- Columna principal -->
    <div class="lg:col-span-2 space-y-6">

       

        <!-- Monitoreos actuales -->
        <div class="card p-6">

            <h3 class="text-lg font-semibold mb-4 text-gray-900 flex items-center">
                <i class="fas fa-stethoscope text-indigo-600 mr-2"></i> Monitoreos Registrados
            </h3>

            <!-- SCROLL AGREGADO ACÁ -->
            <div class="max-h-96 overflow-y-auto pr-1">

                @forelse ($monitoreos as $monitoreo)
                    <div x-data="{ open: false }" class="border rounded-lg mb-3">

                        <!-- Encabezado principal -->
                        <button @click="open = !open"
                            class="w-full flex justify-between items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg">

                            <span class="font-medium text-gray-800">
                                {{ \Carbon\Carbon::parse($monitoreo->created_at)->format('d/m/Y') }}
                            </span>

                            <i :class="open ? 'fas fa-chevron-up' : 'fas fa-chevron-down'" class="text-gray-600"></i>
                        </button>

                        <!-- Contenido interno -->
                        <div x-show="open" x-transition class="p-4 text-gray-700 border-t">

                            <div x-data="{ obsOpen: false }" class="mt-2">

                                <button @click="obsOpen = !obsOpen"
                                    class="flex justify-between items-center w-full px-3 py-2 rounded-md bg-gray-50 hover:bg-gray-100 border">
                                    <span class="font-medium text-gray-700">Observación</span>
                                    <i :class="obsOpen ? 'fas fa-chevron-up' : 'fas fa-chevron-down'"
                                       class="text-gray-500"></i>
                                </button>

                                <div x-show="obsOpen" x-transition
                                    class="mt-3 p-3 bg-gray-50 border rounded-md text-gray-800">
                                    {{ $monitoreo->observacion }}
                                </div>

                            </div>

                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-4">Aún no hay monitoreos cargados.</p>
                @endforelse

            </div>
            <!-- FIN SCROLL -->

        </div>

    </div>

    <!-- Columna lateral -->
    <div class="space-y-6">

        <div class="card p-6">
            <h3 class="text-lg font-semibold mb-4 text-gray-900 flex items-center">
                <i class="fas a-plus-circle text-green-600 mr-2"></i> Nueva Acción
            </h3>

            <button onclick="document.getElementById('modalMonitoreo').classList.remove('hidden')"
                class="btn-primary w-full flex items-center justify-center gap-2">
                <i class="fas fa-heartbeat"></i> Cargar Monitoreo
            </button>
        </div>

        <div class="card p-4 bg-blue-50 border border-blue-200">
            <p class="text-sm text-gray-700">
                <strong>Nota:</strong> Los monitoreos permiten registrar el estado evolutivo del tratamiento.
            </p>
        </div>

    </div>
</div>

<!-- Modal Cargar Monitoreo -->
<div id="modalMonitoreo"
     class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">

    <div class="bg-white rounded-xl shadow-lg w-full max-w-lg p-6">

        <h3 class="text-xl font-semibold mb-4 flex items-center text-gray-900">
            <i class="fas fa-heartbeat text-red-600 mr-2"></i> Nuevo Monitoreo
        </h3>

        <form action="{{ route('monitoreos.store') }}" method="POST">
    @csrf

    <input type="hidden" name="tratamiento_id" value="{{ $tratamiento->id }}">

    <div class="mb-4">
        <label class="block text-gray-700 font-medium mb-1">Observación</label>
        <textarea name="observacion"
                  class="w-full border rounded-lg p-3 focus:ring focus:ring-indigo-200"
                  rows="4"
                  placeholder="Escriba aquí el monitoreo..."></textarea>
    </div>

    <div class="flex justify-end gap-3">
        <button type="button"
                onclick="document.getElementById('modalMonitoreo').classList.add('hidden')"
                class="btn-secondary">
            Cancelar
        </button>

        <button type="button"
        onclick="document.getElementById('confirmModal').classList.remove('hidden')"
        class="btn-primary">
            Guardar Monitoreo
        </button>

    </div>
</form>

    <!-- Modal de Confirmación -->
<div id="confirmModal"
     class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-[60]">

    <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6">

        <h3 class="text-xl font-semibold text-gray-900 mb-4">
            Confirmar acción
        </h3>

        <p class="text-gray-700 mb-6">
            ¿Está seguro de que desea cargar este monitoreo?
        </p>

        <div class="flex justify-end gap-3">
            <!-- Cancelar -->
            <button type="button"
                    onclick="document.getElementById('confirmModal').classList.add('hidden')"
                    class="btn-secondary">
                Cancelar
            </button>

            <!-- Confirmar envío -->
            <button
                onclick="document.querySelector('#modalMonitoreo form').submit()"
                class="btn-primary">
                Confirmar
            </button>
        </div>

    </div>
</div>

    </div>
</div>

@endsection
