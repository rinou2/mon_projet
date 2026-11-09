const filterButtons = document.querySelectorAll('.choix');
const foodCards = document.querySelectorAll('.m');

filterButtons.forEach(button => {
  button.addEventListener('click', () => {
    const filterValue = button.getAttribute('data-choix');

    foodCards.forEach(card => {
      const cardCategory = card.getAttribute('data-menu');

      if (filterValue === 'all' || filterValue === cardCategory) {
        card.style.display = 'block';
      } else {
        card.style.display = 'none';  
      }
    });
  });
});
