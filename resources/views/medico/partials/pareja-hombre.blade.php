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
            <input id="p_antecedentes_personales_input" type="text" name="p_antecedentes_personales"
                class="form-control" placeholder="Buscar término médico..." autocomplete="off">

            <div id="p_antecedentes_personales_dropdown" class="dropdown-menu w-100 p-2"
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
        <textarea id="p_antecedentes_genitales" name="p_antecedentes_genitales" class="form-control"
            placeholder="Varicocele, criptorquidia, traumatismos, infecciones, cirugías..."></textarea>
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
            <input id="p_altura" type="number" name="p_altura" class="form-control" placeholder="Ej: 178">
        </div>

        <div class="col-md-4 mb-3">
            <label class="form-label">Complexión</label>
            <select id="p_complexion" name="p_complexion" class="form-select">
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
