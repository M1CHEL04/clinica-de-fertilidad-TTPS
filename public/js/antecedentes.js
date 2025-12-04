document.addEventListener("DOMContentLoaded", () => {
    const input = document.getElementById("antecedente-input");
    const dropdown = document.getElementById("antecedente-dropdown");
    const list = document.getElementById("antecedente-list");

    input.addEventListener("input", function(e) {
        e.stopPropagation();
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
                            
                            // Evitar duplicados
                            if ([...list.children].some(li => li.textContent.includes(item))) return;

                            const li = document.createElement('li');
                            li.classList.add('list-group-item', 'd-flex', 'justify-content-between', 'align-items-center');
                            li.textContent = item;

                            const hidden = document.createElement('input');
                            hidden.type = 'hidden';
                            hidden.name = 'antecedentes[]';
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

    // Cerrar dropdown al hacer click fuera
    document.addEventListener('click', e => {
        if (!input.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.classList.remove('show');
        }
    });
});