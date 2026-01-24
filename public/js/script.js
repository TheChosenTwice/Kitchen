// Handles button toggling, ingredient displaying and saving them after page reloads
const KEY = 'selectedIngredients';
document.addEventListener('DOMContentLoaded', () => {
    const buttons = document.querySelectorAll('.ingredient-btn');
    const list = document.getElementById('selected-ingredients');
    // Loads stored ingredients from localStorage
    const stored = (() => { try { return JSON.parse(localStorage.getItem(KEY)) || []; } catch { return []; } })();

    // Sets up buttons and list based on stored ingredients
    buttons.forEach(b => {
        const name = b.dataset.ingredient;
        const id = b.dataset.ingredientId;
        if (stored.some(item => item.id === id)) {
            b.classList.replace('btn-outline-primary', 'btn-primary');
            if (list && !list.querySelector(`li[data-ingredient-id="${id}"]`)) {
                const li = document.createElement('li'); li.className = 'list-group-item'; li.dataset.ingredientId = id; li.textContent = name; list.appendChild(li);
            }
        }
        // Toggle button logic
        b.addEventListener('click', () => {
            b.classList.toggle('btn-outline-primary'); b.classList.toggle('btn-primary');
            const idx = stored.findIndex(item => item.id === id);
            if (b.classList.contains('btn-primary')) {
                if (idx === -1) stored.push({id, name});
                if (list && !list.querySelector(`li[data-ingredient-id="${id}"]`)) { const li = document.createElement('li'); li.className = 'list-group-item'; li.dataset.ingredientId = id; li.textContent = name; list.appendChild(li); }
            }
            else {
                if (idx !== -1) stored.splice(idx, 1);
                const ex = list && list.querySelector(`li[data-ingredient-id="${id}"]`); if (ex) ex.remove();
            }
            try { localStorage.setItem(KEY, JSON.stringify(stored)); } catch {}
        });
    });

    // Clear all button logic
    const clearBtn = document.getElementById('clear-all');
    if (clearBtn) {
        clearBtn.addEventListener('click', () => {
            // Remove from localStorage
            localStorage.removeItem(KEY);
            // Clear UI list
            if (list) list.innerHTML = '';
            // Reset all ingredient buttons
            buttons.forEach(b => {
                b.classList.remove('btn-primary');
                b.classList.add('btn-outline-primary');
            });
        });
    }
});

// Adds to URL ingredient IDs after clicking the "Find Recipes" button
document.addEventListener('DOMContentLoaded', () => {
    const btn = document.getElementById('find-recipes');
    btn.addEventListener('click', function(e) {
        const selected = JSON.parse(localStorage.getItem('selectedIngredients') || '[]');
        const ids = selected.map(item => item.id).filter(id => id && id !== '');
        const url = new URL(btn.href, window.location.origin);
        if (ids.length) {
            url.searchParams.set('ingredients', ids.join(','));
        } else {
            url.searchParams.delete('ingredients');
        }
        btn.href = url.toString();
    });
});