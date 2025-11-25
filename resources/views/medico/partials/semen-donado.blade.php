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
        <input id="p_altura" type="number" name="p_altura" class="form-control" placeholder="Ej: 165">
    </div>

    <div class="col-md-4 mb-3">
        <label class="form-label">Complexión</label>
        <input id="p_complexion_corporal" type="text" name="p_complexion" class="form-control" placeholder="Ej: delgada, robusta">
    </div>

    <div class="col-md-4 mb-3">
        <label class="form-label">Rasgos étnicos</label>
        <input id="p_rasgos_etnicos" type="text" name="p_etnia" class="form-control" placeholder="Ej: asiático">
    </div>
</div>
</div>
