
document.addEventListener('DOMContentLoaded', function () {
    const familiaresContainer = document.getElementById('familiares-container');
    const addFamiliarBtn = document.getElementById('add-familiar');

    let familiarIndex = 0;

    function createFamiliarBlock(index) {
        const wrapper = document.createElement('div');
        wrapper.classList.add('border', 'rounded', 'p-3', 'mb-3', 'bg-light');
        wrapper.innerHTML = `
            <div class="mb-2">
                <label class="form-label">Parentesco</label>
                <input type="text" name="familiares[${index}][parentesco]" class="form-control" placeholder="Ej: Madre, Padre, Hermano">
            </div>
            <div class="mb-2 position-relative">
                <label class="form-label">Antecedentes médicos</label>
                <div class="dropdown">
                    <input type="text" class="form-control antecedente-input" placeholder="Buscar término médico..." autocomplete="off">
                    <div class="dropdown-menu antecedentes-dropdown w-100 p-2" style="max-height: 180px; overflow-y: auto; width: 100%;"></div>
                </div>
                <ul class="list-group mt-2 antecedentes-list"></ul>
            </div>
        `;
        familiaresContainer.appendChild(wrapper);

        const input = wrapper.querySelector('.antecedente-input');
        const dropdown = wrapper.querySelector('.antecedentes-dropdown');
        const list = wrapper.querySelector('.antecedentes-list');

        input.addEventListener('input', function () {
            const q = input.value;
            if (q.length < 3) {
                dropdown.innerHTML = '';
                dropdown.classList.remove('show');
                return;
            }

            fetch(`/terminos/search?q=${encodeURIComponent(q)}&limit=5`)
                .then(res => res.json())
                .then(data => {
                    dropdown.innerHTML = '';
                    if (data.rows && data.rows.length > 0) {
                        data.rows.forEach(item => {
                            const card = document.createElement('div');
                            card.classList.add('border', 'rounded', 'p-2', 'mb-2', 'bg-light', 'text-dark');
                            card.style.cursor = 'pointer';
                            card.textContent = item;
                            card.addEventListener('click', () => {
                                const li = document.createElement('li');
                                li.classList.add('list-group-item', 'd-flex', 'justify-content-between', 'align-items-center');
                                li.textContent = item;

                                const hidden = document.createElement('input');
                                hidden.type = 'hidden';
                                hidden.name = `familiares[${index}][antecedentes][]`;
                                hidden.value = item;
                                li.appendChild(hidden);

                                const removeBtn = document.createElement('button');
                                removeBtn.type = 'button';
                                removeBtn.classList.add('btn', 'btn-sm', 'btn-outline-danger');
                                removeBtn.innerHTML = `<i class="fas fa-trash-alt"></i>`;
                                removeBtn.addEventListener('click', () => li.remove());
                                li.appendChild(removeBtn);

                                list.appendChild(li);
                                input.value = '';
                                dropdown.innerHTML = '';
                                dropdown.classList.remove('show');
                            });
                            dropdown.appendChild(card);
                        });
                        dropdown.classList.add('show');
                    } else {
                        dropdown.classList.remove('show');
                    }
                })
                .catch(err => {
                    console.error("Error en fetch:", err);
                    dropdown.classList.remove('show');
                });
        });

        document.addEventListener('click', function (e) {
            if (!input.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.classList.remove('show');
            }
        });
    }

    addFamiliarBtn.addEventListener('click', function () {
        createFamiliarBlock(familiarIndex++);
    });

    // Crear el primer bloque por defecto
    createFamiliarBlock(familiarIndex++);
});

