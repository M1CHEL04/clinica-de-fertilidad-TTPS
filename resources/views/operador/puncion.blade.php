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
                                        Fecha: {{ $puncion->fecha_hora }}

                                    </span>
                                    <span class="font-medium text-gray-800">Quirofano: {{ $puncion->nro_quirofano }}</span>

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

                                                <!-- HEADER DEL OVOCITO -->
                                                <div
                                                    class="flex justify-between items-center bg-gray-50 px-3 py-2 hover:bg-gray-100 rounded-t-md">
                                                    <span class="font-medium">Identificador:
                                                        {{ $ovocito->identificador }}</span>

                                                    <div class="flex items-center gap-2">
                                                        <!-- Botón historial -->
                                                        <button
                                                            onclick="document.getElementById('modal-{{ $ovocito->id }}').classList.remove('hidden')"
                                                            class="text-blue-500 hover:text-blue-700">
                                                            <i class="fas fa-history"></i>
                                                        </button>
                                                        <!-- Botón editar -->
                                                        <button type="button" onclick="abrirModalEdit({{ $ovocito->id }})"
                                                            class="text-green-600 hover:text-green-800">
                                                            <i class="fas fa-edit"></i>
                                                        </button>


                                                        <!-- Botón desplegar detalle -->
                                                        <button @click="detail = !detail">
                                                            <i :class="detail ? 'fas fa-chevron-up' : 'fas fa-chevron-down'"
                                                                class="text-gray-500"></i>
                                                        </button>
                                                    </div>
                                                </div>

                                                <!-- DETALLE DEL OVOCITO -->
                                                <div x-show="detail" x-transition
                                                    class="p-3 bg-gray-50 border-t text-gray-700">
                                                    @php
                                                        $estadoObj = optional($ovocito->estado_ovocito);
                                                        $tipoEstado = optional($estadoObj->TipoEstadoOvocito);
                                                        $motivoDescarte = $estadoObj->motivo_descarte;
                                                        $nombreEstado = $tipoEstado->nombre
                                                            ? strtolower($tipoEstado->nombre)
                                                            : null;
                                                        $tiempoMaduracion = optional($ovocito->estado_ovocito)
                                                            ->tiempo_maduracion;
                                                        $guardado = $ovocito->guardado_id
                                                            ? strtolower($ovocito->guardado_id)
                                                            : null;
                                                        $estadoFinal = '';

                                                        if ($motivoDescarte) {
                                                            $estadoFinal = 'Descartado';
                                                        } elseif ($guardado != null) {
                                                            $estadoFinal = 'Criopreservado';
                                                        } elseif ($nombreEstado === 'maduro' && !$guardado) {
                                                            $estadoFinal = 'Listo para fecundar';
                                                        } elseif ($tiempoMaduracion) {
                                                            $estadoFinal = 'Madurando';
                                                        } else {
                                                            $estadoFinal = $tipoEstado->nombre ?? 'Sin estado';
                                                        }
                                                    @endphp


                                                    <p><strong>Estado:</strong> {{ $tipoEstado->nombre ?? 'Sin estado' }}
                                                    </p>
                                                    <p><strong>Condición:</strong> {{ $estadoFinal }}</p>

                                                    @if ($tiempoMaduracion)
                                                        <p><strong>Tiempo maduración:</strong> {{ $tiempoMaduracion }} hs
                                                        </p>
                                                    @endif
                                                    @if ($ovocito->calidad_morfologica)
                                                        <p><strong>Calidad morfológica:</strong>
                                                            {{ $ovocito->calidad_morfologica }}</p>
                                                    @endif
                                                    @if ($motivoDescarte)
                                                        <p><strong>Motivo de descarte:</strong> {{ $motivoDescarte }}</p>
                                                    @endif
                                                    @if ($ovocito->guardado_id)
                                                        <p><strong>Tanque:</strong> {{ $ovocito->guardado->id_tanque }}</p>
                                                        <p><strong>Rack:</strong> {{ $ovocito->guardado->id_rack }}</p>
                                                    @endif
                                                </div>

                                            </div>

                                            <!-- MODAL HISTORIAL OVOCITO -->
                                            <div id="modal-{{ $ovocito->id }}"
                                                class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                                                <div
                                                    class="bg-white rounded-xl w-full max-w-2xl max-h-[80vh] p-6 flex flex-col">

                                                    <!-- HEADER -->
                                                    <div class="flex justify-between items-center mb-4">
                                                        <h3 class="text-lg font-semibold text-gray-900">Historial Ovocito
                                                            {{ $ovocito->identificador }}</h3>
                                                        <button
                                                            onclick="document.getElementById('modal-{{ $ovocito->id }}').classList.add('hidden')"
                                                            class="text-gray-600 hover:text-gray-900 text-xl">&times;</button>
                                                    </div>

                                                    <!-- CONTENIDO SCROLLEABLE -->
                                                    <div class="overflow-y-auto flex-1 pr-2">
                                                        @forelse($ovocito->historial as $h)
                                                            <div class="border-b py-2">
                                                                <p><strong>Fecha:</strong> {{ $h->fecha_cambio }}</p>
                                                                <p><strong>Acción:</strong> {{ $h->accion }}</p>

                                                                <p><strong>Estado anterior:</strong>
                                                                    {{ $h->estadoAnterior->nombre ?? 'N/A' }}</p>
                                                                <p><strong>Estado nuevo:</strong>
                                                                    {{ $h->estadoNuevo->nombre ?? 'N/A' }}</p>
                                                                @if ($h->motivo_descarte)
                                                                    <p><strong>Motivo de descarte:</strong>
                                                                        {{ $h->motivo_descarte }}</p>
                                                                @endif
                                                                @if ($h->tiempo_maduracion)
                                                                    <p><strong>Tiempo maduración:</strong>
                                                                        {{ $h->tiempo_maduracion }} hs</p>
                                                                @endif
                                                            </div>
                                                        @empty
                                                            <p class="text-gray-500">No hay historial registrado.</p>
                                                        @endforelse
                                                    </div>

                                                    <!-- FOOTER -->
                                                    <div class="flex justify-end mt-4">
                                                        <button
                                                            onclick="document.getElementById('modal-{{ $ovocito->id }}').classList.add('hidden')"
                                                            class="btn-secondary">Cerrar</button>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- MODAL EDITAR OVOCITO -->
                                            <div id="modalEditOvocito"
                                                class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                                                <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">

                                                    <!-- HEADER -->
                                                    <div
                                                        class="flex justify-between items-center px-6 py-4 border-b border-gray-200">
                                                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                                            <i class="fas fa-edit text-green-600 mr-2"></i> Editar Ovocito
                                                        </h3>
                                                        <button type="button" onclick="cerrarModalEdit()"
                                                            class="text-gray-400 hover:text-gray-600 text-xl">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </div>

                                                    <form id="formEditOvocito" method="POST"
                                                        action="{{ route('ovocito.actualizar') }}">
                                                        @csrf
                                                        <input type="hidden" name="ovocito_id" id="editOvocitoId">
                                                        <input type="hidden" name="criopreservar" id="is_criopreservar"
                                                            value="false">
                                                        <input type="hidden" name="accion" id="accion" value="false">

                                                        <!-- CONTENIDO -->
                                                        <div class="px-6 py-4 space-y-4">

                                                            <!-- Identificador -->
                                                            <div>
                                                                <label
                                                                    class="block text-sm font-medium text-gray-700 mb-1">Identificador</label>
                                                                <input type="text" id="editIdentificador"
                                                                    name="identificador"
                                                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md bg-gray-50"
                                                                    readonly>
                                                            </div>

                                                            <!-- Estado inicial -->
                                                            <div>
                                                                <label
                                                                    class="block text-sm font-medium text-gray-700 mb-1">Estado
                                                                    inicial *</label>
                                                                <select id="editEstadoInicial" name="estado_inicial"
                                                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500"
                                                                    onchange="actualizarCamposEdit()" required>
                                                                    <option value="">Seleccione un estado</option>
                                                                    <option value="muy_inmaduro">Muy inmaduro</option>
                                                                    <option value="inmaduro">Inmaduro</option>
                                                                    <option value="maduro">Maduro</option>
                                                                </select>
                                                            </div>

                                                            <!-- Campos dinámicos -->
                                                            <div id="editExtra"></div>

                                                        </div>

                                                        <!-- FOOTER -->
                                                        <div
                                                            class="flex justify-end gap-3 px-6 py-4 border-t border-gray-200 bg-gray-50">
                                                            <button type="button" onclick="cerrarModalEdit()"
                                                                class="btn-secondary">
                                                                Cancelar
                                                            </button>
                                                            <button type="submit"
                                                                class="btn-primary bg-green-600 hover:bg-green-700">
                                                                <i class="fas fa-save mr-2"></i> Guardar cambios
                                                            </button>
                                                        </div>

                                                    </form>
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
            <div id="modalPuncion" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white rounded-lg shadow-xl w-full max-w-3xl mx-4 max-h-[90vh] flex flex-col">

                    <!-- HEADER -->
                    <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-syringe text-indigo-600 mr-2"></i> Registrar Nueva Punción
                        </h3>
                        <button type="button" onclick="cerrarModal()" class="text-gray-400 hover:text-gray-600 text-xl">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <form id="formPuncion" method="POST" action="{{ route('puncion.guardar') }}"
                        class="flex flex-col flex-1 overflow-hidden">
                        @csrf

                        <!-- CONTENIDO SCROLLEABLE -->
                        <div class="flex-1 overflow-y-auto px-6 py-4">

                            <input type="hidden" name="paciente_id" value="{{ $paciente->id }}">
                            <input type="hidden" id="pacienteNombre"
                                value="{{ $paciente->nombre }} {{ $paciente->apellido }}">

                            <!-- DATOS PRINCIPALES -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Fecha *</label>
                                    <input type="date" name="fecha" id="fechaPuncion"
                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                        required>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Hora *</label>
                                    <input type="time" name="hora" id="horaPuncion"
                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                        required>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nº Quirófano *</label>
                                    <input type="text" name="numero_quirofano" id="quirofanoPuncion"
                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                        required>
                                </div>
                            </div>

                            <!-- PACIENTE -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Paciente</label>
                                <input type="text" value="{{ $paciente->nombre }} {{ $paciente->apellido }}"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md bg-gray-50 cursor-not-allowed"
                                    readonly>
                            </div>

                            <!-- OVOCITOS -->
                            <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                                <div class="flex justify-between items-center mb-3">
                                    <span class="text-sm font-semibold text-gray-900">Ovocitos</span>
                                    <button type="button" onclick="agregarOvocito()"
                                        class="px-3 py-1.5 bg-indigo-600 text-white text-sm rounded-md hover:bg-indigo-700 transition-colors">
                                        <i class="fas fa-plus mr-1"></i> Agregar
                                    </button>
                                </div>
                                <div id="contenedorOvocitos" class="space-y-3"></div>
                            </div>
                        </div>

                        <!-- FOOTER -->
                        <div class="flex justify-end gap-3 px-6 py-4 border-t border-gray-200 bg-gray-50">
                            <button type="button" onclick="cerrarModal()" class="btn-secondary">
                                Cancelar
                            </button>
                            <button type="button" onclick="mostrarConfirmacion()" class="btn-primary">
                                <i class="fas fa-save mr-2"></i> Guardar Punción
                            </button>
                        </div>

                    </form>
                </div>
            </div>




            <!-- MODAL CONFIRMACIÓN -->
            <div id="confirmModal"
                class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-[60]">
                <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">

                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Confirmar acción</h3>
                    </div>

                    <div class="px-6 py-4">
                        <p class="text-sm text-gray-600">
                            ¿Está seguro de que desea registrar esta punción?
                        </p>
                    </div>

                    <div class="flex justify-end gap-3 px-6 py-4 border-t border-gray-200 bg-gray-50">
                        <button type="button" onclick="cerrarConfirmacion()" class="btn-secondary">
                            Cancelar
                        </button>
                        <button onclick="document.querySelector('#formPuncion').submit()" class="btn-primary">
                            <i class="fas fa-check mr-2"></i> Confirmar
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

                    function abrirModalEdit(ovocitoId) {
                        // Traer la info del ovocito desde el servidor
                        fetch(`/ovocito/${ovocitoId}/json`)
                            .then(response => {
                                if (!response.ok) throw new Error("Error al traer el ovocito");
                                return response.json();
                            })
                            .then(ovocito => {
                                console.log(ovocito)
                                // Cargar datos en el modal
                                document.getElementById("editOvocitoId").value = ovocito.id;
                                document.getElementById("editIdentificador").value = ovocito.identificador;

                                // Estado inicial

                                let estado = ovocito.estado_ovocito?.tipo || ""; // "Muy inmaduro"

                                // Normalizamos a value del select
                                estado = estado.toLowerCase().replace(" ", "_"); // "muy_inmaduro"
                                document.getElementById("editEstadoInicial").value = estado;
                                console.log(document.getElementById("editEstadoInicial").value)

                                // Renderizar campos extra según estado y valores actuales
                                actualizarCamposEdit(ovocito);

                                // Abrir modal
                                document.getElementById("modalEditOvocito").classList.remove("hidden");
                            })
                            .catch(err => {
                                console.error(err);
                                alert("No se pudo cargar la información del ovocito.");
                            });
                    }



                    function cerrarModalEdit() {
                        document.getElementById("modalEditOvocito").classList.add("hidden");
                    }

                    function actualizarCamposEdit(data = null) {

                        const estado = document.getElementById("editEstadoInicial").value;
                        const box = document.getElementById("editExtra");
                        console.log("EL ESTADO ES " + estado)

                        box.innerHTML = "";

                        if (!estado) return;

                        const tiempo = data?.estado_ovocito?.tiempo_maduracion || "";
                        const calidad = data?.calidad_morfologica || "";
                        const motivo = data?.estado_ovocito?.motivo_descarte || "";
                        const destino = data?.estado_ovocito?.TipoEstadoOvocito?.nombre?.toLowerCase() || "";

                        if (estado === "muy_inmaduro") {
                            box.innerHTML = `
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Acción *</label>
                <select id="editAccion" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md" onchange="actualizarSubCamposEdit()" required>
                    <option value="">Seleccione una acción</option>
                    <option value="descartar" ${motivo ? "selected" : ""}>Descartar</option>
                    <option value="tratar_inmaduro" ${tiempo ? "selected" : ""}>Tratar como inmaduro</option>
                </select>
            </div>
            <div id="editDesc" class="mt-3"></div>
        `;
                        } else if (estado === "inmaduro") {
                            box.innerHTML = `
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tiempo de maduración (horas) *</label>
                <input type="number" min="1" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md" name="tiempo_maduracion" value="${tiempo}"
                       placeholder="Ej: 24" required>
            </div>
            <div id="editDesc" class="mt-3"></div>
        `;
                        } else if (estado === "maduro") {
                            box.innerHTML = `
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Destino *</label>
                <select id="editDestino" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md" onchange="actualizarSubCamposEdit()" required>
                    <option value="">Seleccione un destino</option>
                    <option value="fecundar" ${destino==="fecundar"?"selected":""}}>Fecundación</option>
                    <option value="criopreservar" ${destino==="criopreservar"?"selected":""}}>Criopreservación</option>
                    <option value="descartar" ${destino==="descartar"?"selected":""}}>Descartar</option>
                </select>
            </div>
            <div id="editDesc" class="mt-3"></div>
        `;
                        }

                        actualizarSubCamposEdit(data);
                    }

                    function actualizarSubCamposEdit(data = null) {
                        const estado = document.getElementById("editEstadoInicial").value;
                        const accion = document.getElementById("editAccion")?.value;
                        const destino = document.getElementById("editDestino")?.value;
                        const box = document.getElementById("editDesc");

                        box.innerHTML = "";

                        const tiempo = data?.estado_ovocito?.tiempo_maduracion || "";
                        const calidad = data?.calidad_morfologica || "";
                        const motivo = data?.estado_ovocito?.motivo_descarte || "";
                        // Primero, obtenemos o creamos el input oculto
                        let inputCriopreservar = document.getElementById("is_criopreservar");
                        if (!inputCriopreservar) {
                            inputCriopreservar = document.createElement("input");
                            inputCriopreservar.type = "hidden";
                            inputCriopreservar.id = "is_criopreservar";
                            inputCriopreservar.name = "is_criopreservar";
                            document.querySelector("form").appendChild(inputCriopreservar); // o donde esté tu form
                        }
                        let inputAccion = document.getElementById("accion")


                        // Determinar la acción según el select visible
                        let accionValue = "";
                        if (estado === "muy_inmaduro") {
                            accionValue = accion || ""; // "descartar" o "tratar_inmaduro"
                        } else if (estado === "maduro") {
                            accionValue = destino || ""; // "fecundar", "criopreservar", "descartar"
                        } else {
                            accionValue = ""; // inactivo para inmaduro
                        }

                        inputAccion.value = accionValue;
                        // Ahora seteamos el valor según el destino
                        inputCriopreservar.value = destino === "criopreservar" ? "true" : "false";


                        if (estado === "muy_inmaduro" && accion === "descartar") {
                            box.innerHTML = `
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Motivo de descarte</label>
                    <textarea class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md resize-none" name="motivo_descarte" rows="2" placeholder="Especifique el motivo...">${motivo}</textarea>
                </div>
            `;
                        } else if (estado === "muy_inmaduro" && accion === "tratar_inmaduro") {
                            box.innerHTML = `
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tiempo de maduración (horas)</label>
                    <input type="number" min="1" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md" name="tiempo_maduracion" value="${tiempo}" placeholder="Ej: 24" required>
                </div>
            `;
                        } else if (estado === "maduro" && ["fecundar", "criopreservar"].includes(destino)) {
                            box.innerHTML = `
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Calidad morfológica</label>
                    <select class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md" name="calidad_morfologica" required>
                        <option value="">Seleccione la calidad</option>
                        <option value="1" ${calidad==1?"selected":""}}>1 — Muy baja</option>
                        <option value="2" ${calidad==2?"selected":""}}>2 — Baja</option>
                        <option value="3" ${calidad==3?"selected":""}}>3 — Media</option>
                        <option value="4" ${calidad==4?"selected":""}}>4 — Buena</option>
                        <option value="5" ${calidad==5?"selected":""}}>5 — Excelente</option>
                    </select>
                </div>
            `;
                        } else if (estado === "maduro" && destino === "descartar") {
                            box.innerHTML = `
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Motivo de descarte</label>
                    <textarea class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md resize-none" name="motivo_descarte" rows="2" placeholder="Especifique el motivo...">${motivo}</textarea>
                </div>
            `;
                        }
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
                        const hora = document.getElementById("horaPuncion").value;
                        if (!hora) {
                            alert("Debe seleccionar la hora antes de agregar ovocitos.");
                            return null;
                        }
                        const quirofano = document.getElementById("quirofanoPuncion").value;
                        if (!quirofano) {
                            alert("Debe seleccionar el numero de quirofano antes de agregar ovocitos.");
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
        <div class="border border-gray-200 rounded-lg p-3 bg-white" id="ovocito_${index}">
            
            <div class="flex justify-between items-center mb-3">
                <p class="text-sm font-medium text-gray-900">
                    Ovocito ${index}
                    <span class="text-xs text-gray-500 ml-1">${id}</span>
                </p>
                <button type="button" onclick="quitarOvocito(${index})" class="text-red-500 hover:text-red-700 text-sm">
                    <i class="fas fa-trash"></i>
                </button>
            </div>

            <input type="hidden" name="ovocitos[${index}][id]" value="${id}">

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Estado inicial *</label>
                <select class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
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
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Acción *</label>
                <select class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500" 
                        name="ovocitos[${i}][accion_muy_inmaduro]"
                        onchange="actualizarSubCampos(${i})"
                        required>
                    <option value="">Seleccione</option>
                    <option value="descartar">Descartar</option>
                    <option value="tratar_inmaduro">Tratar como inmaduro</option>
                </select>
            </div>

            <div id="desc_${i}" class="mt-3"></div>
        `;
                        } else if (estado === "inmaduro") {
                            box.innerHTML = `
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tiempo de maduración (horas)</label>
                <input type="number" min="1" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                       name="ovocitos[${i}][tiempo_maduracion]" placeholder="Ej: 24">
            </div>
            <div id="desc_${i}" class="mt-3"></div>
        `;
                        } else if (estado === "maduro") {
                            box.innerHTML = `
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Destino *</label>
                <select class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500" 
                        name="ovocitos[${i}][destino_maduro]"
                        onchange="actualizarSubCampos(${i})"
                        required>
                    <option value="">Seleccione</option>
                    <option value="fecundar">Fecundación</option>
                    <option value="criopreservar">Criopreservación</option>
                    <option value="descartar">Descartar</option>
                </select>
            </div>

            <div id="desc_${i}" class="mt-3"></div>
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
                        zone.innerHTML = "";

                        /* -------------------------
                           MUY INMADURO → DESCARTAR
                        ------------------------- */
                        if (estado === "muy_inmaduro" && accion === "descartar") {
                            zone.innerHTML = `
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Motivo de descarte</label>
                <textarea class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md resize-none focus:outline-none focus:ring-2 focus:ring-indigo-500" 
                          name="ovocitos[${i}][motivo_descarte]" rows="2" placeholder="Especifique el motivo..."></textarea>
            </div>
        `;
                        }

                        /* -------------------------
                           MUY INMADURO → TRATAR COMO INMADURO
                           → debe pedir tiempo de maduración
                        ------------------------- */
                        else if (estado === "muy_inmaduro" && accion === "tratar_inmaduro") {
                            zone.innerHTML = `
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tiempo de maduración (horas) *</label>
                <input type="number" min="1" 
                       class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                       name="ovocitos[${i}][tiempo_maduracion]" 
                       placeholder="Ej: 24"
                       required>
            </div>
        `;
                        }

                        /* -------------------------
                           MADURO → Fecundar / Criopreservar
                           → requiere calidad morfológica
                        ------------------------- */
                        else if (estado === "maduro" && ["fecundar", "criopreservar"].includes(destino)) {
                            zone.innerHTML = `
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Calidad morfológica *</label>
            <select class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500" 
                    name="ovocitos[${i}][calidad_morfologica]" required>
                <option value="">Seleccione</option>
                <option value="1">1 — Muy baja</option>
                <option value="2">2 — Baja</option>
                <option value="3">3 — Media</option>
                <option value="4">4 — Buena</option>
                <option value="5">5 — Excelente</option>
            </select>
        </div>
    `;
                        }


                        /* -------------------------
                           MADURO → Descarta
                        ------------------------- */
                        else if (estado === "maduro" && destino === "descartar") {
                            zone.innerHTML = `
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Motivo de descarte</label>
                <textarea class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md resize-none focus:outline-none focus:ring-2 focus:ring-indigo-500" 
                          name="ovocitos[${i}][motivo_descarte]" rows="2" placeholder="Especifique el motivo..."></textarea>
            </div>
        `;
                        }
                    }
                </script>

            @endsection
        @endsection
