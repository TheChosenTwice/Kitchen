document.querySelectorAll('.ingredient-btn').forEach(button => {
    button.addEventListener('click', function () {
        this.classList.toggle('btn-outline-primary');
        this.classList.toggle('btn-primary');
    });
});
