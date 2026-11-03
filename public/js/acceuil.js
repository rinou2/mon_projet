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
        card.style.display = 'block'; // Afficher la carte"
      } else {
        card.style.display = 'none';  // Masquer la carte
      }
    });
  });
});

/*input  name ='imag' file .jpg.png pdf  etc 
 getextension -< .png 
$variable image (uuid) 123564.png ---> insertion bd 
move src/public/image

asset{{ asset('image/{menu.image) }}*/