function inicializarFormParejas() {

    /* ======================================================
       BUSCADOR REUTILIZABLE (estética unificada)
    ====================================================== */
    function attachTermSearch(input, dropdown, list, hiddenNamePrefix) {
        if (!input || !dropdown || !list) return;

        // Cerrar al click afuera
        document.addEventListener("click", e => {
            if (!input.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.classList.remove("show");
            }
        });

        input.addEventListener("input", function () {
            const q = input.value.trim();

            if (q.length < 3) {
                dropdown.innerHTML = "";
                dropdown.classList.remove("show");
                return;
            }

            fetch(`/terminos/search?q=${encodeURIComponent(q)}&limit=5`)
                .then(res => res.json())
                .then(data => {
                    dropdown.innerHTML = "";
                    dropdown.style.maxHeight = "180px";
                    dropdown.style.overflowY = "auto";

                    if (data.rows?.length) {
                        data.rows.forEach(item => {
                            const card = document.createElement("div");
                            card.classList.add(
                                "border", "rounded", "p-2", "mb-2",
                                "bg-light", "text-dark"
                            );
                            card.style.cursor = "pointer";
                            card.textContent = item;

                            card.addEventListener("click", () => {

                                // Evitar duplicados
                                if ([...list.children].some(li => li.textContent.includes(item)))
                                    return;

                                const li = document.createElement("li");
                                li.classList.add(
                                    "list-group-item", "d-flex",
                                    "justify-content-between", "align-items-center"
                                );
                                li.textContent = item;

                                const hidden = document.createElement("input");
                                hidden.type = "hidden";
                                hidden.name = hiddenNamePrefix;
                                hidden.value = item;
                                li.appendChild(hidden);

                                const removeBtn = document.createElement("button");
                                removeBtn.type = "button";
                                removeBtn.classList.add("btn","btn-sm","btn-outline-danger");
                                removeBtn.textContent = "✕";
                                removeBtn.addEventListener("click", () => li.remove());
                                li.appendChild(removeBtn);

                                list.appendChild(li);

                                input.value = "";
                                dropdown.innerHTML = "";
                                dropdown.classList.remove("show");
                            });

                            dropdown.appendChild(card);
                        });

                        dropdown.classList.add("show");
                    } else {
                        dropdown.classList.remove("show");
                    }
                })
                .catch(() => dropdown.classList.remove("show"));
        });
    }

    /* ======================================================
       MUJER – antecedentes personales
    ====================================================== */
    attachTermSearch(
        document.getElementById("pareja-antecedente-input"),
        document.getElementById("pareja-antecedente-dropdown"),
        document.getElementById("pareja-antecedente-list"),
        "pareja_antecedentes_personales[]"
    );

    /* ======================================================
       MUJER – antecedentes familiares (bloques dinámicos)
    ====================================================== */
    const famContainer = document.getElementById("pareja-familiares-container");
    const famBtn = document.getElementById("add-pareja-familiar");
    let famIndex = 0;

    function newFamBlock(i) {
        const wrap = document.createElement("div");
        wrap.classList.add("border", "rounded", "p-3", "mb-3", "bg-light");

        wrap.innerHTML = `
            <div class="mb-2">
                <label class="form-label">Parentesco</label>
                <input type="text" class="form-control" name="pareja_familiares[${i}][parentesco]">
            </div>

            <div class="mb-2 position-relative">
                <label class="form-label">Antecedentes médicos</label>
                <div class="dropdown w-100">
                    <input type="text" class="form-control familiar-antecedente-input" 
                           placeholder="Buscar término médico..." autocomplete="off">
                    <div class="dropdown-menu familiar-antecedentes-dropdown w-100 p-2"></div>
                </div>
                <ul class="list-group mt-2 familiar-antecedentes-list"></ul>
            </div>
        `;

        famContainer.appendChild(wrap);

        attachTermSearch(
            wrap.querySelector(".familiar-antecedente-input"),
            wrap.querySelector(".familiar-antecedentes-dropdown"),
            wrap.querySelector(".familiar-antecedentes-list"),
            `pareja_familiares[${i}][antecedentes][]`
        );
    }

    if (famBtn) {
        famBtn.onclick = () => newFamBlock(famIndex++);
        newFamBlock(famIndex++);
    }

    /* ======================================================
       HOMBRE – antecedentes personales
    ====================================================== */
    attachTermSearch(
        document.getElementById("hombre-antecedente-input"),
        document.getElementById("hombre-antecedente-dropdown"),
        document.getElementById("hombre-antecedente-list"),
        "hombre_antecedentes_personales[]"
    );

    /* ======================================================
       HOMBRE – antecedentes familiares
    ====================================================== */
    const hFamContainer = document.getElementById("hombre-familiares-container");
    const hBtn = document.getElementById("add-hombre-familiar");
    let hIdx = 0;

    function newHFamBlock(i) {
        const wrap = document.createElement("div");
        wrap.classList.add("border", "rounded", "p-3", "mb-3", "bg-light");

        wrap.innerHTML = `
            <div class="mb-2">
                <label class="form-label">Parentesco</label>
                <input type="text" class="form-control" name="hombre_familiares[${i}][parentesco]">
            </div>

            <div class="mb-2 position-relative">
                <label class="form-label">Antecedentes médicos</label>
                <div class="dropdown w-100">
                    <input type="text" class="form-control hombre-fam-input"
                           placeholder="Buscar término médico..." autocomplete="off">
                    <div class="dropdown-menu hombre-fam-dropdown w-100 p-2"></div>
                </div>
                <ul class="list-group hombre-fam-list mt-2"></ul>
            </div>
        `;

        hFamContainer.appendChild(wrap);

        attachTermSearch(
            wrap.querySelector(".hombre-fam-input"),
            wrap.querySelector(".hombre-fam-dropdown"),
            wrap.querySelector(".hombre-fam-list"),
            `hombre_familiares[${i}][antecedentes][]`
        );
    }

    if (hBtn) {
        hBtn.onclick = () => newHFamBlock(hIdx++);
        newHFamBlock(hIdx++);
    }
}
