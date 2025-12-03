@extends('layouts.layoutInterno')

@section('title', 'Post Transferencia')

@section('page-header')
<div class="page-header">
    <div>
        <h1 class="page-title">Post Transferencia</h1>
        <p class="page-subtitle">Resultados posteriores a la transferencia embrionaria</p>
    </div>
    @if(session('rol') == 3)
    <div>
        <a href="{{ route('operador.tratamiento.detalle', $tratamiento->id) }}" class="btn-secondary">
            <i class="fas fa-arrow-left mr-2"></i> Volver al tratamiento
        </a>
    </div>
    @elseif(session('rol') == 5)
    <div>
        <a href="{{ route('jefe.tratamiento.detalle', $tratamiento->id) }}" class="btn-secondary">
            <i class="fas fa-arrow-left mr-2"></i> Volver al tratamiento
        </a>
    </div>
    @else
    <div>
        <a href="{{ route('medico.tratamiento.detalle', $tratamiento->id) }}" class="btn-secondary">
            <i class="fas fa-arrow-left mr-2"></i> Volver al tratamiento
        </a>
    </div>
    @endif
</div>
@endsection

@section('content')

<div class="max-w-xl mx-auto bg-white shadow-md rounded-xl p-6 space-y-6">

    {{-- MENSAJE DE GUARDADO --}}
    @if(session('success'))
        <div class="p-3 bg-green-100 text-green-700 rounded-md">
            {{ session('success') }}
        </div>
    @endif

    {{-- RESUMEN DE DATOS CARGADOS --}}
    @if(isset($post) && (
            $post->beta !== null ||
            $post->saco !== null ||
            $post->embarazo !== null ||
            $post->vivo !== null ||
            $post->fecha_nacimiento !== null ||
            $post->causa_no_nacido !== null
        ))

    <div class="bg-gray-100 border border-gray-300 rounded-lg p-4 mb-6">
        <h2 class="text-lg font-bold mb-3">Datos cargados de la post-transferencia</h2>

        <ul class="space-y-2">

            @if($post->beta !== null)
                <li>
                    <span class="font-semibold">Beta:</span>
                    <span class="px-2 py-1 rounded text-white 
                        {{ $post->beta ? 'bg-green-600' : 'bg-red-600' }}">
                        {{ $post->beta ? 'Positivo' : 'Negativo' }}
                    </span>
                </li>
            @endif

            @if($post->saco !== null)
                <li>
                    <span class="font-semibold">Saco gestacional:</span>
                    <span class="px-2 py-1 rounded text-white 
                        {{ $post->saco ? 'bg-green-600' : 'bg-red-600' }}">
                        {{ $post->saco ? 'Sí' : 'No' }}
                    </span>
                </li>
            @endif

            @if($post->embarazo !== null)
                <li>
                    <span class="font-semibold">Embarazo clínico:</span>
                    <span class="px-2 py-1 rounded text-white 
                        {{ $post->embarazo ? 'bg-green-600' : 'bg-red-600' }}">
                        {{ $post->embarazo ? 'Sí' : 'No' }}
                    </span>
                </li>
            @endif

            @if($post->vivo !== null)
                <li>
                    <span class="font-semibold">Vivo:</span>
                    <span class="px-2 py-1 rounded text-white 
                        {{ $post->vivo ? 'bg-green-600' : 'bg-red-600' }}">
                        {{ $post->vivo ? 'Sí' : 'No' }}
                    </span>
                </li>
            @endif

            @if($post->vivo === 1 && $post->fecha_nacimiento)
                <li>
                    <span class="font-semibold">Fecha de nacimiento:</span>
                    <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded">
                        {{ \Carbon\Carbon::parse($post->fecha_nacimiento)->format('d/m/Y') }}
                    </span>
                </li>
            @endif

            @if($post->vivo === 0 && $post->causa_no_nacido)
                <li>
                    <span class="font-semibold">Causa (no nacido):</span>
                    <span class="px-2 py-1 bg-gray-200 text-gray-700 rounded">
                        {{ $post->causa_no_nacido }}
                    </span>
                </li>
            @endif

        </ul>
    </div>

    @endif

    <form 
    action="{{ session('rol') == 5
        ? route('jefe.tratamiento.guardar-post', $tratamiento->id)
        : route('tratamiento.guardar-post', $tratamiento->id) }}"
    method="POST"
>

          
        @csrf

        {{-- BETA --}}
        @if(!isset($post) || $post->beta === null)
        <div>
            <label class="font-semibold">Beta</label>
            <select name="beta" class="w-full mt-1 border rounded-md p-2">
                <option value="">Seleccione…</option>
                <option value="1">Positivo</option>
                <option value="0">Negativo</option>
            </select>
        </div>
        @endif

        {{-- SACO --}}
        @if(isset($post) && $post->beta !== null && $post->saco === null)
        <div>
            <label class="font-semibold">Saco gestacional</label>
            <select name="saco" class="w-full mt-1 border rounded-md p-2">
                <option value="">Seleccione…</option>
                <option value="1">Sí</option>
                <option value="0">No</option>
            </select>
        </div>
        @endif

        {{-- EMBARAZO --}}
        @if(isset($post) && $post->saco !== null && $post->embarazo === null)
        <div>
            <label class="font-semibold">Embarazo clínico</label>
            <select name="embarazo" class="w-full mt-1 border rounded-md p-2">
                <option value="">Seleccione…</option>
                <option value="1">Sí</option>
                <option value="0">No</option>
            </select>
        </div>
        @endif

        {{-- VIVO --}}
        @if(isset($post) && $post->embarazo !== null && $post->vivo === null)
        <div>
            <label class="font-semibold">Vivo</label>
            <select name="vivo" id="vivoSelect" class="w-full mt-1 border rounded-md p-2">
                <option value="">Seleccione…</option>
                <option value="1">Sí</option>
                <option value="0">No</option>
            </select>
        </div>
        @endif

        {{-- FECHA si vivo = 1 --}}
        @if(isset($post) && $post->vivo === 1 && $post->fecha_nacimiento === null)
        <div id="campoFecha">
            <label class="font-semibold">Fecha de nacimiento</label>
            <input type="date" name="fecha_nacimiento"
                class="w-full mt-1 border rounded-md p-2">
        </div>
        @endif

        {{-- CAUSA si vivo = 0 --}}
        @if(isset($post) && $post->vivo === 0 && $post->causa_no_nacido === null)
        <div id="campoCausa">
            <label class="font-semibold">Causa de que no haya nacido</label>
            <input type="text" name="causa_no_nacido"
                class="w-full mt-1 border rounded-md p-2">
        </div>
        @endif

    @php
        $completo = false;

        if (isset($post)) {
            // Si todos los campos simples están cargados
            $basicos = $post->beta !== null &&
                    $post->saco !== null &&
                    $post->embarazo !== null &&
                    $post->vivo !== null;

            // Si está vivo -> debe tener fecha
            $condVivo = ($post->vivo == 1 && $post->fecha_nacimiento !== null);

            // Si NO está vivo -> debe tener causa
            $condNoVivo = ($post->vivo == 0 && $post->causa_no_nacido !== null);

            // Evaluamos
            $completo = $basicos && ($condVivo || $condNoVivo);
        }
    @endphp
        {{-- BOTÓN --}}
        @if(!$completo)
            @if(session('rol') == 3)
            <button class="btn-primary w-full flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed " disabled>
                <i class="fas fa-save"></i> Solo puede visualizar los datos
            </button>
            @else
                <button class="btn-primary w-full flex items-center justify-center gap-2">
                <i class="fas fa-save"></i> Guardar Datos
            </button>
            @endif
        @endif

    </form>

</div>

<script>
    const vivoSelect = document.getElementById('vivoSelect');

    if (vivoSelect) {
        vivoSelect.addEventListener('change', function () {
            const val = this.value;
            
            const campoFecha = document.getElementById('campoFecha');
            const campoCausa = document.getElementById('campoCausa');

            if (campoFecha) campoFecha.style.display = val === "1" ? "block" : "none";
            if (campoCausa) campoCausa.style.display = val === "0" ? "block" : "none";
        });
    }
</script>

@endsection
