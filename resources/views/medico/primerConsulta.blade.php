@extends('layouts.layoutInterno')

@section('content')
<div class="container">
    <h1>Primera Consulta</h1>

    <form action="{{ route('consulta.store') }}" method="POST">
        @csrf

        <!-- Objetivo de la consulta -->
        <div class="mb-3">
            <label for="objetivo" class="form-label">Objetivo de la consulta</label>
                <select name="objetivo" id="objetivo" class="form-select">
                    <option value="Gametos Propios">Gametos propios</option>
                    <option value="Esperma Donado">Esperma donado</option>
                    <option value="Ropa">Ropa</option>
                    <option value="Preservar">Preservar</option>
                </select>
        </div>


        <!-- Antecedentes -->
        <h3>Antecedentes</h3>
        <div class="mb-3" style="max-width: 500px;">
            <label for="antecedente-input" class="form-label">Antecedentes (SNOMED/CIE-10)</label>
            <div class="dropdown">
                <input type="text" id="antecedente-input" name="antecedente" class="form-control dropdown-toggle" data-bs-toggle="dropdown" autocomplete="off" placeholder="Escriba al menos 3 letras...">
                <div id="antecedente-dropdown" class="dropdown-menu p-2" style="max-height: 180px; overflow-y: auto; width: 100%;"></div>
            </div>
        </div>




        <!-- Personales -->
        <h3>Datos Personales</h3>
        <div class="mb-3">
            <label for="fuma" class="form-label">¿Fuma? (pack-días)</label>
            <input type="text" name="fuma" id="fuma" class="form-control" placeholder="Ej: 10 cigarros x día x 5 años / 20">
        </div>
        <div class="mb-3">
            <label for="alcohol" class="form-label">Alcohol</label>
            <input type="text" name="alcohol" id="alcohol" class="form-control" placeholder="Frecuencia y tipo">
        </div>
        <div class="mb-3">
            <label for="drogas" class="form-label">Drogas recreativas</label>
            <input type="text" name="drogas" id="drogas" class="form-control">
        </div>
        <div class="mb-3">
            <label for="observaciones" class="form-label">Observaciones</label>
            <textarea name="observaciones" id="observaciones" class="form-control"></textarea>
        </div>

        <!-- Antecedentes familiares -->
        <div class="mb-3">
            <h3>Antecedentes Familiares</h3>
            <div id="familiares-container"></div>
            <button type="button" id="add-familiar" class="btn btn-secondary mt-2">Añadir familiar</button>
        </div>




        <!-- Antecedentes ginecológicos -->
        <h3>Antecedentes Ginecológicos</h3>
        <div class="row">
            <div class="col-md-4">
                <label for="ciclos" class="form-label">Ciclos menstruales</label>
                <input type="text" name="ciclos" id="ciclos" class="form-control" placeholder="Regular/Irregular, duración, sangrado">
            </div>
            <div class="col-md-4">
                <label for="menarca" class="form-label">Menarca (edad)</label>
                <input type="number" name="menarca" id="menarca" class="form-control">
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-3">
                <label for="embarazos" class="form-label">G (embarazos)</label>
                <input type="number" name="embarazos" id="embarazos" class="form-control">
            </div>
            <div class="col-md-3">
                <label for="partos" class="form-label">P (partos)</label>
                <input type="number" name="partos" id="partos" class="form-control">
            </div>
            <div class="col-md-3">
                <label for="abortos" class="form-label">AB (abortos)</label>
                <input type="number" name="abortos" id="abortos" class="form-control">
            </div>
            <div class="col-md-3">
                <label for="ectopicos" class="form-label">CT (ectópicos)</label>
                <input type="number" name="ectopicos" id="ectopicos" class="form-control">
            </div>
        </div>
        <div class="mb-3 mt-3">
            <label for="examen_fisico" class="form-label">Examen físico</label>
            <textarea name="examen_fisico" id="examen_fisico" class="form-control"></textarea>
        </div>

        <!-- Fenotipo -->
        <h3>Fenotipo</h3>
        <div class="row">
            <div class="col-md-4">
                <label for="ojos" class="form-label">Color de ojos</label>
                <input type="text" name="ojos" id="ojos" class="form-control">
            </div>
            <div class="col-md-4">
                <label for="pelo" class="form-label">Color de pelo</label>
                <input type="text" name="pelo" id="pelo" class="form-control">
            </div>
            <div class="col-md-4">
                <label for="tipo_pelo" class="form-label">Tipo de pelo</label>
                <input type="text" name="tipo_pelo" id="tipo_pelo" class="form-control">
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-4">
                <label for="altura" class="form-label">Altura</label>
                <input type="text" name="altura" id="altura" class="form-control">
            </div>
            <div class="col-md-4">
                <label for="complexion" class="form-label">Complexión</label>
                <select name="complexion" id="complexion" class="form-select">
                    <option value="delgada">Delgada</option>
                    <option value="media">Media</option>
                    <option value="robusta">Robusta</option>
                </select>
            </div>
            <div class="col-md-4">
                <label for="etnia" class="form-label">Rasgos étnicos</label>
                <input type="text" name="etnia" id="etnia" class="form-control">
            </div>
        </div>

        <!-- Estudios médicos -->
        <h3>Estudios Médicos</h3>


         {{-- Estudios Ginecológicos --}}
        <div class="mb-3">
            <label for="ginecologicos" class="form-label">Estudios Ginecológicos</label>
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



        {{-- Estudios Hormonales --}}
        <div class="mb-3">
            <label for="hormonales" class="form-label">Estudios Hormonales</label>
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

        {{-- Estudios Prequirúrgicos --}}
        <div class="mb-3">
            <label for="prequirurgicos" class="form-label">Estudios Prequirúrgicos</label>
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

        {{-- Estudios de Semen --}}
        <div class="mb-3">
            <label for="semen" class="form-label">Estudios de Semen</label>
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


        <button type="submit" class="btn btn-primary">Enviar</button>

        <!-- Botón enviar -->
        <button type="submit" class="btn btn-primary">Guardar Consulta</button>
    </form>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/terminos.js') }}"></script>
<script src="{{ asset('js/familiares.js') }}"></script>
<script src="{{ asset('js/estudios.js')}}"></script>
@endsection











