@extends('layouts.layoutInterno')
@section('title', 'Post Transferencia')

@section('page-header')
<div class="page-header">
    <div>
        <h1 class="page-title">Post Transferencia</h1>
        <p class="page-subtitle">Resultados posteriores a la transferencia embrionaria</p>
    </div>
    <div>
        <a href="{{ route('medico.tratamiento.detalle', $tratamiento->id) }}" class="btn-secondary">
            <i class="fas fa-arrow-left mr-2"></i> Volver al tratamiento
        </a>
    </div>
</div>
@endsection

@section('content')

<div class="max-w-xl mx-auto bg-white shadow-md rounded-xl p-6 space-y-6">

    <form action="{{ route('tratamiento.guardar-post', $tratamiento->id) }}" 
          method="POST" class="space-y-4">
        @csrf

        <div>
            <label class="font-semibold">Beta</label>
            <select name="beta" class="w-full mt-1 border rounded-md p-2">
                <option value="">Seleccione…</option>
                <option value="1" @selected($tratamiento->beta === 1)>Positivo</option>
                <option value="0" @selected($tratamiento->beta === 0)>Negativo</option>
            </select>
        </div>

        <div>
            <label class="font-semibold">Saco gestacional</label>
            <select name="saco" class="w-full mt-1 border rounded-md p-2">
                <option value="">Seleccione…</option>
                <option value="1" @selected($tratamiento->saco === 1)>Sí</option>
                <option value="0" @selected($tratamiento->saco === 0)>No</option>
            </select>
        </div>

        <div>
            <label class="font-semibold">Embarazo Clinico</label>
            <select name="embrion" class="w-full mt-1 border rounded-md p-2">
                <option value="">Seleccione…</option>
                <option value="1" @selected($tratamiento->embrion === 1)>Sí</option>
                <option value="0" @selected($tratamiento->embrion === 0)>No</option>
            </select>
        </div>

        <div>
            <label class="font-semibold">Vivo</label>
            <select name="vivo" class="w-full mt-1 border rounded-md p-2">
                <option value="">Seleccione…</option>
                <option value="1" @selected($tratamiento->vivo === 1)>Sí</option>
                <option value="0" @selected($tratamiento->vivo === 0)>No</option>
            </select>
        </div>

        <button class="btn-primary w-full flex items-center justify-center gap-2">
            <i class="fas fa-save"></i> Guardar Datos
        </button>

    </form>

</div>

@endsection
