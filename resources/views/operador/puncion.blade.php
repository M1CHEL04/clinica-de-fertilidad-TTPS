@extends('layouts.layoutInterno')
@section('title', 'Punciones - Fertilia')

@section('page-header')
<div class="page-header">
    <div>
        <h1 class="page-title">Gestión de Punciones</h1>
        <p class="page-subtitle">Registro y administración de ovocitos por punción</p>
    </div>
    <div>
        <a href="{{ route('operador.home') }}" class="btn-secondary">
            <i class="fas fa-arrow-left mr-2"></i> Volver al Inicio
        </a>
</div>
@endsection


@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- Columna principal -->
    <div class="lg:col-span-2 space-y-6">

        <!-- PUNCIONES REGISTRADAS -->
        <div class="card p-6">
            <h3 class="text-lg font-semibold mb-4 text-gray-900 flex items-center">
                <i class="fas fa-syringe text-indigo-600 mr-2"></i> Punciones Registradas
            </h3>

            <div class="max-h-96 overflow-y-auto pr-1">

                @forelse (($punciones ?? []) as $puncion)

                    <div x-data="{ open: false }" class="border rounded-lg mb-3">

                        <!-- HEADER -->
                        <button @click="open = !open"
                                class="w-full flex justify-between items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg">

                            <span class="font-medium text-gray-800">
                                {{ \Carbon\Carbon::parse($puncion->fecha)->format('d/m/Y') }} —
                                {{ $puncion->hora }} — Qx: {{ $puncion->numero_quirofano }}
                            </span>

                            <i :class="open ? 'fas fa-chevron-up' : 'fas fa-chevron-down'"
                               class="text-gray-600"></i>
                        </button>

                        <!-- DETALLE -->
                        <div x-show="open" x-transition class="p-4 text-gray-700 border-t">

                            <p class="text-sm text-gray-600 mb-3">
                                Registrado por: <strong>{{ $puncion->operador->nombre ?? 'Operador' }}</strong>
                            </p>

                            <!-- LISTA DE OVOCITOS -->
                            <div class="space-y-3">

                                @foreach ($puncion->ovocitos as $ovocito)
                                    <div x-data="{ detail: false }" class="border rounded-md">

                                        <button @click="detail = !detail"
                                            class="w-full flex justify-between items-center px-3 py-2 bg-gray-50 hover:bg-gray-100">
                                            <span class="font-medium">{{ $ovocito->id_ovocito }}</span>
                                            <i :class="detail ? 'fas fa-chevron-up' : 'fas fa-chevron-down'"
                                               class="text-gray-500"></i>
                                        </button>

                                        <div x-show="detail" x-transition class="p-3 text-sm border-t">
                                            <p><strong>Estado inicial:</strong> {{ $ovocito->estado_inicial }}</p>
                                            <p><strong>Estado final:</strong> {{ $ovocito->estado_final ?? '-' }}</p>
                                            @if($ovocito->tiempo_maduracion)
                                                <p><strong>Tiempo maduración:</strong> {{ $ovocito->tiempo_maduracion }} hs</p>
                                            @endif
                                            @if($ovocito->calidad_morfologica)
                                                <p><strong>Calidad morfológica:</strong> {{ $ovocito->calidad_morfologica }}</p>
                                            @endif
                                            @if($ovocito->motivo_descarte)
                                                <p><strong>Motivo descarte:</strong> {{ $ovocito->motivo_descarte }}</p>
                                            @endif

                                            <div class="mt-2">
                                                <strong>Tracking:</strong>
                                                <ul class="list-disc list-inside text-gray-700 text-xs mt-1">
                                                    @foreach($ovocito->historial as $log)
                                                        <li>{{ $log }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>

                                    </div>
                                @endforeach

                            </div>

                        </div>
                    </div>

                @empty
                    <p class="text-gray-500 text-center py-4">Aún no hay punciones registradas.</p>
                @endforelse

            </div>
        </div>

    </div>

    <!-- Columna lateral -->
    <div class="space-y-6">

        <div class="card p-6">
            <h3 class="text-lg font-semibold mb-4 text-gray-900 flex items-center">
                <i class="fas fa-plus-circle text-green-600 mr-2"></i> Nueva Acción
            </h3>

            <button onclick="document.getElementById('modalPuncion').classList.remove('hidden')"
                class="btn-primary w-full flex items-center justify-center gap-2">
                <i class="fas fa-syringe"></i> Registrar Punción
            </button>
        </div>

        <div class="card p-4 bg-blue-50 border border-blue-200">
            <p class="text-sm text-gray-700">
                <strong>Nota:</strong> Registre cada ovocito y marque su estado para permitir trazabilidad completa.
            </p>
        </div>

    </div>
</div>



<!-- MODAL PRINCIPAL -->
<div id="modalPuncion"
     class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">

    <div class="bg-white rounded-xl shadow-lg w-full max-w-3xl p-6">

        <h3 class="text-xl font-semibold mb-4 flex items-center text-gray-900">
            <i class="fas fa-syringe text-indigo-600 mr-2"></i> Registrar Nueva Punción
        </h3>

        <form id="formPuncion" method="POST" action="{{ route('puncion.guardar') }}">
            @csrf

            <input type="hidden" name="paciente_id" value="{{ $paciente->id }}">
            <input type="hidden" id="pacienteNombre" 
                   value="{{ $paciente->nombre }} {{ $paciente->apellido }}">

            <!-- DATOS PRINCIPALES -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div>
                    <label class="font-medium text-gray-700">Fecha</label>
                    <input type="date" name="fecha" id="fechaPuncion" class="input" required>
                </div>

                <div>
                    <label class="font-medium text-gray-700">Hora</label>
                    <input type="time" name="hora" class="input" required>
                </div>

                <div>
                    <label class="font-medium text-gray-700">Número de quirófano</label>
                    <input type="text" name="numero_quirofano" class="input bg-gray-100" required>
                </div>
            </div>

            <!-- PACIENTE -->
            <div class="mb-4">
                <label class="font-medium text-gray-700">Nombre y apellido del paciente</label>
                <input type="text"
                       value="{{ $paciente->nombre }} {{ $paciente->apellido }}"
                       class="input bg-gray-100 cursor-not-allowed"
                       readonly>
            </div>

            <!-- OVOCITOS -->
            <div class="border rounded-lg p-4 bg-gray-50 mb-4">

                <div class="flex justify-between items-center mb-3">
                    <span class="font-semibold">Ovocitos</span>

                    <button type="button"
                            onclick="agregarOvocito()"
                            class="px-3 py-1 bg-indigo-600 text-white rounded-md text-sm">
                        + Agregar ovocito
                    </button>
                </div>

                <div id="contenedorOvocitos"></div>

            </div>

            <!-- BOTONES -->
            <div class="flex justify-end gap-3">
                <button type="button"
                        onclick="cerrarModal()"
                        class="btn-secondary">
                    Cancelar
                </button>

                <button type="button"
                        onclick="mostrarConfirmacion()"
                        class="btn-primary">
                    Guardar Punción
                </button>
            </div>

        </form>
    </div>
</div>


<!-- MODAL CONFIRMACIÓN -->
<div id="confirmModal"
     class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-[60]">

    <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6">

        <h3 class="text-xl font-semibold text-gray-900 mb-4">Confirmar acción</h3>

        <p class="text-gray-700 mb-6">
            ¿Está seguro de que desea registrar esta punción?
        </p>

        <div class="flex justify-end gap-3">

            <button type="button"
                    onclick="cerrarConfirmacion()"
                    class="btn-secondary">
                Cancelar
            </button>

            <button onclick="document.querySelector('#formPuncion').submit()" 
                    class="btn-primary">
                Confirmar
            </button>

        </div>
    </div>
</div>




@section('scripts')
<script>

let contador = 0;

/* -------------------------
   MODAL PRINCIPAL
------------------------- */
function abrirModal() {
    document.getElementById('modalPuncion').classList.remove('hidden');
}
function cerrarModal() {
    document.getElementById('modalPuncion').classList.add('hidden');
}

/* -------------------------
   MODAL CONFIRMACIÓN
------------------------- */
function mostrarConfirmacion() {
    document.getElementById('confirmModal').classList.remove('hidden');
}
function cerrarConfirmacion() {
    document.getElementById('confirmModal').classList.add('hidden');
}

/* -------------------------
   GENERAR ID OVOCITO
------------------------- */
function generarIdOvocito() {

    const paciente = document.getElementById("pacienteNombre").value
        .trim()
        .split(/\s+/);

    const nombre = paciente[0] ?? "---";
    const apellido = paciente.slice(1).join(" ") || "---";

    const fecha = document.getElementById("fechaPuncion").value;
    if (!fecha) {
        alert("Debe seleccionar la fecha antes de agregar ovocitos.");
        return null;
    }

    const f = fecha.replaceAll("-", ""); // YYYYMMDD
    contador++;

    const random = Math.floor(Math.random() * 9999999)
        .toString()
        .padStart(7, "0");

    return `OVO_${f}_${apellido.substring(0,3).toUpperCase()}_${nombre.substring(0,3).toUpperCase()}_${contador}_${random}`;
}

/* -------------------------
   AGREGAR OVOCITO
------------------------- */
function agregarOvocito() {

    const id = generarIdOvocito();
    if (!id) return;

    const index = contador;
    const cont = document.getElementById("contenedorOvocitos");

    cont.insertAdjacentHTML(
        "beforeend",
        `
        <div class="border rounded-md p-3 mb-3 bg-white" id="ovocito_${index}">
            
            <div class="flex justify-between items-center">
                <p class="font-medium text-gray-800">
                    Ovocito ${index} —
                    <span class="text-xs text-gray-500">${id}</span>
                </p>
                <button type="button" onclick="quitarOvocito(${index})" class="text-red-500 text-sm">
                    <i class="fas fa-trash"></i>
                </button>
            </div>

            <input type="hidden" name="ovocitos[${index}][id]" value="${id}">

            <div class="mt-2">
                <label class="text-sm text-gray-700">Estado inicial</label>
                <select class="input mt-1"
                        name="ovocitos[${index}][estado_inicial]"
                        onchange="actualizarCampos(${index})"
                        required>
                    <option value="">Seleccione</option>
                    <option value="muy_inmaduro">Muy inmaduro</option>
                    <option value="inmaduro">Inmaduro</option>
                    <option value="maduro">Maduro</option>
                </select>
            </div>

            <div id="extra_${index}" class="mt-3"></div>

        </div>
        `
    );
}

/* -------------------------
   QUITAR OVOCITO
------------------------- */
function quitarOvocito(i) {
    document.getElementById(`ovocito_${i}`).remove();
}

/* -------------------------
   ACTUALIZAR CAMPOS PRINCIPALES
------------------------- */
function actualizarCampos(i) {

    const estado = document.querySelector(`select[name="ovocitos[${i}][estado_inicial]"]`).value;
    const box = document.getElementById(`extra_${i}`);

    if (!estado) {
        box.innerHTML = "";
        return;
    }

    if (estado === "muy_inmaduro") {
        box.innerHTML = `
            <label class="text-sm text-gray-700">Acción</label>
            <select class="input mt-1" 
                    name="ovocitos[${i}][accion_muy_inmaduro]"
                    onchange="actualizarSubCampos(${i})"
                    required>
                <option value="">Seleccione</option>
                <option value="descartar">Descartar</option>
                <option value="tratar_inmaduro">Tratar como inmaduro</option>
            </select>

            <div id="desc_${i}" class="mt-2"></div>
        `;
    }

    else if (estado === "inmaduro") {
        box.innerHTML = `
            <label class="text-sm text-gray-700">Tiempo de maduración (hs)</label>
            <input type="number" min="1" class="input mt-1 bg-gray-100"
                   name="ovocitos[${i}][tiempo_maduracion]">
            <div id="desc_${i}" class="mt-2"></div>
        `;
    }

    else if (estado === "maduro") {
        box.innerHTML = `
            <label class="text-sm text-gray-700">Destino</label>
            <select class="input mt-1" 
                    name="ovocitos[${i}][destino_maduro]"
                    onchange="actualizarSubCampos(${i})"
                    required>
                <option value="">Seleccione</option>
                <option value="fecundar">Fecundación</option>
                <option value="criopreservar">Criopreservación</option>
                <option value="descartar">Descartar</option>
            </select>

            <div id="desc_${i}" class="mt-2"></div>
        `;
    }
}

/* -------------------------
   SUBCAMPOS SEGÚN OPCIONES
------------------------- */
function actualizarSubCampos(i) {

    const estado = document.querySelector(`select[name="ovocitos[${i}][estado_inicial]"]`)?.value;
    const destino = document.querySelector(`select[name="ovocitos[${i}][destino_maduro]"]`)?.value;
    const accion = document.querySelector(`select[name="ovocitos[${i}][accion_muy_inmaduro]"]`)?.value;

    const zone = document.getElementById(`desc_${i}`);

    // limpiar primero
    zone.innerHTML = "";

    // Muy inmaduro → descarte
    if (estado === "muy_inmaduro" && accion === "descartar") {
        zone.innerHTML = `
            <label class="text-sm text-gray-700">Motivo de descarte</label>
            <textarea class="input mt-1 bg-gray-100" name="ovocitos[${i}][motivo_descarte]"></textarea>
        `;
    }

    // Maduro → calidad morfológica
    else if (estado === "maduro" && ["fecundar", "criopreservar"].includes(destino)) {
        zone.innerHTML = `
            <label class="text-sm text-gray-700">Calidad morfológica</label>
            <input type="text" class="input mt-1 bg-gray-100" name="ovocitos[${i}][calidad_morfologica]">
        `;
    }

    // Maduro → descarte
    else if (estado === "maduro" && destino === "descartar") {
        zone.innerHTML = `
            <label class="text-sm text-gray-700">Motivo de descarte</label>
            <textarea class="input mt-1 bg-gray-100" name="ovocitos[${i}][motivo_descarte]"></textarea>
        `;
    }
}

</script>

@endsection
@endsection