
// Récupère les éléments HTML
const checkbox = document.getElementById('checkbox');
const textFieldContainer = document.getElementById('text-field-container');

// Cache le champ texte au chargement de la page
textFieldContainer.style.display = 'none';

// Ajoute un écouteur d'événements sur la case à cocher
checkbox.addEventListener('change', function() {
    if (checkbox.checked) {
    textFieldContainer.style.display = 'block'; // Affiche le champ texte si la case est cochée
    } else {
    textFieldContainer.style.display = 'none'; // Cache le champ texte si la case est décochée
    }
});