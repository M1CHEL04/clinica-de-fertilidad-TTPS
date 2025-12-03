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

{{-- MODAL --}}
<div id="modalConfirm" 
     class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">

        <h2 class="text-lg font-bold mb-4">Confirmar carga</h2>
        <p id="modalMessage" class="mb-6 text-gray-700">¿Confirmás guardar este dato?</p>

        <div class="flex justify-end gap-3">
            <button id="btnCancelar" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancelar</button>
            <button id="btnConfirmar" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Confirmar</button>
        </div>

    </div>
</div>

<div class="max-w-xl mx-auto bg-white shadow-md rounded-xl p-6 space-y-6">

    @if(session('success'))
        <div class="p-3 bg-green-100 text-green-700 rounded-md">
            {{ session('success') }}
        </div>
    @endif

    {{-- RESUMEN --}}
    @if(isset($post) && (
        $post->beta !== null ||
        $post->saco !== null ||
        $post->embarazo !== null ||
        $post->vivo !== null ||
        $post->fecha_nacimiento !== null ||
        $post->causa_no_nacido !== null
    ))
    <div class="bg-gray-100 border border-gray-300 rounded-lg p-4 mb-6">
        <h2 class="text-lg font-bold mb-3">Datos cargados</h2>
        <ul class="space-y-2">

            @if($post->beta !== null)
                <li><strong>Beta:</strong>
                    <span class="px-2 py-1 rounded text-white {{ $post->beta ? 'bg-green-600' : 'bg-red-600' }}">
                        {{ $post->beta ? 'Positivo' : 'Negativo' }}
                    </span>
                </li>
            @endif

            @if($post->beta == 1 && $post->saco !== null)
                <li><strong>Saco gestacional:</strong>
                    <span class="px-2 py-1 rounded text-white {{ $post->saco ? 'bg-green-600' : 'bg-red-600' }}">
                        {{ $post->saco ? 'Sí' : 'No' }}
                    </span>
                </li>
            @endif

            @if($post->saco == 1 && $post->embarazo !== null)
                <li><strong>Embarazo clínico:</strong>
                    <span class="px-2 py-1 rounded text-white {{ $post->embarazo ? 'bg-green-600' : 'bg-red-600' }}">
                        {{ $post->embarazo ? 'Sí' : 'No' }}
                    </span>
                </li>
            @endif

            @if($post->saco == 1 && $post->embarazo !== null && $post->vivo !== null)
                <li><strong>Vivo:</strong>
                    <span class="px-2 py-1 rounded text-white {{ $post->vivo ? 'bg-green-600' : 'bg-red-600' }}">
                        {{ $post->vivo ? 'Sí' : 'No' }}
                    </span>
                </li>
            @endif

            @if($post->vivo == 1 && $post->fecha_nacimiento)
                <li><strong>Fecha de nacimiento:</strong>
                    <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded">
                        {{ \Carbon\Carbon::parse($post->fecha_nacimiento)->format('d/m/Y') }}
                    </span>
                </li>
            @endif

            @if($post->vivo == 0 && $post->causa_no_nacido)
                <li><strong>Causa:</strong>
                    <span class="px-2 py-1 bg-gray-200 rounded">{{ $post->causa_no_nacido }}</span>
                </li>
            @endif
        </ul>
    </div>
    @endif

    {{-- FORM --}}
    <form id="formPost" action="{{ route('tratamiento.guardar-post', $tratamiento->id) }}" method="POST">
        @csrf


        {{-- 1) BETA --}}
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

        @php
            $flujoTerminado = false;
            if(isset($post)) {
                if($post->beta === 0) $flujoTerminado = true;
                if($post->saco === 0) $flujoTerminado = true;
                if($post->embarazo === 0) $flujoTerminado = true;
            }
        @endphp

        {{-- 2) SACO --}}
        @if(!$flujoTerminado && isset($post) && $post->beta == 1 && $post->saco === null)
            <div>
                <label class="font-semibold">Saco gestacional</label>
                <select name="saco" class="w-full mt-1 border rounded-md p-2">
                    <option value="">Seleccione…</option>
                    <option value="1">Sí</option>
                    <option value="0">No</option>
                </select>
            </div>
        @endif

        {{-- 3) EMBARAZO --}}
        @if(!$flujoTerminado && isset($post) && $post->saco == 1 && $post->embarazo === null)
            <div>
                <label class="font-semibold">Embarazo clínico</label>
                <select name="embarazo" class="w-full mt-1 border rounded-md p-2">
                    <option value="">Seleccione…</option>
                    <option value="1">Sí</option>
                    <option value="0">No</option>
                </select>
            </div>
        @endif

        {{-- 4) VIVO --}}
        @if(!$flujoTerminado && isset($post) && $post->embarazo == 1 && $post->vivo === null)
            <div>
                <label class="font-semibold">Vivo</label>
                <select name="vivo" id="vivoSelect" class="w-full mt-1 border rounded-md p-2">
                    <option value="">Seleccione…</option>
                    <option value="1">Sí</option>
                    <option value="0">No</option>
                </select>
            </div>
        @endif

        {{-- 5) FECHA NACIMIENTO --}}
        @if(!$flujoTerminado && isset($post) && $post->vivo == 1 && $post->fecha_nacimiento === null)
            <div id="campoFecha">
                <label class="font-semibold">Fecha de nacimiento</label>
                <input type="date" name="fecha_nacimiento" class="w-full mt-1 border rounded-md p-2">
            </div>
        @endif

        {{-- 6) CAUSA (solo si NO nació) --}}
        @if(!$flujoTerminado && isset($post) && $post->vivo !== null && $post->vivo == 0 && $post->causa_no_nacido === null)
            <div id="campoCausa">
                <label class="font-semibold">Causa de que no haya nacido</label>
                <input type="text" name="causa_no_nacido" class="w-full mt-1 border rounded-md p-2">
            </div>
        @endif  

        {{-- === BOTÓN GUARDAR === --}}
            @php
                $completo = false;
                if(isset($post)) {
                    if($post->beta === 0 || $post->saco === 0 || $post->embarazo === 0) {
                        $completo = true;
                    } else {
                        $basicos = $post->beta !== null &&
                                $post->saco !== null &&
                                $post->embarazo !== null &&
                                $post->vivo !== null;

                        $condVivo = ($post->vivo == 1 && $post->fecha_nacimiento !== null);
                        $condNoVivo = ($post->vivo == 0 && $post->causa_no_nacido !== null);

                        $completo = $basicos && ($condVivo || $condNoVivo);
                    }
                }
            @endphp

        @if(!$completo)
            <button class="btn-primary w-full flex items-center justify-center gap-2 mt-4">
                <i class="fas fa-save"></i> Guardar Datos
            </button>
        @endif

    </form>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {

    const vivoSelect = document.getElementById('vivoSelect');
    if (vivoSelect) {
        vivoSelect.addEventListener('change', function () {
            const campoFecha = document.getElementById('campoFecha');
            const campoCausa = document.getElementById('campoCausa');
            if (campoFecha) campoFecha.style.display = this.value === "1" ? "block" : "none";
            if (campoCausa) campoCausa.style.display = this.value === "0" ? "block" : "none";
        });
    }

    /** MODAL **/
    const form = document.getElementById('formPost');
    const modal = document.getElementById('modalConfirm');
    const modalMessage = document.getElementById('modalMessage');
    const btnConfirmar = document.getElementById('btnConfirmar');
    const btnCancelar = document.getElementById('btnCancelar');

    let allowSubmit = false;

    form.addEventListener('submit', function(event) {
        if (!allowSubmit) {
            event.preventDefault();

            let campo = null;
            let valorVisible = null;

            for (let el of form.elements) {
                if (!el.name || !el.value || el.type === "hidden") continue;

                if (el.tagName === 'SELECT') {
                    campo = el.name;
                    valorVisible = el.options[el.selectedIndex].text.trim();
                    break;
                }

                if (el.tagName === 'INPUT') {
                    campo = el.name;
                    valorVisible = el.value;
                    break;
                }
            }

            modalMessage.textContent = `¿Confirmás guardar el valor "${valorVisible}" en el campo "${campo}"?`;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
    });

    btnCancelar.addEventListener('click', () => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    });

    btnConfirmar.addEventListener('click', () => {
        allowSubmit = true;
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        form.submit();
    });

});
</script>

@endsection
