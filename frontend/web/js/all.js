(() => {
  const citiesList = document.querySelector(`#city-session`);
  citiesList && citiesList.addEventListener(`change`, async ({ target }) => {
    const data = await fetch(`/site/set-ajax-city?id=${target.value}`);
    window.location.reload();
  });

  const lightbulb = document.querySelector('.header__lightbulb--active');
  lightbulb && lightbulb.addEventListener('mouseover', () => {
    lightbulb.classList.remove(`.header__lightbulb--active`);
    fetch('/site/clear-event-ribbon');
  });
}) ();
