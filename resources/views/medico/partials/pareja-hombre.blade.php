{{-- HOMBRE – GAMETOS PROPIOS --}}
<style>
.form-section {
    background: #f8f9fa;
    border: 1px solid #dcdcdc;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 25px;
}
</style>
<div class="form-section">
<h3 class="mb-3">Datos de la pareja</h3>

{{-- DNI --}}
<div class="row mb-3">
    <div class="col-md-4">
        <label class="form-label">DNI</label>
        <input type="number" name="dni" class="form-control" placeholder="Ej: 12345678">
    </div>
</div>

{{-- Antecedentes personales --}}
<h4 class="mt-4">Antecedentes personales</h4>
<div class="mb-3 position-relative">
    <div class="dropdown w-100">
        <input id="hombre-antecedente-input"
               type="text"
               class="form-control"
               placeholder="Buscar término médico..."
               autocomplete="off">

        <div id="hombre-antecedente-dropdown"
             class="dropdown-menu w-100 p-2"
             style="max-height:200px; overflow-y:auto;"></div>
    </div>

    <ul id="hombre-antecedente-list" class="list-group mt-2"></ul>
</div>

{{-- Antecedentes familiares --}}
<h4 class="mt-4">Antecedentes familiares</h4>
<div class="mb-2" id="hombre-familiares-container"></div>
<button type="button" id="add-hombre-familiar" class="btn btn-secondary mb-3">Añadir familiar</button>

{{-- Antecedentes genitales --}}
<h4 class="mt-4">Antecedentes genitales</h4>
<div class="mb-3">
    <textarea name="hombre_genitales" class="form-control"
              placeholder="Varicocele, criptorquidia, traumatismos, infecciones, cirugías..."></textarea>
</div>

{{-- Fenotipo --}}
<h4 class="mt-4">Fenotipo</h4>

<div class="row">
    <div class="col-md-4 mb-3">
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

    <div class="col-md-4 mb-3">
        <label class="form-label">Color de pelo</label>
        <select name="hombre_pelo" class="form-select">
            <option value="">Seleccione...</option>
            <option value="negro">Negro</option>
            <option value="castaño">Castaño</option>
            <option value="rubio">Rubio</option>
            <option value="pelirrojo">Pelirrojo</option>
        </select>
    </div>

    <div class="col-md-4 mb-3">
        <label class="form-label">Tipo de pelo</label>
        <select name="hombre_tipo_pelo" class="form-select">
            <option value="">Seleccione...</option>
            <option value="liso">Liso</option>
            <option value="ondulado">Ondulado</option>
            <option value="rizado">Rizado</option>
        </select>
    </div>
</div>

<div class="row">
    <div class="col-md-4 mb-3">
        <label class="form-label">Altura (en cm)</label>
        <input type="number" name="hombre_altura" class="form-control" placeholder="Ej: 178">
    </div>

    <div class="col-md-4 mb-3">
        <label class="form-label">Complexión</label>
        <input type="text" name="hombre_complexion" class="form-control" placeholder="Ej: delgado, robusto">
    </div>

    <div class="col-md-4 mb-3">
        <label class="form-label">Rasgos étnicos</label>
        <input type="text" name="hombre_etnia" class="form-control" placeholder="Ej: asiatico">
    </div>
</div>
</div>