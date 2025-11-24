@extends('layouts.layoutInterno')
<style>
    .card-centered-wrapper {
        min-height: 80vh;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 40px;
    }
</style>
@section('content')
<div class="card-centered-wrapper">
    <div class="card shadow p-4" style="max-width: 900px; width: 100%;">

    <h1>Estudios Médicos</h1>

    <form action="{{ route('estudios.store') }}" method="POST">
        @csrf
        <!-- Input hidden con el ID del paciente -->
        <input type="hidden" name="paciente_id" value="{{ $paciente->id }}">

        <div class="mb-4">
            <label class="form-label">Estudios Ginecológicos</label>
            <div class="d-flex">
                <select id="ginecologicos-select" class="form-select me-2">
                    @foreach($ginecologicos as $estudio)
                        <option value="{{ $estudio['id'] }}">{{ $estudio['nombre'] }}</option>
                    @endforeach
                </select>
                <button type="button" id="add-ginecologico" class="btn btn-outline-primary">Agregar</button>
            </div>
            <ul id="ginecologicos-list" class="list-group mt-2"></ul>
        </div>

        <div class="mb-4">
            <label class="form-label">Estudios Hormonales</label>
            <div class="d-flex">
                <select id="hormonales-select" class="form-select me-2">
                    @foreach($hormonales as $estudio)
                        <option value="{{ $estudio['id'] }}">{{ $estudio['nombre'] }}</option>
                    @endforeach
                </select>
                <button type="button" id="add-hormonal" class="btn btn-outline-primary">Agregar</button>
            </div>
            <ul id="hormonales-list" class="list-group mt-2"></ul>
        </div>

        <div class="mb-4">
            <label class="form-label">Prequirúrgicos</label>
            <div class="d-flex">
                <select id="prequirurgicos-select" class="form-select me-2">
                    @foreach($prequirurgicos as $estudio)
                        <option value="{{ $estudio['id'] }}">{{ $estudio['nombre'] }}</option>
                    @endforeach
                </select>
                <button type="button" id="add-prequirurgico" class="btn btn-outline-primary">Agregar</button>
            </div>
            <ul id="prequirurgicos-list" class="list-group mt-2"></ul>
        </div>

        <div class="mb-4">
            <label class="form-label">Estudios de Semen</label>
            <div class="d-flex">
                <select id="semen-select" class="form-select me-2">
                    @foreach($semen as $estudio)
                        <option value="{{ $estudio['id'] }}">{{ $estudio['nombre'] }}</option>
                    @endforeach
                </select>
                <button type="button" id="add-semen" class="btn btn-outline-primary">Agregar</button>
            </div>
            <ul id="semen-list" class="list-group mt-2"></ul>
        </div>

        <button type="submit" class="btn btn-success float-end">Guardar estudios</button>
    </form>
    </div> {{-- card --}}
</div> {{-- wrapper --}}
@endsection

@section('scripts')
<script src="{{ asset('js/estudios.js')}}"></script>
@endsection
