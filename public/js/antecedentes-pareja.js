function inicializarFormParejas() {

    /* ======================================================
       UTILIDAD: buscador reutilizable
    ====================================================== */
    function attachTermSearch(input, dropdown, list, hiddenNamePrefix) {
        if (!input || !dropdown || !list) return;

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

                    if (data.rows?.length) {
                        data.rows.forEach(item => {
                            const card = document.createElement("div");
                            card.classList.add("border", "rounded", "p-2", "mb-2", "bg-light", "text-dark");
                            card.style.cursor = "pointer";
                            card.textContent = item;

                            card.addEventListener("click", () => {

                                if ([...list.children].some(li => li.textContent.includes(item)))
                                    return;

                                const li = document.createElement("li");
                                li.classList.add("list-group-item","d-flex","justify-content-between","align-items-center");
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
                                removeBtn.onclick = () => li.remove();
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
       MUJER – antecedentes familiares
    ====================================================== */
    const famContainer = document.getElementById("pareja-familiares-container");
    const famBtn = document.getElementById("add-pareja-familiar");
    let famIndex = 0;

    function newFamBlock(index) {
        if (!famContainer) return;

        const box = document.createElement("div");
        box.classList.add("border","rounded","p-3","mb-3","bg-light");

        box.innerHTML = `
            <label>Parentesco</label>
            <input type="text" class="form-control mb-2" name="pareja_familiares[${index}][parentesco]">

            <label>Antecedentes médicos</label>
            <input type="text" class="form-control familiar-antecedente-input" placeholder="buscar...">

            <div class="dropdown-menu familiar-antecedentes-dropdown w-100 p-2"></div>
            <ul class="list-group familiar-antecedentes-list mt-2"></ul>
        `;

        famContainer.appendChild(box);

        attachTermSearch(
            box.querySelector(".familiar-antecedente-input"),
            box.querySelector(".familiar-antecedentes-dropdown"),
            box.querySelector(".familiar-antecedentes-list"),
            `pareja_familiares[${index}][antecedentes][]`
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
    const hFamBtn = document.getElementById("add-hombre-familiar");
    let hIndex = 0;

    function newHFamBlock(index) {
        if (!hFamContainer) return;

        const box = document.createElement("div");
        box.classList.add("border","rounded","p-3","mb-3","bg-light");

        box.innerHTML = `
            <label>Parentesco</label>
            <input type="text" class="form-control mb-2" name="hombre_familiares[${index}][parentesco]">

            <label>Antecedentes médicos</label>
            <input type="text" class="form-control hombre-fam-input" placeholder="buscar...">

            <div class="dropdown-menu hombre-fam-dropdown w-100 p-2"></div>
            <ul class="list-group hombre-fam-list mt-2"></ul>
        `;

        hFamContainer.appendChild(box);

        attachTermSearch(
            box.querySelector(".hombre-fam-input"),
            box.querySelector(".hombre-fam-dropdown"),
            box.querySelector(".hombre-fam-list"),
            `hombre_familiares[${index}][antecedentes][]`
        );
    }

    if (hFamBtn) {
        hFamBtn.onclick = () => newHFamBlock(hIndex++);
        newHFamBlock(hIndex++);
    }
}
