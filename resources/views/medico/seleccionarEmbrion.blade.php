@extends('layouts.layoutInterno')
@section('title', 'Seleccionar Embrión - Fertilia')

@section('page-header')
    <div class="page-header">
        <div>
            <h1 class="page-title">Seleccionar embrión a transferir</h1>
            <p class="page-subtitle">
                Paciente: {{ $paciente->nombre }} {{ $paciente->apellido }} · DNI: {{ $paciente->dni }}
            </p>
        </div>

        @php
            $prefix = session('rol') == 3 ? 'operador' : (session('rol') == 5 ? 'jefe' : 'medico');
        @endphp
        <div>
            <a href="{{ url("$prefix/paciente/$tratamiento->id/tratamiento") }}" class="btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i> Volver al tratamiento
            </a>
        </div>
    </div>
@endsection

@section('content')
    <!-- Si no hay embriones -->
    @if ($embriones->isEmpty())
        <div class="card p-12 text-center">
            <i class="fas fa-seedling text-6xl text-gray-300 mb-4"></i>
            <p class="text-gray-600 text-lg">Este paciente no tiene embriones disponibles.</p>
        </div>
    @else
        <!-- Lista de embriones -->
        <form method="POST"
            action="{{ session('rol') == 5 ? route('jefe.transferencia.guardar') : route('medico.transferencia.guardar') }}"
            id="formTransferencia">

            @csrf
            <input type="hidden" name="paciente_id" value="{{ $paciente->id }}">

            <div class="mb-4 p-4 bg-blue-50 border-l-4 border-blue-500 rounded">
                <p class="text-sm text-blue-800">
                    <i class="fas fa-info-circle mr-2"></i>
                    Puede seleccionar hasta <strong>3 embriones</strong> para transferir.
                    <span class="text-blue-700">Embriones seleccionados: <strong
                            id="contadorSeleccionados">0</strong></span>
                </p>
            </div>

            <!-- Alerta de riesgo (oculta por defecto) -->
            <div id="alertaRiesgo"
                class="hidden mb-4 p-4 bg-yellow-50 border-l-4 border-yellow-500 rounded animate-fade-in">
                <div class="flex items-start">
                    <i class="fas fa-exclamation-triangle text-yellow-600 text-xl mr-3 mt-1"></i>
                    <div>
                        <h4 class="text-yellow-800 font-semibold mb-1">Advertencia: Riesgo de embarazo múltiple</h4>
                        <p class="text-yellow-700 text-sm">
                            La transferencia de múltiples embriones aumenta significativamente el riesgo de embarazo
                            múltiple.
                            Por favor, considere cuidadosamente los riesgos asociados antes de continuar.
                        </p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach ($embriones as $embrion)
                    @php

                        $estaCriopreservado = $embrion->criopreservado == true;
                        $tieneDescarte = !is_null($embrion->motivo_descarte);
                        $estaUtilizado = $embrion->utilizado;

                        // → Embrión seleccionable solo si NO está criopreservado, NO descartado y NO utilizado
                        $disabled = $estaCriopreservado || $tieneDescarte || $estaUtilizado;
                    @endphp

                    <label class="block cursor-pointer">

                        <input type="checkbox" name="embriones_ids[]" value="{{ $embrion->id }}"
                            class="peer hidden embrion-checkbox" @if ($disabled) disabled @endif
                            data-embrion-id="{{ $embrion->id }}">

                        <div
                            class="
                        card p-4 transition-all
                        @if ($disabled) opacity-50 cursor-not-allowed
                        @else
                            hover:shadow-lg peer-checked:border-blue-500 peer-checked:ring-2 peer-checked:ring-blue-300 @endif
                    ">
                            <div class="flex items-start justify-between mb-3">
                                <h4 class="text-sm font-medium text-gray-900 truncate pr-2">
                                    {{ $embrion->identificador }}
                                </h4>

                                <!-- Etiquetas de estado -->
                                @if ($estaUtilizado)
                                    <span class="badge badge-danger">
                                        Utilizado
                                    </span>
                                @elseif($tieneDescarte)
                                    <span class="badge badge-danger">
                                        Descartado
                                    </span>
                                @elseif($estaCriopreservado)
                                    <span class="badge badge-info">
                                        Criopreservado
                                    </span>
                                @else
                                    <span class="badge badge-success">
                                        Disponible
                                    </span>
                                @endif
                            </div>

                            <div class="space-y-2 text-sm">
                                <div>
                                    <span class="font-medium text-gray-600">Calidad:</span>
                                    <span class="text-gray-800">Grado
                                        {{ $embrion->calidad_morfologica ?? 'Sin evaluar' }}</span>
                                </div>

                                <div>
                                    <span class="font-medium text-gray-600">PGT:</span>
                                    @if ($embrion->realizo_PGT == true)
                                        @if ($embrion->pgt_positivo)
                                            <span class="text-red-600 font-medium">Positivo</span>
                                        @else
                                            <span class="text-green-600 font-medium">Negativo</span>
                                        @endif
                                    @else
                                        <span class="text-gray-500">No realizado</span>
                                    @endif
                                </div>

                                <div>
                                    <span class="font-medium text-gray-600">Origen gametos:</span>
                                    @if ($embrion->semen_dni != null)
                                        <span class="text-gray-800">Semen criopreservado</span>
                                    @elseif($embrion->semen_fresco == true)
                                        <span class="text-gray-800">Semen fresco</span>
                                    @else
                                        <span class="text-gray-800">Semen donado</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </label>
                @endforeach
            </div>

            <div class="flex justify-end mt-6">
                <button type="submit" class="btn-primary" id="btnConfirmar" disabled>
                    <i class="fas fa-check mr-2"></i> Confirmar transferencia
                </button>
            </div>

        </form>
    @endif
@endsection

@section('scripts')
    <style>
        @keyframes fade-in {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fade-in 0.3s ease-out;
        }

        /* Estilo para modal de confirmación personalizado */
        .modal-overlay {
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
        }

        .modal-content {
            animation: modal-appear 0.2s ease-out;
        }

        @keyframes modal-appear {
            from {
                opacity: 0;
                transform: scale(0.95);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }
    </style>

    <script>
        let embrionesSeleccionados = [];
        const MAX_EMBRIONES = 3;
        let alertaMostrada = false;

        document.addEventListener('DOMContentLoaded', function() {
            const checkboxes = document.querySelectorAll('.embrion-checkbox');
            const contador = document.getElementById('contadorSeleccionados');
            const btnConfirmar = document.getElementById('btnConfirmar');
            const form = document.getElementById('formTransferencia');
            const alertaRiesgo = document.getElementById('alertaRiesgo');

            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    actualizarSeleccion();
                });
            });

            function actualizarSeleccion() {
                const seleccionados = document.querySelectorAll('.embrion-checkbox:checked');
                const cantidad = seleccionados.length;

                // Actualizar contador visual
                contador.textContent = cantidad;

                // Actualizar array de IDs
                embrionesSeleccionados = Array.from(seleccionados).map(cb => cb.value);

                // Habilitar/deshabilitar botón
                if (cantidad > 0) {
                    btnConfirmar.disabled = false;
                    btnConfirmar.classList.remove('opacity-50', 'cursor-not-allowed');
                } else {
                    btnConfirmar.disabled = true;
                    btnConfirmar.classList.add('opacity-50', 'cursor-not-allowed');
                }

                // Limitar a 3 embriones
                if (cantidad >= MAX_EMBRIONES) {
                    checkboxes.forEach(cb => {
                        if (!cb.checked && !cb.disabled) {
                            cb.disabled = true;
                            cb.closest('label').classList.add('opacity-50', 'cursor-not-allowed');
                        }
                    });
                } else {
                    checkboxes.forEach(cb => {
                        const label = cb.closest('label');
                        const card = label.querySelector('.card');

                        if (!card.classList.contains('opacity-50') || cb.checked) {
                            cb.disabled = false;
                            if (cb.checked) {
                                label.classList.remove('opacity-50', 'cursor-not-allowed');
                            }
                        }
                    });
                    alertaMostrada = false;
                }

                // Mostrar/ocultar alerta de riesgo
                if (cantidad >= 2) {
                    alertaRiesgo.classList.remove('hidden');
                    if (!alertaMostrada) {
                        alertaMostrada = true;
                        // Scroll suave hacia la alerta
                        alertaRiesgo.scrollIntoView({
                            behavior: 'smooth',
                            block: 'nearest'
                        });
                    }
                } else {
                    alertaRiesgo.classList.add('hidden');
                    alertaMostrada = false;
                }
            }

            // Validación antes de enviar
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                if (embrionesSeleccionados.length === 0) {
                    mostrarModal(
                        'Error de Validación',
                        'Debe seleccionar al menos un embrión para transferir.',
                        'error'
                    );
                    return false;
                }

                if (embrionesSeleccionados.length > MAX_EMBRIONES) {
                    mostrarModal(
                        'Error de Validación',
                        `No puede seleccionar más de ${MAX_EMBRIONES} embriones.`,
                        'error'
                    );
                    return false;
                }

                // Confirmación final
                const cantidad = embrionesSeleccionados.length;
                const titulo = cantidad === 1 ? 'Confirmar Transferencia' :
                    'Confirmar Transferencia Múltiple';
                const mensaje = cantidad === 1 ?
                    '¿Está seguro de que desea confirmar la transferencia de 1 embrión?' :
                    `¿Está seguro de que desea confirmar la transferencia de ${cantidad} embriones?<br><br><strong class="text-yellow-700">Recuerde que esto aumenta el riesgo de embarazo múltiple.</strong>`;

                mostrarModalConfirmacion(titulo, mensaje, function() {
                    form.submit();
                });
            });
        });

        function mostrarModal(titulo, mensaje, tipo = 'info') {
            const iconos = {
                error: '<i class="fas fa-exclamation-circle text-red-500 text-4xl mb-4"></i>',
                warning: '<i class="fas fa-exclamation-triangle text-yellow-500 text-4xl mb-4"></i>',
                info: '<i class="fas fa-info-circle text-blue-500 text-4xl mb-4"></i>',
                success: '<i class="fas fa-check-circle text-green-500 text-4xl mb-4"></i>'
            };

            const colores = {
                error: 'border-red-500',
                warning: 'border-yellow-500',
                info: 'border-blue-500',
                success: 'border-green-500'
            };

            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 z-50 flex items-center justify-center modal-overlay';
            modal.innerHTML = `
                <div class="bg-white rounded-lg shadow-2xl max-w-md w-full mx-4 border-t-4 ${colores[tipo]} modal-content">
                    <div class="p-6 text-center">
                        ${iconos[tipo]}
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">${titulo}</h3>
                        <p class="text-gray-600 mb-6">${mensaje}</p>
                        <button onclick="this.closest('.modal-overlay').remove()" 
                                class="btn-primary px-6 py-2">
                            Entendido
                        </button>
                    </div>
                </div>
            `;
            document.body.appendChild(modal);

            // Cerrar al hacer click fuera
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    modal.remove();
                }
            });
        }

        function mostrarModalConfirmacion(titulo, mensaje, callback) {
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 z-50 flex items-center justify-center modal-overlay';
            modal.innerHTML = `
                <div class="bg-white rounded-lg shadow-2xl max-w-md w-full mx-4 border-t-4 border-blue-500 modal-content">
                    <div class="p-6">
                        <div class="text-center mb-4">
                            <i class="fas fa-question-circle text-blue-500 text-4xl mb-4"></i>
                            <h3 class="text-xl font-semibold text-gray-900 mb-3">${titulo}</h3>
                            <p class="text-gray-600">${mensaje}</p>
                        </div>
                        <div class="flex gap-3 justify-end mt-6">
                            <button onclick="this.closest('.modal-overlay').remove()" 
                                    class="btn-secondary px-6 py-2">
                                Cancelar
                            </button>
                            <button id="btnConfirmarModal" 
                                    class="btn-primary px-6 py-2">
                                Confirmar
                            </button>
                        </div>
                    </div>
                </div>
            `;
            document.body.appendChild(modal);

            document.getElementById('btnConfirmarModal').addEventListener('click', function() {
                modal.remove();
                callback();
            });

            // Cerrar al hacer click fuera
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    modal.remove();
                }
            });

            // Cerrar con ESC
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    modal.remove();
                }
            }, {
                once: true
            });
        }
    </script>
@endsection
