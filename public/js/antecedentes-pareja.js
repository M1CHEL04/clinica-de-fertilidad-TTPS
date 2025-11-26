function inicializarFormParejas() {
    const tratamiento = window.tratamiento || {};
    const pareja = tratamiento.antecedentes_pareja || {};
    const hombre = tratamiento.antecedentes_hombre || {};
    console.log(tratamiento);
    console.log(pareja);

    const genital = document.getElementById("p_antecedentes_genitales");
    if (genital)
        genital.value = tratamiento?.antecedentes_genitales?.observacion ?? "";

    /* ============================================
       HELPERS
    ============================================ */
    function prefill(id, value) {
        const el = document.getElementById(id);
        if (el) el.value = value ?? "";
    }

    function prefillList(listId, items, hiddenName) {
        const list = document.getElementById(listId);
        if (!list || !items?.length) return;

        items.forEach((term) => {
            const li = document.createElement("li");
            li.className =
                "list-group-item d-flex justify-content-between align-items-center";
            li.textContent = term;

            const hidden = document.createElement("input");
            hidden.type = "hidden";
            hidden.name = hiddenName;
            hidden.value = term;

            const btn = document.createElement("button");
            btn.type = "button";
            btn.className = "btn btn-sm btn-outline-danger";
            btn.textContent = "✕";
            btn.onclick = () => li.remove();

            li.appendChild(hidden);
            li.appendChild(btn);
            list.appendChild(li);
        });
    }

    /* ============================================
       AUTO-COMPLETADO (fetch dinámico)
    ============================================ */
    function attachTermSearch(input, dropdown, list, hiddenName) {
        if (!input || !dropdown || !list) return;

        // Cerrar al click afuera
        document.addEventListener("click", (e) => {
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
                .then((res) => res.json())
                .then((data) => {
                    dropdown.innerHTML = "";
                    dropdown.style.maxHeight = "180px";
                    dropdown.style.overflowY = "auto";

                    if (data.rows?.length) {
                        data.rows.forEach((item) => {
                            const card = document.createElement("div");
                            card.classList.add(
                                "border",
                                "rounded",
                                "p-2",
                                "mb-2",
                                "bg-light",
                                "text-dark"
                            );
                            card.style.cursor = "pointer";
                            card.textContent = item;

                            card.addEventListener("click", () => {
                                // Evitar duplicados
                                if (
                                    [...list.children].some((li) =>
                                        li.textContent.includes(item)
                                    )
                                )
                                    return;

                                const li = document.createElement("li");
                                li.classList.add(
                                    "list-group-item",
                                    "d-flex",
                                    "justify-content-between",
                                    "align-items-center"
                                );
                                li.textContent = item;

                                const hidden = document.createElement("input");
                                hidden.type = "hidden";
                                hidden.name = hiddenName;
                                hidden.value = item;
                                li.appendChild(hidden);

                                const removeBtn =
                                    document.createElement("button");
                                removeBtn.type = "button";
                                removeBtn.classList.add(
                                    "btn",
                                    "btn-sm",
                                    "btn-outline-danger"
                                );
                                removeBtn.textContent = "✕";
                                removeBtn.addEventListener("click", () =>
                                    li.remove()
                                );
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

        input.addEventListener("blur", () => {
            setTimeout(() => (dropdown.innerHTML = ""), 200);
        });
    }

    /* ============================================
       BLOQUES FAMILIARES
    ============================================ */
    function createFamBlock(container, prefix, familiaresRaw) {
        if (!container) return () => {};

        // Convertimos datos reales a la estructura usada por el form
        let familiares = [];

        if (Array.isArray(familiaresRaw)) {
            familiaresRaw.forEach((item) => {
                familiares.push({
                    nombre: item.nombre ?? "",
                    enfermedad: item.enfermedad ?? "",
                });
            });
        }
        //     familiaresRaw.forEach((item) => {
        //         const parentesco = item.parentesco ?? "";
        //         console.log(item);
        //         // Si trae múltiples antecedentes, generamos un bloque por cada uno
        //         console.log(Array.isArray(item.antecedentes));
        //         if (Array.isArray(item.antecedentes)) {
        //             item.antecedentes.forEach((enf) => {
        //                 console.log(parentesco, enf);
        //                 familiares.push({
        //                     nombre: parentesco,
        //                     enfermedad: enf,
        //                 });
        //             });
        //         }
        //     });
        // }

        // Renderizamos iniciales
        familiares.forEach((f) => agregar(f));

        return () => agregar();

        /* ==============================
           Creador de bloques
        ============================== */
        function agregar(data = {}) {
            const index = container.children.length;

            const div = document.createElement("div");
            div.className = "border p-3 mb-2 rounded bg-light p_familiar_item";

            div.innerHTML = `
            <label class="form-label">Familiar</label>
            <input type="text" class="form-control mb-2" 
                name="${prefix}[${index}][nombre]" 
                value="${data.nombre ?? ""}">

            <label class="form-label">Enfermedad</label>
            <input type="text" class="form-control mb-2" 
                name="${prefix}[${index}][enfermedad]" 
                value="${data.enfermedad ?? ""}">

            <button type="button" class="btn btn-danger btn-sm eliminar-fam">
                Eliminar
            </button>
        `;

            div.querySelector(".eliminar-fam").onclick = () => div.remove();
            container.appendChild(div);
        }
    }

    /* ============================================
       CAMPOS SIMPLES
    ============================================ */
    const CAMPOS = [
        "dni",
        "ciclo_regular",
        "duracion",
        "caracteristicas_sangrado",
        "tipo_pelo",
        "color_pelo",
        "color_ojos",
        "altura",
        "complexion_corporal",
        "rasgos_etnicos",
        "AB",
        "G",
        "CT",
        "P",
    ];

    /* ============================================
       INICIALIZAR PERSONA (prefijo p_)
    ============================================ */
    function inicializarPersona(data) {
        const prefijo = "p_";

        // Campos simples
        CAMPOS.forEach((c) => {
            prefill(prefijo + c, data[c]);
        });

        // Lista antecedentes personales
        const personales = data.antecedentes_personales
            ? JSON.parse(data.antecedentes_personales)
            : [];

        prefillList(
            "p_antecedentes_personales_list",
            personales,
            "p_antecedentes_personales[]"
        );

        attachTermSearch(
            document.getElementById("p_antecedentes_personales_input"),
            document.getElementById("p_antecedentes_personales_dropdown"),
            document.getElementById("p_antecedentes_personales_list"),
            "p_antecedentes_personales[]"
        );

        /* ============================================
           FAMILIARES (ACTUALIZADO CON LOS NUEVOS IDS)
        ============================================ */
        const contFam = document.getElementById("p_familiares_container");
        const btnAdd = document.getElementById("p_btn_add_familiar");

        const familiares = data.descripcion_familiar
            ? JSON.parse(data.descripcion_familiar)
            : [];

        if (contFam) {
            const addFam = createFamBlock(contFam, "p_familiares", familiares);
            if (btnAdd) btnAdd.onclick = () => addFam();
        }
    }

    /* ============================================
       EJECUTAR (fusión pareja + hombre)
    ============================================ */
    const datosUnificados = { ...pareja, ...hombre };

    inicializarPersona(datosUnificados);
}
