// filtre des menus par thème sur la page d'accueil
// (dans une fonction pour pouvoir recharger la page sans erreur "deja declare")
function filtresAccueil() {
  const filterButtons = document.querySelectorAll('.choix');
  const foodCards = document.querySelectorAll('.m');
  const messageVide = document.getElementById('aucun-menu-accueil');

  filterButtons.forEach(button => {
    button.addEventListener('click', () => {
      const filterValue = button.getAttribute('data-choix');
      let visibles = 0;

      // le bouton choisi devient orange
      filterButtons.forEach(b => b.classList.remove('active'));
      button.classList.add('active');

      foodCards.forEach(card => {
        const cardCategory = card.getAttribute('data-menu');

        if (filterValue === 'all' || filterValue === cardCategory) {
          card.style.display = '';
          visibles++;
        } else {
          card.style.display = 'none';
        }
      });

      // message si aucun menu pour ce thème
      if (messageVide) {
        messageVide.style.display = visibles === 0 ? 'block' : 'none';
      }
    });
  });
}

filtresAccueil();
