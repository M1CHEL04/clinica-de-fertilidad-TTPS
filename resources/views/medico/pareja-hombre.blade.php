{{-- HOMBRE – GAMETOS PROPIOS --}}
<h3>Antecedentes del paciente</h3>

{{-- Antecedentes personales --}}
<h4 class="mt-3">Antecedentes personales</h4>
<div class="mb-3">
    <textarea name="hombre_antecedentes_personales" class="form-control"></textarea>
</div>

{{-- Antecedentes familiares --}}
<h4 class="mt-3">Antecedentes familiares</h4>
<div id="hombre-familiares-container"></div>
<button type="button" id="add-hombre-familiar" class="btn btn-secondary mt-2">Añadir familiar</button>

{{-- Antecedentes genitales --}}
<h4 class="mt-4">Antecedentes genitales</h4>
<div class="mb-3">
    <textarea name="hombre_genitales" class="form-control" placeholder="Varicocele, criptorquidia, traumatismos, infecciones, cirugías..."></textarea>
</div>

{{-- Fenotipo --}}
<h4 class="mt-4">Fenotipo</h4>
<div class="row">
    <div class="col-md-4">
        <label class="form-label">Color de ojos</label>
        <select name="hombre_ojos" class="form-select">
            <option value="">Seleccione...</option>
            <option value="ambar">Ámbar</option>
            <option value="castaño">Castaño</option>
            <option value="avellana">Avellana</option>
            <option value="azul">Azul</option>
            <option value="verde">Verde</option>
            <option value="gris">Gris</option>
        </select>
    </div>

    <div class="col-md-4">
        <label class="form-label">Color de pelo</label>
        <select name="hombre_pelo" class="form-select">
            <option value="">Seleccione...</option>
            <option value="negro">Negro</option>
            <option value="castaño">Castaño</option>
            <option value="rubio">Rubio</option>
            <option value="pelirrojo">Pelirrojo</option>
        </select>
    </div>

    <div class="col-md-4">
        <label class="form-label">Tipo de pelo</label>
        <select name="hombre_tipo_pelo" class="form-select">
            <option value="">Seleccione...</option>
            <option value="liso">Liso</option>
            <option value="ondulado">Ondulado</option>
            <option value="rizado">Rizado</option>
        </select>
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-4">
        <label class="form-label">Altura</label>
        <input type="number" name="hombre_altura" class="form-control">
    </div>
    <div class="col-md-4">
        <label class="form-label">Complexión</label>
        <input type="text" name="hombre_complexion" class="form-control">
    </div>
    <div class="col-md-4">
        <label class="form-label">Rasgos étnicos</label>
        <input type="text" name="hombre_etnia" class="form-control">
    </div>
</div>

@section('scripts')
<script src="{{ asset('js/familiares.js') }}"></script>
@endsection