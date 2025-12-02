document.addEventListener('DOMContentLoaded', function () {
    function setupSelect(addBtnId, selectId, listId, inputName) {
        const select = document.getElementById(selectId);
        const addBtn = document.getElementById(addBtnId);
        const list = document.getElementById(listId);

        addBtn.addEventListener('click', function () {
            const selectedId = select.value;
            const selectedText = select.options[select.selectedIndex].text;

            // Crear item en la lista
            const li = document.createElement('li');
            li.classList.add('list-group-item', 'd-flex', 'justify-content-between', 'align-items-center');
            li.textContent = selectedText;

            // Hidden input para enviar al backend
            const hidden = document.createElement('input');
            hidden.type = 'hidden';
            hidden.name = inputName + "[]";
            hidden.value = selectedId;
            li.appendChild(hidden);

            // Botón eliminar
            const removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.classList.add('btn', 'btn-sm', 'btn-outline-danger');
            removeBtn.innerHTML = `<i class="fas fa-trash-alt"></i>`;
            removeBtn.addEventListener('click', () => li.remove());
            li.appendChild(removeBtn);

            list.appendChild(li);
            // Deshabilitar la opción en el select
            const option = select.querySelector(`option[value="${selectedId}"]`);
            if (option) {
                option.disabled = true;
                option.classList.add('opcion-desactivada');
            }

        });
    }

    // Configurar cada bloque
    setupSelect('add-ginecologico', 'ginecologicos-select', 'ginecologicos-list', 'ginecologicos');
    setupSelect('add-hormonal', 'hormonales-select', 'hormonales-list', 'hormonales');
    setupSelect('add-prequirurgico', 'prequirurgicos-select', 'prequirurgicos-list', 'prequirurgicos');
    setupSelect('add-semen', 'semen-select', 'semen-list', 'semen');
});
