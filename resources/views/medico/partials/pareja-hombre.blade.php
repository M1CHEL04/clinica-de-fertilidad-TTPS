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
<h3 class="mb-3">Datos de la pareja (Hombre)</h3>

{{-- DNI --}}
<div class="row mb-3">
    <div class="col-md-4">
        <label class="form-label">DNI</label>
        <input id="p_dni" type="number" name="p_dni" class="form-control" placeholder="Ej: 12345678">
    </div>
</div>

{{-- Antecedentes personales --}}
<h4 class="mt-4">Antecedentes personales</h4>
<div class="mb-3 position-relative">

    <div class="dropdown w-100">
        <input id="p_antecedentes_personales_input"
               type="text"
               name="p_antecedentes_personales"
               class="form-control"
               placeholder="Buscar término médico..."
               autocomplete="off">

        <div id="p_antecedentes_personales_dropdown"
             class="dropdown-menu w-100 p-2"
             style="max-height:200px; overflow-y:auto;">
        </div>
    </div>

    <ul id="p_antecedentes_personales_list" class="list-group mt-2"></ul>
</div>

{{-- Antecedentes familiares --}}
<h4 class="mt-4">Antecedentes familiares</h4>
<div class="mb-2" id="p_familiares_container"></div>
<button type="button" id="p_btn_add_familiar" class="btn btn-secondary mb-3">Añadir familiar</button>

{{-- Antecedentes genitales --}}
<h4 class="mt-4">Antecedentes genitales</h4>
<div class="mb-3">
    <textarea id="p_antecedentes_genitales"
              name="p_antecedentes_genitales"
              class="form-control"
              placeholder="Varicocele, criptorquidia, traumatismos, infecciones, cirugías..."></textarea>
</div>

{{-- Fenotipo --}}
<h4 class="mt-4">Fenotipo</h4>

<div class="row">
    <div class="col-md-4 mb-3">
        <label class="form-label">Color de ojos</label>
        <select id="p_color_ojos" name="p_color_ojos" class="form-select">
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
        <select id="p_color_pelo" name="p_color_pelo" class="form-select">
            <option value="">Seleccione...</option>
            <option value="negro">Negro</option>
            <option value="castaño">Castaño</option>
            <option value="rubio">Rubio</option>
            <option value="pelirrojo">Pelirrojo</option>
        </select>
    </div>

    <div class="col-md-4 mb-3">
        <label class="form-label">Tipo de pelo</label>
        <select id="p_tipo_pelo" name="p_tipo_pelo" class="form-select">
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
        <input id="p_altura" type="number" name="p_altura" class="form-control" placeholder="Ej: 178">
    </div>

    <div class="col-md-4 mb-3">
        <label class="form-label">Complexión</label>
        <input id="p_complexion_corporal" type="text" name="p_complexion_corporal" class="form-control" placeholder="Ej: delgado, robusto">
    </div>

    <div class="col-md-4 mb-3">
        <label class="form-label">Rasgos étnicos</label>
        <input id="p_rasgos_etnicos" type="text" name="p_rasgos_etnicos" class="form-control" placeholder="Ej: asiatico">
    </div>
</div>
</div>
