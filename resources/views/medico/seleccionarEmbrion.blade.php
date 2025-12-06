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
    $prefix = session('rol') == 3 
        ? 'operador' 
        : (session('rol') == 5 
            ? 'jefe' 
            : 'medico');
@endphp
        <div>
           <a 
    href="{{ url("$prefix/paciente/$tratamiento->id/tratamiento") }}"
    class="btn-secondary"
>
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
            action="{{ session('rol') == 5 ? route('jefe.transferencia.guardar') : route('medico.transferencia.guardar') }}">

            @csrf
            <input type="hidden" name="paciente_id" value="{{ $paciente->id }}">

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

                        <input type="radio" name="embrion_id" value="{{ $embrion->id }}" class="peer hidden"
                            @if ($disabled) disabled @endif required>

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
                                    @if ($embrion->realizo_pgt == true)
                                        @if ($embrion->pgt_positivo)
                                            <span class="text-red-600 font-medium">Positivo</span>
                                        @else
                                            <span class="text-green-600 font-medium">Negativo</span>
                                        @endif
                                        <span class="text-green-600 font-medium">
                                            ({{ ucfirst($embrion->resultado_pgt) }})
                                        </span>
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
                <button type="submit" class="btn-primary">
                    <i class="fas fa-check mr-2"></i> Confirmar transferencia
                </button>
            </div>

        </form>

    @endif


@endsection
