@extends('layouts.layoutInterno')
@section('title', 'Detalle de Tratamiento - Fertilia')

@section('page-header')
<div class="page-header">
    <div>
        <h1 class="page-title">Detalle del Tratamiento</h1>
        <p class="page-subtitle">Información detallada del paciente y su tratamiento actual</p>
    </div>
    <div>
        <a href="{{ route('medico.home') }}" class="btn-secondary">
            <i class="fas fa-arrow-left mr-2"></i> Volver a Pacientes
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- 🧍‍♀️ Columna principal -->
    <div class="lg:col-span-2 space-y-6">

        <!-- Información del Paciente -->
        <div class="card p-6">
            <div class="flex items-center mb-6">
                <div class="w-16 h-16 bg-blue-600 rounded-full flex items-center justify-center mr-4">
                    <span class="text-white text-lg font-medium">
                        {{ strtoupper(substr($tratamiento->nombre, 0, 1)) }}{{ strtoupper(substr($tratamiento->apellido, 0, 1)) }}
                    </span>
                </div>
                <div>
                    <h2 class="text-2xl font-semibold text-gray-900">
                        {{ $tratamiento->nombre }} {{ $tratamiento->apellido }}
                    </h2>
                    <p class="text-gray-500">
                        DNI: {{ $tratamiento->dni }} · 
                        {{ \Carbon\Carbon::parse($tratamiento->fecha_nacimiento)->age }} años · 
                        {{ $tratamiento->mail }}
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <p class="font-medium text-gray-600">Estado actual:</p>
                    <span class="badge 
                        {{ strtolower($tratamiento->estado_tratamiento) === 'activo' ? 'badge-success' : 
                           (strtolower($tratamiento->estado_tratamiento) === 'completado' ? 'badge-info' : 'badge-warning') }}">
                        {{ $tratamiento->estado_tratamiento }}
                    </span>
                </div>
                <div>
                    <p class="font-medium text-gray-600">Fecha de inicio:</p>
                    <p class="text-gray-800">
                        {{ \Carbon\Carbon::parse($tratamiento->fecha_inicio)->format('d/m/Y') }}
                    </p>
                </div>
                
            </div>
        </div>

        <!-- 📋 Detalle del Tratamiento -->
        <div class="card p-6">
            <h3 class="text-lg font-semibold mb-4 text-gray-900 flex items-center">
                <i class="fas fa-microscope text-purple-600 mr-2"></i> Información del Tratamiento
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <div>
                    <p class="font-medium text-gray-600">Tratamiento:</p>
                    <p class="text-gray-800">{{$tratamiento->objetivo}}</p>
                </div>
                <div>
                    <p class="font-medium text-gray-600">Etapa:</p>
                    <p class="text-gray-800">{{$tratamiento->etapa}}</p>
                </div>
                
            </div>
        </div>

        

        <!-- ➡️ Avanzar etapa -->
    

        <!-- 🔮 Próximas acciones -->
       
        <div class="card p-6">
             
            <h3 class="text-lg font-semibold mb-4 text-gray-900 flex items-center">
                <i class="fas fa-calendar-plus text-blue-600 mr-2"></i> Próximas Acciones
            </h3>
 @if (session('rol') == 2)
    <div class="flex space-x-2">
    @if (strtolower($tratamiento->etapa) !== 'finalizado')
        <form method="POST" action="{{ route('tratamiento.avanzar-etapa', $tratamiento->id) }}">
            @csrf
            <button class="btn-primary">
                <i class="fas fa-arrow-right mr-1"></i> Avanzar etapa
            </button>
        </form>
    @endif

    @if (strtolower($tratamiento->etapa) !== 'primera consulta')
        <form method="POST" action="{{ route('tratamiento.retroceder-etapa', $tratamiento->id) }}">
            @csrf
            <button class="btn-secondary">
                <i class="fas fa-arrow-left mr-1"></i> Retroceder etapa
            </button>
        </form>
    @endif
</div>

    
    

            <div class="mt-6 flex justify-end">
                <button class="btn-primary">
                    <i class="fas fa-plus mr-2"></i> Agendar nueva consulta
                </button>
            </div>
        </div>
        @endif
    </div>
    

    <!-- 🧰 Panel lateral -->
    @if ($tratamiento->estado_tratamiento == 'Activo') 
    <div class="space-y-4">
        <div class="card p-6">
            <h3 class="text-lg font-semibold mb-4 text-gray-900 flex items-center">
                <i class="fas fa-tasks text-indigo-600 mr-2"></i> Acciones del Tratamiento
            </h3>
            
            <div class="space-y-3">

    {{-- Normalizar el nombre de etapa para comparación --}}
    @php
        $etapa = trim(strtolower($tratamiento->etapa));
    @endphp

    {{-- PRIMERA CONSULTA --}}
    @php
        $etapa1 = in_array($etapa, [
            'primera consulta',
            'segunda consulta',
            'monitoreos',
            'control de embarazo',
            'puncion',
            'finalizado',
            'transferencia'
        ]);
    @endphp

    {{-- SEGUNDA CONSULTA --}}
    @php
        $etapa2 = in_array($etapa, [
            'segunda consulta',
            'monitoreos',
            'control de embarazo',
            'puncion',
            'finalizado',
            'transferencia'
        ]);
    @endphp

    @php
        $etapa3 = in_array($etapa, [
            'monitoreos',
            'control de embarazo',
            'puncion',
            'finalizado',
            'transferencia'
        ]);
    @endphp

    {{-- CONTROL DE EMBARAZO --}}
    @php
        $etapa4 = in_array($etapa, [
            'control de embarazo',
            'finalizado'
        ]);
    @endphp


    {{-- 6️⃣ Cargar objetivo (desde etapa 1) --}}
    <button class="btn-primary w-full flex items-center justify-center gap-2 {{ !$etapa1 ? 'opacity-50 cursor-not-allowed' : '' }}"
            {{ !$etapa1 ? 'disabled' : '' }}>
        <i class="fas fa-bullseye"></i> Cargar objetivo
    </button>

    {{-- 1️⃣ Recetar estudios (desde etapa 1) --}}
    <button class="btn-primary w-full flex items-center justify-center gap-2 {{ !$etapa1 ? 'opacity-50 cursor-not-allowed' : '' }}"
            {{ !$etapa1 ? 'disabled' : '' }}>
        <i class="fas fa-vials"></i> Recetar estudios
    </button>

   

    {{-- 3️⃣ Cargar antecedentes (desde etapa 1) --}}
    <button class="btn-primary w-full flex items-center justify-center gap-2 {{ !$etapa1 ? 'opacity-50 cursor-not-allowed' : '' }}"
            {{ !$etapa1 ? 'disabled' : '' }}>
        <i class="fas fa-user-md"></i> Cargar antecedentes
    </button>
    
     @if ($etapa2)
        <a href="{{ route('tratamiento.cargar-estudios', $tratamiento->id) }}"
           class="btn-primary w-full flex items-center justify-center gap-2">
            <i class="fas fa-file-upload"></i> Sección Estudios
        </a>
    @else
        <a class="btn-primary w-full flex items-center justify-center gap-2 opacity-50 cursor-not-allowed pointer-events-none">
            <i class="fas fa-file-upload"></i> Sección Estudios
        </a>
    @endif
    

   
    {{--  Protocolo de Estimulación (desde etapa 2) --}}
    @if ($etapa2)
        <a href="{{ route('tratamiento.protocolo', $tratamiento->id) }}"
           class="btn-primary w-full flex items-center justify-center gap-2">
            <i class="fas fa-dna"></i> Protocolo de Estimulación
        </a>
    @else
        <a class="btn-primary w-full flex items-center justify-center gap-2 opacity-50 cursor-not-allowed pointer-events-none">
            <i class="fas fa-dna"></i> Protocolo de Estimulación
        </a>
    @endif

     {{-- 4️⃣ Monitoreos (desde etapa 3) --}}
    @if ($etapa3)
        <a href="{{ route('monitoreos', $tratamiento->id) }}"
           class="btn-primary w-full flex items-center justify-center gap-2">
            <i class="fas fa-heartbeat"></i> Sección de Monitoreos
        </a>
    @else
        <a class="btn-primary w-full flex items-center justify-center gap-2 opacity-50 cursor-not-allowed pointer-events-none">
            <i class="fas fa-heartbeat"></i> Sección de Monitoreos
        </a>
    @endif

   
    @if ($etapa4)
    <button class="btn-primary w-full flex items-center justify-center gap-2">
        <i class="fas fa-seedling"></i> Cargar post-transferencia
    </button>
    @else
        <button class="btn-primary w-full flex items-center justify-center gap-2 opacity-50 cursor-not-allowed pointer-events-none">
        <i class="fas fa-seedling"></i> Cargar post-transferencia
    </button>
    @endif

    

</div>


        <div class="card p-4 bg-blue-50 border border-blue-200">
            <p class="text-sm text-gray-700">
                💡 <strong>Consejo:</strong> Las acciones se habilitan automáticamente según la etapa actual del tratamiento.
            </p>
        </div>
    </div>
    @endif
</div>
    
@endsection
