@extends('layouts.layoutInterno')
@section('title', 'Detalle de Tratamiento - Fertilia')

@section('page-header')
<div class="page-header">
    <div>
        <h1 class="page-title">Detalle del Tratamiento</h1>
        <p class="page-subtitle">Información detallada del paciente y su tratamiento actual</p>
    </div>
    @if (session('rol') == 3 )
    <div>
        <a href="/operador/home" class="btn-secondary">
            <i class="fas fa-arrow-left mr-2"></i> Volver a Pacientes
        </a>
    </div>
    @else
    <div>
        <a href="/medico/home" class="btn-secondary">
            <i class="fas fa-arrow-left mr-2"></i> Volver a Pacientes
        </a>
    </div>
    @endif
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
 @if (session('rol') == 2 || session('rol') == 5)
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

    
    
@php
    $deshabilitado = $tratamiento->etapa !== "Monitoreos";
    $habilitado = $tratamiento->etapa == "Transferencia";
@endphp

<div style="display:flex; gap:10px;">
    <button 
        class="btn-primary {{ $deshabilitado ? 'btn-disabled' : '' }}"
        @if($deshabilitado) disabled @else onclick="abrirModal()" @endif
    >
        <i class="fas fa-plus mr-2"></i> Agendar nueva consulta
    </button>

    <form method="POST" action="{{ route('tratamiento.notificar-transferencia', $tratamiento->id) }}">
        @csrf
        <button 
            type="submit"
            class="btn btn-primary {{ $habilitado ? '' : 'btn-disabled' }}"
            {{ $habilitado ? '' : 'disabled' }}
        >
            <i class="fas fa-plus mr-2"></i>
            Notificar etapa de transferencia al paciente
        </button>
    </form>
</div>



<div id="modal-agendar" class="modal-overlay" style="display:none;">
    <div class="modal-content">
        <h2 class="modal-title">Agendar consulta</h2>

        <form method="POST" action="{{ route('tratamiento.agendar-consulta', $tratamiento->id) }}">
            @csrf

            <label>Fecha Inicio Tentativa</label>
            <input type="date" name="fecha_inicio" required>

            <label>Fecha Fin Tentativa</label>
            <input type="date" name="fecha_fin" required>

            <div class="modal-actions">
                <button type="button" class="btn-secondary" onclick="cerrarModal()">Cancelar</button>
                <button type="submit" class="btn-primary">Guardar</button>
            </div>
        </form>
    </div>
</div>



        </div>
        
        @endif
        @if (session('rol') == 3 )
    <div class="flex space-x-2">
    @if (strtolower($tratamiento->etapa) !== 'finalizado')
        <form method="POST" action="{{ route('tratamiento.avanzar-etapa', $tratamiento->id) }}">
            @csrf
            <button
                
                class="btn-primary disabled:opacity-50 disabled:cursor-not-allowed"
                disabled >
                <i class="fas fa-arrow-right mr-1"></i> Avanzar etapa
            </button>
        </form>
    @endif

    @if (strtolower($tratamiento->etapa) !== 'primera consulta')
        <form method="POST" action="{{ route('tratamiento.retroceder-etapa', $tratamiento->id) }}">
            @csrf
            <button class="btn-secondary disabled:opacity-50 disabled:cursor-not-allowed"
            disabled>
                <i class="fas fa-arrow-left mr-1"></i> Retroceder etapa
            </button>
        </form>
    @endif
</div>

    
    
@php
    $deshabilitado = $tratamiento->etapa !== "Monitoreos";
@endphp

<button 
    class="btn-primary disabled:opacity-50 disabled:cursor-not-allowed"
    disabled
>
    <i class="fas fa-plus mr-2" ></i> Agendar nueva consulta
</button>

<div id="modal-agendar" class="modal-overlay" style="display:none;">
    <div class="modal-content">
        <h2 class="modal-title">Agendar consulta</h2>

        <form method="POST" action="{{ route('tratamiento.agendar-consulta', $tratamiento->id) }}">
            @csrf

            <label>Fecha Inicio Tentativa</label>
            <input type="date" name="fecha_inicio" required>

            <label>Fecha Fin Tentativa</label>
            <input type="date" name="fecha_fin" required>

            <div class="modal-actions">
                <button type="button" class="btn-secondary" onclick="cerrarModal()">Cancelar</button>
                <button type="submit" class="btn-primary">Guardar</button>
            </div>
        </form>
    </div>
</div>



        </div>
        
        @endif
    </div>
    
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
    <!-- 🧰 Panel lateral -->
    @if ($tratamiento->estado_tratamiento == 'Activo') 
    <div class="space-y-4">
        <div class="card p-6">
            <h3 class="text-lg font-semibold mb-4 text-gray-900 flex items-center">
                <i class="fas fa-tasks text-indigo-600 mr-2"></i> Acciones del Tratamiento
            </h3>
            
    
    @if (session('rol') == 3)
        {{-- 6️⃣ Cargar objetivo (desde etapa 1) --}}

    <div class="space-y-3">    
    
     <a href="{{ route('operador.verConsulta', $tratamiento->paciente_id) }}" class="btn-primary w-full flex items-center justify-center gap-2"
            {{ !$etapa1 ? 'disabled' : '' }}>
        <i class="fas fa-bullseye"></i> Seccion de primera consulta
    </a>

    {{-- 1️⃣ Recetar estudios (desde etapa 1) --}}
    @if ($etapa1)
    <a href="{{ route('operador.estudios.index', $tratamiento->paciente_id) }}"
   class="btn-primary w-full flex items-center justify-center gap-2">
    <i class="fas fa-vials"></i> Recetar estudios
</a>

@else
    <a class="btn-primary w-full flex items-center justify-center gap-2 opacity-50 cursor-not-allowed pointer-events-none">
        <i class="fas fa-vials"></i> Recetar estudios
    </a>
@endif


   
     @if ($etapa2)
        <a href="{{ route('operador.tratamiento.cargar-estudios', $tratamiento->id) }}"
           class="btn-primary w-full flex items-center justify-center gap-2">
            <i class="fas fa-file-upload"></i> Sección Estudios
        </a>
    @else
        <a class="btn-primary w-full flex items-center justify-center gap-2 opacity-50 cursor-not-allowed pointer-events-none">
            <i class="fas fa-file-upload"></i> Sección Estudios
        </a>
    @endif
    

   
    {{--  Protocolo de Estimulación (desde etapa 2) --}}
    
        <a class="btn-primary w-full flex items-center justify-center gap-2 opacity-50 cursor-not-allowed pointer-events-none">
            <i class="fas fa-dna"></i> Protocolo de Estimulación
        </a>
    

     {{-- 4️⃣ Monitoreos (desde etapa 3) --}}
    @if ($etapa3)
        <a href="{{ route('operador.monitoreos', $tratamiento->id) }}"
           class="btn-primary w-full flex items-center justify-center gap-2">
            <i class="fas fa-heartbeat"></i> Sección de Monitoreos
        </a>
    @else
        <a class="btn-primary w-full flex items-center justify-center gap-2 opacity-50 cursor-not-allowed pointer-events-none">
            <i class="fas fa-heartbeat"></i> Sección de Monitoreos
        </a>
    @endif

   
    @if ($etapa4)
        <a href="{{ route('operador.tratamiento.post', $tratamiento->id) }}"
        class="btn-primary w-full flex items-center justify-center gap-2">
            <i class="fas fa-leaf"></i> Post-transferencia
        </a>
    @else
        <button class="btn-primary w-full flex items-center justify-center gap-2 opacity-50 cursor-not-allowed pointer-events-none">
        <i class="fas fa-seedling"></i> Post-transferencia
        </button>
    </div>    
    @endif
    @else
    
    <div class="space-y-3">

   


    {{-- 6️⃣ Cargar objetivo (desde etapa 1) --}}
    
     <a href="{{ route('medico.verConsulta', $tratamiento->paciente_id) }}" class="btn-primary w-full flex items-center justify-center gap-2"
            {{ !$etapa1 ? 'disabled' : '' }}>
        <i class="fas fa-bullseye"></i> Seccion de primera consulta
    </a>

    {{-- 1️⃣ Recetar estudios (desde etapa 1) --}}
    @if ($etapa1)
    <a href="{{ route('estudios.index', $tratamiento->paciente_id) }}"
   class="btn-primary w-full flex items-center justify-center gap-2">
    <i class="fas fa-vials"></i> Recetar estudios
</a>

@else
    <a class="btn-primary w-full flex items-center justify-center gap-2 opacity-50 cursor-not-allowed pointer-events-none">
        <i class="fas fa-vials"></i> Recetar estudios
    </a>
@endif


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
            <i class="fas fa-dna"></i> Protocolo De Estimulacion
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
        <a href="{{ route('tratamiento.post', $tratamiento->id) }}"
        class="btn-primary w-full flex items-center justify-center gap-2">
            <i class="fas fa-leaf"></i> Post-transferencia
        </a>
    @else
        <button class="btn-primary w-full flex items-center justify-center gap-2 opacity-50 cursor-not-allowed pointer-events-none">
        <i class="fas fa-seedling"></i> Post-transferencia
        </button>
    @endif

    

</div>
@endif


        <div class="card p-4 bg-blue-50 border border-blue-200">
            <p class="text-sm text-gray-700">
                💡 <strong>Consejo:</strong> Las acciones se habilitan automáticamente según la etapa actual del tratamiento.
            </p>
        </div>
    </div>
    @endif
</div>
    
@endsection

<script>
    function abrirModal() {
        document.getElementById("modal-agendar").style.display = "flex";
    }

    function cerrarModal() {
        document.getElementById("modal-agendar").style.display = "none";
    }
</script>


<style>
    .btn-disabled {
    opacity: 0.5;           /* más transparente */
    cursor: not-allowed;    /* cursor prohibido */
    background-color: #999; /* color más apagado */
    color: #fff;            /* asegura legibilidad */
}


.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.6);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 9999;
}

.modal-content {
    background: white;
    padding: 25px;
    border-radius: 8px;
    width: 400px;
}

.modal-title {
    font-size: 20px;
    margin-bottom: 15px;
    font-weight: bold;
}

.modal-content label {
    display: block;
    margin-top: 10px;
    font-weight: 600;
}

.modal-content input[type="date"] {
    width: 100%;
    padding: 8px;
    margin-top: 5px;
}

.modal-actions {
    display: flex;
    justify-content: flex-end;
    margin-top: 20px;
    gap: 10px;
}


</style>