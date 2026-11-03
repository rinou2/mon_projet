import './stimulus_bootstrap.js';

/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';

console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');
const filterButtons = document.querySelectorAll('.choix');
const foodCards = document.querySelectorAll('.m');

// 2. Écouter le clic sur chaque bouton de catégorie
filterButtons.forEach(button => {
  button.addEventListener('click', () => {
    const filterValue = button.getAttribute('data-choix');

    // 3. Parcourir toutes les cartes pour filtrer
    foodCards.forEach(card => {
      const cardCategory = card.getAttribute('data-menu');

      // Si le filtre est "all" OU si la catégorie correspond
      if (filterValue === 'all' || filterValue === cardCategory) {
        card.style.display = 'block'; // Afficher la carte
      } else {
        card.style.display = 'none';  // Masquer la carte
      }
    });
  });
});





























