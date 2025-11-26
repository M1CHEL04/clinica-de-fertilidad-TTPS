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
                <option value="" disabled selected>Seleccione un color de ojos</option>
                @foreach ($coloresOjos as $color)
                    <option value="{{ $color->value }}">{{ $color->nombre }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-4 mb-3">
            <label class="form-label">Color de pelo</label>
            <select id="p_color_pelo" name="p_color_pelo" class="form-select">
                <option value="" disabled selected>Seleccione un color de pelo</option>
                @foreach ($coloresPelo as $color)
                    <option value="{{ $color->value }}">{{ $color->nombre }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-4 mb-3">
            <label class="form-label">Tipo de pelo</label>
            <select id="p_tipo_pelo" name="p_tipo_pelo" class="form-select">
                <option value="" disabled selected>Seleccione un tipo de pelo</option>
                @foreach ($tipoPelo as $tipo)
                    <option value="{{ $tipo->value }}">{{ $tipo->nombre }}</option>
                @endforeach
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
            <select id="p_complexion_corporal" name="p_complexion" class="form-select">
                <option value="" disabled selected>Seleccione una complexión</option>
                @foreach ($complexiones as $complexion)
                    <option value="{{ $complexion->value }}">{{ $complexion->nombre }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-4 mb-3">
            <label class="form-label">Rasgos étnicos</label>
            <select id="p_rasgos_etnicos" name="p_etnia" class="form-select">
                <option value="" disabled selected>Seleccione rasgos étnicos</option>
                @foreach ($rasgos as $rasgo)
                    <option value="{{ $rasgo->value }}">{{ $rasgo->nombre }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>
