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
