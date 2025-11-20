{{-- PAREJA MUJER – ROPA --}}
<h3>Datos de la pareja (mujer)</h3>

{{-- Antecedentes personales --}}
<h4 class="mt-3">Antecedentes personales</h4>
<div class="mb-3">
    <textarea name="pareja_antecedentes_personales" class="form-control"></textarea>
</div>

{{-- Antecedentes familiares --}}
<h4 class="mt-3">Antecedentes familiares</h4>
<div id="pareja-familiares-container"></div>
<button type="button" id="add-pareja-familiar" class="btn btn-secondary mt-2">Añadir familiar</button>

{{-- Antecedentes ginecológicos --}}
<h4 class="mt-4">Antecedentes ginecológicos</h4>
<div class="row">
    <div class="col-md-4">
        <label class="form-label">Ciclos</label>
        <select name="pareja_ciclos" class="form-select">
            <option value="">Seleccione...</option>
            <option value="regular">Regular</option>
            <option value="irregular">Irregular</option>
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label">Duración</label>
        <input type="number" name="pareja_duracion" class="form-control">
    </div>
    <div class="col-md-4">
        <label class="form-label">Características del sangrado</label>
        <input type="text" name="pareja_caracteristica" class="form-control">
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-3">
        <label class="form-label">G</label>
        <input type="number" name="pareja_g" class="form-control">
    </div>
    <div class="col-md-3">
        <label class="form-label">P</label>
        <input type="number" name="pareja_p" class="form-control">
    </div>
    <div class="col-md-3">
        <label class="form-label">AB</label>
        <input type="number" name="pareja_ab" class="form-control">
    </div>
    <div class="col-md-3">
        <label class="form-label">CT</label>
        <input type="number" name="pareja_ct" class="form-control">
    </div>
</div>

{{-- Fenotipo --}}
<h4 class="mt-4">Fenotipo</h4>
<div class="row">
    <div class="col-md-4">
        <label class="form-label">Color de ojos</label>
        <select name="pareja_ojos" class="form-select">
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
        <select name="pareja_pelo" class="form-select">
            <option value="">Seleccione...</option>
            <option value="negro">Negro</option>
            <option value="castaño">Castaño</option>
            <option value="rubio">Rubio</option>
            <option value="pelirrojo">Pelirrojo</option>
        </select>
    </div>

    <div class="col-md-4">
        <label class="form-label">Tipo de pelo</label>
        <select name="pareja_tipo_pelo" class="form-select">
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
        <input type="number" name="pareja_altura" class="form-control">
    </div>
    <div class="col-md-4">
        <label class="form-label">Complexión</label>
        <input type="text" name="pareja_complexion" class="form-control">
    </div>
    <div class="col-md-4">
        <label class="form-label">Rasgos étnicos</label>
        <input type="text" name="pareja_etnia" class="form-control">
    </div>
</div>
