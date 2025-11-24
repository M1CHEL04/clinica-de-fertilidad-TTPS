
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('antecedente-input');
    const dropdown = document.getElementById('antecedente-dropdown');

    input.addEventListener('input', function () {
        const q = input.value;

        if (q.length < 3) {
            dropdown.innerHTML = '';
            dropdown.classList.remove('show');
            return;
        }

        fetch(`/terminos/search?q=${encodeURIComponent(q)}&limit=10`)
            .then(res => res.json())
            .then(data => {
                dropdown.innerHTML = '';
                if (data.rows && data.rows.length > 0) {
                    data.rows.forEach(item => {
                        const card = document.createElement('div');
                        card.classList.add('border', 'rounded', 'p-2', 'mb-2', 'bg-light', 'text-dark', 'cursor-pointer');
                        card.style.cursor = 'pointer';
                        card.textContent = item;
                        card.addEventListener('click', () => {
                            input.value = item;
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
});
