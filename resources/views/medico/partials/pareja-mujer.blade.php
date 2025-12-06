{{-- PAREJA MUJER – ROPA --}}
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
    <h3 class="mb-3">Datos de la pareja (Mujer)</h3>

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
            <input id="p_antecedentes_personales_input" type="text" class="form-control"
                placeholder="Buscar término médico..." autocomplete="off">

            <div id="p_antecedentes_personales_dropdown" class="dropdown-menu w-100 p-2"
                style="max-height:200px; overflow-y:auto;"></div>
        </div>

        <ul id="p_antecedentes_personales_list" class="list-group mt-2"></ul>
    </div>

    {{-- Antecedentes familiares --}}
    <h4 class="mt-4">Antecedentes familiares</h4>

    <div class="mb-2" id="p_familiares_container"></div>

    <button type="button" id="p_btn_add_familiar" class="btn btn-secondary mb-3">
        Añadir familiar
    </button>

    {{-- Antecedentes ginecológicos --}}
    <h4 class="mt-4">Antecedentes ginecológicos</h4>

    <div class="row">
        <div class="col-md-4 mb-3">
            <label class="form-label">Ciclos</label>
            <select id="p_ciclo_regular" name="p_ciclo_regular" class="form-select">
                <option value="">Seleccione...</option>
                <option value="regular">Regular</option>
                <option value="irregular">Irregular</option>
            </select>
        </div>

        <div class="col-md-4 mb-3">
            <label class="form-label">Duración</label>
            <input id="p_duracion" type="number" name="p_duracion" class="form-control" placeholder="Ej: 21">
        </div>

        <div class="col-md-4 mb-3">
            <label class="form-label">Características del sangrado</label>
            <input id="p_caracteristicas_sangrado" type="text" name="p_caracteristicas_sangrado" class="form-control"
                placeholder="Ej: abundante, leve">
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-3 mb-3">
            <label class="form-label">G</label>
            <input id="p_G" type="number" name="p_g" class="form-control"
                placeholder="Cantidad de embarazos">
        </div>

        <div class="col-md-3 mb-3">
            <label class="form-label">P</label>
            <input id="p_P" type="number" name="p_p" class="form-control" placeholder="Cantidad de partos">
        </div>

        <div class="col-md-3 mb-3">
            <label class="form-label">AB</label>
            <input id="p_AB" type="number" name="p_ab" class="form-control" placeholder="Cantidad de abortos">
        </div>

        <div class="col-md-3 mb-3">
            <label class="form-label">CT</label>
            <input id="p_CT" type="number" name="p_ct" class="form-control"
                placeholder="Cantidad de embarazos ectópicos">
        </div>
    </div>

    {{-- Fenotipo --}}
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
            <select id="p_rasgos_etnicos" name="p_rasgos_etnicos" class="form-select">
                <option value="" disabled selected>Seleccione rasgos étnicos</option>
                @foreach ($rasgos as $rasgo)
                    <option value="{{ $rasgo->value }}">{{ $rasgo->nombre }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>
