@extends('layouts.layoutInterno')
@section('title', 'Donación de Gametos')

@section('page-header')
<div class="page-header">
    <div>
        <h1 class="page-title">Donación de Gametos</h1>
        <p class="page-subtitle">Registrar una nueva donación</p>
    </div>
</div>
@endsection

@section('content')

<div class="max-w-3xl mx-auto bg-white p-6 rounded-xl shadow">

    <!-- Botón Volver -->
    <a href="{{ url()->previous() }}"
       class="inline-flex items-center gap-2 px-4 py-2 mb-6 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition">
        <i class="fas fa-arrow-left"></i>
        Volver
    </a>

    <h2 class="text-xl font-semibold mb-4">Formulario de Donación</h2>

    <form action="{{ route('donacion.registrar') }}" method="POST" class="space-y-6">
        @csrf

        <!-- Tipo de donación -->
        <div>
            <label class="form-label">Tipo de donación</label>
            <select name="tipo_donacion" class="form-input" required>
                @foreach($enums['gamete_type'] as $value)
                    <option value="{{ $value }}">{{ ucfirst(str_replace('_',' ', $value)) }}</option>
                @endforeach
            </select>
        </div>

        <!-- Color de ojos -->
        <div>
            <label class="form-label">Color de ojos</label>
            <select name="color_ojos" class="form-input" required>
                @foreach($enums['eye_color'] as $value)
                    <option value="{{ $value }}">{{ ucfirst(str_replace('_',' ', $value)) }}</option>
                @endforeach
            </select>
        </div>

        <!-- Color de pelo -->
        <div>
            <label class="form-label">Color de pelo</label>
            <select name="color_pelo" class="form-input" required>
                @foreach($enums['hair_color'] as $value)
                    <option value="{{ $value }}">{{ ucfirst(str_replace('_',' ', $value)) }}</option>
                @endforeach
            </select>
        </div>

        <!-- Tipo de pelo -->
        <div>
            <label class="form-label">Tipo de pelo</label>
            <select name="tipo_pelo" class="form-input" required>
                @foreach($enums['hair_type'] as $value)
                    <option value="{{ $value }}">{{ ucfirst(str_replace('_',' ', $value)) }}</option>
                @endforeach
            </select>
        </div>

        <!-- Altura -->
        <div>
            <label class="form-label">Altura (cm)</label>
            <input 
                type="number" 
                name="altura" 
                class="form-input"
                min="0"
                required
            >
        </div>

        <!-- Complexión -->
        <div>
            <label class="form-label">Complexión</label>
            <select name="complexion" class="form-input" required>
                @foreach($enums['complexion'] as $value)
                    <option value="{{ $value }}">{{ ucfirst(str_replace('_',' ', $value)) }}</option>
                @endforeach
            </select>
        </div>

        <!-- Etnicidad -->
        <div>
            <label class="form-label">Etnicidad</label>
            <select name="etnicidad" class="form-input" required>
                @foreach($enums['ethnicity'] as $value)
                    <option value="{{ $value }}">{{ ucfirst(str_replace('_',' ', $value)) }}</option>
                @endforeach
            </select>
        </div>

        <!-- Botón submit -->
        <div class="pt-4">
            <button 
                type="submit"
                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-semibold rounded-md shadow hover:bg-blue-700 transition">
                Registrar Donación
            </button>
        </div>

    </form>
</div>

@endsection
