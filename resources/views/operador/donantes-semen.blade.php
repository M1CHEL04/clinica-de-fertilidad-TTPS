@extends('layouts.layoutInterno')
@section('title', 'Registrar Donante de Semen')

@section('page-header')
<div class="page-header">
    <div>
        <h1 class="page-title">Registrar Donante de Semen</h1>
        <p class="page-subtitle">Ingrese los datos del donante</p>
    </div>
    <div>
        <a href="{{ route('medico.home') }}" class="btn-secondary">
            <i class="fas fa-arrow-left mr-2"></i> Volver
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="max-w-lg mx-auto bg-white shadow-md rounded-xl p-6">

    {{-- errores --}}
    @if ($errors->any())
        <div class="bg-red-100 border border-red-300 text-red-900 p-3 rounded-md mb-4">
            <ul class="list-disc ms-4">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('donantes.semen.store') }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label class="font-semibold">DNI del Donante <span class="text-red-600">*</span></label>
            <input 
                type="text" 
                name="dni" 
                class="form-input w-full mt-1"
                placeholder="Ingrese DNI"
                required
                value="{{ old('dni') }}"
            >
        </div>

        <button class="btn-primary w-full flex items-center justify-center gap-2">
            <i class="fas fa-save"></i> Registrar Donante
        </button>

    </form>
</div>
@endsection
