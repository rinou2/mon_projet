  document.addEventListener('DOMContentLoaded', function () {     
    const liste = document.getElementById('liste-menus');     
    const formulaire = document.getElementById('filtres'); 
    const m = Array.from(liste.querySelectorAll('.m1_menu'));
    const champRecherche = document.getElementById('filtre-recherche');
    const champPrix = document.getElementById('filtre-prix');
    const champPersonnes = document.getElementById('filtre-personnes');
    const champtheme = document.getElementById('filtre-theme');
    const messageVide = document.getElementById('aucun-menu');
        
    const boutonReset = document.getElementById('filtre-reset');
    function sansAccents(texte) {
        return texte.normalize('NFD').replace(/[\u0300-\u036f]/g, '').toLowerCase();
    }
function filtrer() {
    const recherche = sansAccents(champRecherche.value.trim());
    const prixMax = parseFloat(champPrix.value);
    const personnes = parseInt(champPersonnes.value);



    let visibles = 0;

    m.forEach(function (me) {
        const titre = sansAccents(me.dataset.titre);
        const prix = parseFloat(me.dataset.prix);
        const minPersonnes = parseInt(me.dataset.personnes);

        const okRecherche = recherche === '' || titre.includes(recherche);
        const okPrix = prix <= prixMax;
        const okPersonnes = isNaN(personnes) || minPersonnes <= personnes;

        const visible = okRecherche && okPrix && okPersonnes;
        me.style.display = visible ? '' : 'none';

        if (visible) {
            visibles++;
        }
    });

    return visibles;
} 

    function mettreAJour() {
        const visibles = filtrer();
        messageVide.style.display = visibles === 0 ? 'block' : 'none';
    }    
    champRecherche.addEventListener('input', mettreAJour);
    champPrix.addEventListener('input', mettreAJour);
    champPersonnes.addEventListener('input', mettreAJour);
    boutonReset.addEventListener('click', function () {
        formulaire.reset();
        mettreAJour();
    });
    mettreAJour();

})
const filterButtons = document.querySelectorAll('.th');
const foodCards = document.querySelectorAll('.m1_menu');

filterButtons.forEach(button => {
  button.addEventListener('click', () => {
    const filterValue = button.getAttribute('data-th');

    foodCards.forEach(card => {
      const cardCategory = card.getAttribute('data-menu');

      if (filterValue === 'Tous' || filterValue === cardCategory) {
        card.style.display = 'block';
      } else {
        card.style.display = 'none';
      }
    });
  });
});
const filterButton = document.querySelectorAll('.reg');

filterButton.forEach(button => {
  button.addEventListener('click', () => {
    const filterValue = button.getAttribute('data-reg');

    foodCards.forEach(card => {
      const cardCategory = card.getAttribute('data-regime');

      if (filterValue === 'Tous' || filterValue === cardCategory) {
        card.style.display = 'block';
      } else {
        card.style.display = 'none';  
      }
    });
  });
});