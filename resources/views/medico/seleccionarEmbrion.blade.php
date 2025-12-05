@extends('layouts.layoutInterno')
@section('title', 'Seleccionar Embrión - Fertilia')

@section('content')

<div class="max-w-4xl mx-auto">
    
    <!-- Encabezado -->
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-800 flex items-center">
            <i class="fas fa-seedling text-green-600 mr-2"></i>
            Seleccionar embrión para transferencia
        </h1>
        <p class="text-gray-600 mt-1">
            Paciente: <strong>{{ $paciente->nombre }} {{ $paciente->apellido }}</strong>  
            (DNI: {{ $paciente->dni }})
        </p>
        
    </div>

    <a href="{{ url()->previous() }}" class="text-blue-600 hover:underline">Volver</a>

    <!-- Si no hay embriones -->
    @if($embriones->isEmpty())
        <div class="bg-gray-50 text-center py-10 rounded-lg border border-gray-200">
            <i class="fas fa-egg-crack text-4xl text-gray-400 mb-3"></i>
            <p class="text-gray-600 mb-2">Este paciente no tiene embriones disponibles.</p>
            <a href="{{ url()->previous() }}" class="text-blue-600 hover:underline">Volver</a>
        </div>
    @else

    <!-- Lista de embriones -->
    <form method="POST" 
      action="{{ session('rol') == 5 
                    ? route('jefe.transferencia.guardar') 
                    : route('medico.transferencia.guardar') }}">

        @csrf
        <input type="hidden" name="paciente_id" value="{{ $paciente->id }}">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($embriones as $embrion)

                @php
                    
                    $estaCriopreservado = $embrion->criopreservado == true;
                    $tieneDescarte = !is_null($embrion->motivo_descarte);
                    $estaUtilizado = $embrion->utilizado;

                    // → Embrión seleccionable solo si NO está criopreservado, NO descartado y NO utilizado
                    $disabled = $estaCriopreservado || $tieneDescarte || $estaUtilizado;
                @endphp

                <label class="block cursor-pointer">

                    <input 
                        type="radio" 
                        name="embrion_id" 
                        value="{{ $embrion->id }}" 
                        class="peer hidden"
                        @if($disabled) disabled @endif
                        required
                    >

                    <div class="
                        border rounded-lg p-5 bg-white shadow-sm transition
                        @if($disabled)
                            opacity-50 cursor-not-allowed border-gray-300
                        @else
                            peer-checked:border-blue-500 peer-checked:ring-2 ring-blue-300
                        @endif
                    ">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2 flex items-center justify-between">
                            <span>
                                <i class="fas fa-circle text-blue-400 mr-1"></i>
                                {{ $embrion->identificador }}
                            </span>

                            <!-- Etiquetas de estado -->
                            @if($estaUtilizado)
                                <span class="bg-red-100 text-red-700 text-xs px-2 py-1 rounded-full">
                                    Utilizado
                                </span>
                            @elseif($tieneDescarte)
                                <span class="bg-red-100 text-red-700 text-xs px-2 py-1 rounded-full">
                                    Descartado
                                </span>
                            @elseif($estaCriopreservado)
                                <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">
                                    Criopreservado
                                </span>
                            @endif
                        </h3>

                        <p class="text-sm text-gray-700 mb-1">
                            <strong>Calidad:</strong> {{ $embrion->calidad_morfologica ?? 'Sin evaluar' }}
                        </p>

                        <p class="text-sm text-gray-700 mb-1">
                            <strong>PGT:</strong> 
                            @if($embrion->realizo_pgt === 'si')
                                <span class="text-green-600 font-medium">
                                    Sí ({{ ucfirst($embrion->resultado_pgt) }})
                                </span>
                            @else
                                <span class="text-gray-500">No</span>
                            @endif
                        </p>

                        @if ($embrion->semen_dni)
                        <p class="text-sm text-gray-700 mb-1">
                            <strong>Origen gametos:</strong> {{ ucfirst($embrion->semen_dni) }}
                        </p>
                        @else
                        <p class="text-sm text-gray-700 mb-1">
                            <strong>Origen gametos:</strong> No especificado
                        </p>
                        @endif

                        @if($embrion->estado === 'disponible')
                            <span class="inline-block mt-2 bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">
                                Disponible
                            </span>
                        @endif
                    </div>
                </label>
            @endforeach
        </div>

        <button type="submit" class="btn-primary mt-6">
            <i class="fas fa-check mr-2"></i> Confirmar Transferencia
        </button>

    </form>

    @endif

</div>

@endsection
