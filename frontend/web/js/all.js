(() => {
  const citiesList = document.querySelector(`#city-session`);
  citiesList && citiesList.addEventListener(`change`, async ({ target }) => {
    const data = await fetch(`/site/set-ajax-city?id=${target.value}`);
    window.location.reload();
  });

  const lightbulb = document.querySelector('.header__lightbulb');
  lightbulb.addEventListener('mouseover', () => {
    fetch('/site/clear-event-ribbon');
  });
}) ();
