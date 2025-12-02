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
                                                class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
                                                <div
                                                    class="bg-white rounded-xl shadow-lg w-full max-w-3xl p-6 max-h-[90vh] flex flex-col">

                                                    <h3 class="text-xl font-semibold mb-4 flex items-center text-gray-900">
                                                        <i class="fas fa-edit text-green-600 mr-2"></i> Editar Ovocito
                                                    </h3>


                                                    <form id="formEditOvocito" method="POST"
                                                        action="{{ route('ovocito.actualizar') }}"
                                                        class="flex flex-col h-full">
                                                        @csrf
                                                        <input type="hidden" name="ovocito_id" id="editOvocitoId">
                                                        <input type="hidden" name="criopreservar" id="is_criopreservar"
                                                            value="false">
                                                        <input type="hidden" name="accion" id="accion" value="false">
                                                        <div class="flex-1 overflow-y-auto max-h-[65vh] pr-2">

                                                            <!-- Identificador -->
                                                            <div class="mb-4">
                                                                <label
                                                                    class="font-medium text-gray-700">Identificador</label>
                                                                <input type="text" id="editIdentificador"
                                                                    name="identificador"
                                                                    class="input bg-gray-100 cursor-not-allowed" readonly>
                                                            </div>

                                                            <!-- Estado inicial -->
                                                            <div class="mb-4">
                                                                <label class="font-medium text-gray-700">Estado
                                                                    inicial</label>
                                                                <select id="editEstadoInicial" name="estado_inicial"
                                                                    class="input mt-1" onchange="actualizarCamposEdit()"
                                                                    required>
                                                                    <option value="">Seleccione</option>
                                                                    <option value="muy_inmaduro">Muy inmaduro</option>
                                                                    <option value="inmaduro">Inmaduro</option>
                                                                    <option value="maduro">Maduro</option>
                                                                </select>
                                                            </div>

                                                            <div id="editExtra" class="mt-3"></div>

                                                        </div>

                                                        <div class="flex justify-end gap-3 mt-4">
                                                            <button type="button" onclick="cerrarModalEdit()"
                                                                class="btn-secondary">Cancelar</button>
                                                            <button type="submit" class="btn-primary">Guardar
                                                                Cambios</button>
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

            <div id="modalPuncion" class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">

                <div class="bg-white rounded-xl shadow-lg w-full max-w-3xl p-6 
                max-h-[90vh] flex flex-col">

                    <!-- HEADER (NO SCROLLEA) -->
                    <h3 class="text-xl font-semibold mb-4 flex items-center text-gray-900">
                        <i class="fas fa-syringe text-indigo-600 mr-2"></i> Registrar Nueva Punción
                    </h3>

                    <form id="formPuncion" method="POST" action="{{ route('puncion.guardar') }}"
                        class="flex flex-col h-full">
                        @csrf

                        <!-- CUERPO SCROLLEABLE REAL -->
                        <div class="flex-1 overflow-y-auto max-h-[65vh] pr-2">

                            <input type="hidden" name="paciente_id" value="{{ $paciente->id }}">
                            <input type="hidden" id="pacienteNombre"
                                value="{{ $paciente->nombre }} {{ $paciente->apellido }}">

                            <!-- DATOS PRINCIPALES -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                <div>
                                    <label class="form-label">Fecha *</label>
                                    <input type="date" name="fecha" id="fechaPuncion" class="form-input" required>
                                </div>

                                <div>
                                    <label class="form-label">Hora *</label>
                                    <input type="time" name="hora" id="horaPuncion" class="form-input" required>
                                </div>

                                <div>
                                    <label class="form-label">Número de quirófano *</label>
                                    <input type="text" name="numero_quirofano" id="quirofanoPuncion" class="form-input"
                                        placeholder="Ej: Q-01, Quirófano 1" required>
                                </div>
                            </div>

                            <!-- PACIENTE -->
                            <div class="mb-4">
                                <label class="form-label">Paciente</label>
                                <input type="text" value="{{ $paciente->nombre }} {{ $paciente->apellido }}"
                                    class="form-input bg-gray-100 cursor-not-allowed" readonly>
                            </div>

                            <!-- OVOCITOS -->
                            <div class="border border-gray-200 rounded-lg p-4 bg-gray-50 mb-4">
                                <div class="flex justify-between items-center mb-4">
                                    <h4 class="text-lg font-semibold text-gray-900 flex items-center">
                                        <i class="fas fa-egg text-yellow-600 mr-2"></i>
                                        Ovocitos
                                    </h4>

                                    <button type="button" onclick="agregarOvocito()" class="btn-primary text-sm">
                                        <i class="fas fa-plus mr-1"></i>
                                        Agregar ovocito
                                    </button>
                                </div>

                                <div id="contenedorOvocitos" class="space-y-4"></div>
                            </div>
                        </div>

                        <!-- FOOTER (NO SCROLLEA) -->
                        <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-200">
                            <button type="button" onclick="cerrarModal()" class="btn-secondary">
                                <i class="fas fa-times mr-2"></i>
                                Cancelar
                            </button>

                            <button type="button" onclick="mostrarConfirmacion()" class="btn-primary">
                                <i class="fas fa-save mr-2"></i>
                                Guardar Punción
                            </button>
                        </div>

                    </form>
                </div>
            </div>




            <!-- MODAL CONFIRMACIÓN -->
            <div id="confirmModal"
                class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-[60]">

                <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-6">

                    <div class="flex items-center mb-4">
                        <i class="fas fa-question-circle text-orange-500 text-xl mr-3"></i>
                        <h3 class="text-lg font-semibold text-gray-900">Confirmar acción</h3>
                    </div>

                    <p class="text-gray-600 mb-6">
                        ¿Está seguro de que desea registrar esta punción con todos los ovocitos configurados?
                    </p>

                    <div class="flex justify-end gap-3">

                        <button type="button" onclick="cerrarConfirmacion()" class="btn-secondary">
                            <i class="fas fa-times mr-2"></i>
                            Cancelar
                        </button>

                        <button onclick="document.querySelector('#formPuncion').submit()" class="btn-primary">
                            <i class="fas fa-check mr-2"></i>
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
                <label class="form-label">Acción *</label>
                <select id="editAccion" class="form-input" onchange="actualizarSubCamposEdit()" required>
                    <option value="">Seleccione acción</option>
                    <option value="descartar" ${motivo ? "selected" : ""}>Descartar</option>
                    <option value="tratar_inmaduro" ${tiempo ? "selected" : ""}>Tratar como inmaduro</option>
                </select>
            </div>
            <div id="editDesc" class="mt-3"></div>
        `;
                        } else if (estado === "inmaduro") {
                            box.innerHTML = `
            <div>
                <label class="form-label">Tiempo de maduración (horas) *</label>
                <input type="number" min="1" class="form-input" name="tiempo_maduracion" value="${tiempo}"
                       placeholder="Ingrese horas de maduración">
            </div>
            <div id="editDesc" class="mt-3"></div>
        `;
                        } else if (estado === "maduro") {
                            box.innerHTML = `
            <div>
                <label class="form-label">Destino *</label>
                <select id="editDestino" class="form-input" onchange="actualizarSubCamposEdit()" required>
                    <option value="">Seleccione destino</option>
                    <option value="fecundar" ${destino==="fecundar"?"selected":""}>Fecundación</option>
                    <option value="criopreservar" ${destino==="criopreservar"?"selected":""}>Criopreservación</option>
                    <option value="descartar" ${destino==="descartar"?"selected":""}>Descartar</option>
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
                            box.innerHTML = `<label class="text-sm text-gray-700">Motivo de descarte</label>
                         <textarea class="input mt-1 bg-gray-100" name="motivo_descarte">${motivo}</textarea>`;
                        } else if (estado === "muy_inmaduro" && accion === "tratar_inmaduro") {
                            box.innerHTML =
                                `<label class="text-sm text-gray-700">Tiempo de maduración (hs)</label>
                         <input type="number" min="1" class="input mt-1 bg-gray-100" name="tiempo_maduracion" value="${tiempo}" required>`;
                        } else if (estado === "maduro" && ["fecundar", "criopreservar"].includes(destino)) {
                            box.innerHTML = `<label class="text-sm text-gray-700">Calidad morfológica</label>
                         <select class="input mt-1 bg-gray-100" name="calidad_morfologica" required>
                            <option value="">Seleccione</option>
                            <option value="1" ${calidad==1?"selected":""}>1 — Muy baja</option>
                            <option value="2" ${calidad==2?"selected":""}>2 — Baja</option>
                            <option value="3" ${calidad==3?"selected":""}>3 — Media</option>
                            <option value="4" ${calidad==4?"selected":""}>4 — Buena</option>
                            <option value="5" ${calidad==5?"selected":""}>5 — Excelente</option>
                         </select>`;
                        } else if (estado === "maduro" && destino === "descartar") {
                            box.innerHTML = `<label class="text-sm text-gray-700">Motivo de descarte</label>
                         <textarea class="input mt-1 bg-gray-100" name="motivo_descarte">${motivo}</textarea>`;
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
        <div class="border border-gray-200 rounded-lg p-4 bg-white shadow-sm" id="ovocito_${index}">
            
            <div class="flex justify-between items-start mb-3">
                <div>
                    <h5 class="font-semibold text-gray-900 text-sm">
                        Ovocito ${index}
                    </h5>
                    <p class="text-xs text-gray-500 font-mono">${id}</p>
                </div>
                <button type="button" onclick="quitarOvocito(${index})" 
                        class="text-red-500 hover:text-red-700 transition-colors p-1">
                    <i class="fas fa-trash text-sm"></i>
                </button>
            </div>

            <input type="hidden" name="ovocitos[${index}][id]" value="${id}">

            <div class="space-y-3">
                <div>
                    <label class="form-label">Estado inicial *</label>
                    <select class="form-input"
                            name="ovocitos[${index}][estado_inicial]"
                            onchange="actualizarCampos(${index})"
                            required>
                        <option value="">Seleccione estado</option>
                        <option value="muy_inmaduro">Muy inmaduro</option>
                        <option value="inmaduro">Inmaduro</option>
                        <option value="maduro">Maduro</option>
                    </select>
                </div>

                <div id="extra_${index}"></div>
            </div>

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
                <label class="form-label">Acción *</label>
                <select class="form-input" 
                        name="ovocitos[${i}][accion_muy_inmaduro]"
                        onchange="actualizarSubCampos(${i})"
                        required>
                    <option value="">Seleccione acción</option>
                    <option value="descartar">Descartar</option>
                    <option value="tratar_inmaduro">Tratar como inmaduro</option>
                </select>
            </div>

            <div id="desc_${i}" class="mt-3"></div>
        `;
                        } else if (estado === "inmaduro") {
                            box.innerHTML = `
            <div>
                <label class="form-label">Tiempo de maduración (horas) *</label>
                <input type="number" min="1" class="form-input"
                       name="ovocitos[${i}][tiempo_maduracion]"
                       placeholder="Ingrese horas de maduración">
            </div>
            <div id="desc_${i}" class="mt-3"></div>
        `;
                        } else if (estado === "maduro") {
                            box.innerHTML = `
            <div>
                <label class="form-label">Destino *</label>
                <select class="form-input" 
                        name="ovocitos[${i}][destino_maduro]"
                        onchange="actualizarSubCampos(${i})"
                        required>
                    <option value="">Seleccione destino</option>
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
                <label class="form-label text-red-700">Motivo de descarte *</label>
                <textarea class="form-input border-red-300 focus:border-red-500 focus:ring-red-500" 
                          name="ovocitos[${i}][motivo_descarte]"
                          rows="3"
                          placeholder="Especifique el motivo del descarte..."></textarea>
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
                <label class="form-label">Tiempo de maduración (horas) *</label>
                <input type="number" min="1" 
                       class="form-input"
                       name="ovocitos[${i}][tiempo_maduracion]"
                       placeholder="Ingrese horas de maduración" 
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
            <label class="form-label">Calidad morfológica *</label>
            <select class="form-input" 
                    name="ovocitos[${i}][calidad_morfologica]" required>
                <option value="">Seleccione calidad</option>
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
                <label class="form-label text-red-700">Motivo de descarte *</label>
                <textarea class="form-input border-red-300 focus:border-red-500 focus:ring-red-500" 
                          name="ovocitos[${i}][motivo_descarte]"
                          rows="3"
                          placeholder="Especifique el motivo del descarte..."></textarea>
            </div>
        `;
                        }
                    }
                </script>

            @endsection
        @endsection
