// This script handles the selection and deselection of ingredients
// and updates the displayed list of selected ingredients accordingly.
document.querySelectorAll('.ingredient-btn').forEach(button => {
    button.addEventListener('click', function () {
        this.classList.toggle('btn-outline-primary');
        this.classList.toggle('btn-primary');

        const ingredientName = this.getAttribute('data-ingredient');
        const selectedList = document.getElementById('selected-ingredients');
        let existing = selectedList.querySelector(`li[data-ingredient="${ingredientName}"]`);

        if (this.classList.contains('btn-primary')) {
            if (!existing) {
                let li = document.createElement('li');
                li.className = 'list-group-item';
                li.setAttribute('data-ingredient', ingredientName);
                li.textContent = ingredientName;
                selectedList.appendChild(li);
            }
        } else {
            if (existing) {
                selectedList.removeChild(existing);
            }
        }
    });
});

// Passes ingredient IDs to the URL
document.getElementById('find-recipes').addEventListener('click', function (e) {
    e.preventDefault();

    const ids = Array.from(document.querySelectorAll('.ingredient-btn.btn-primary'))
        .map(btn => btn.dataset.ingredientId)
        .join(',');

    this.href = this.href.replace(/ingredients=.*/, 'ingredients=' + ids);
    window.location.href = this.href;
});

// Clear all selected ingredients
document.getElementById('clear-all').addEventListener('click', () => {
    document.querySelectorAll('.ingredient-btn.btn-primary').forEach(btn => {
        btn.classList.remove('btn-primary');
        btn.classList.add('btn-outline-primary');
    });

    document.getElementById('selected-ingredients').innerHTML = '';
});


