(() => {
  window.sendFiles = (form, files) => {
    if (files.length === 0) {
      return form.submit();
    }

    const formData = new FormData(form);
    files.forEach(it => formData.append('files[]', it));

    $.ajax({
      url: window.location.href,
      method: `POST`,
      cache: false,
      contentType: false,
      processData: false,
      data: formData,
      success: function () {
        form.submit();
      }
    });
  };

  const citiesList = document.querySelector(`#city-session`);
  citiesList && citiesList.addEventListener(`change`, async ({target}) => {
    const data = await fetch(`/site/set-ajax-city?id=${target.value}`);
    window.location.reload();
  });

  const lightbulb = document.querySelector('.header__lightbulb--active');
  lightbulb && lightbulb.addEventListener('mouseover', () => {
    lightbulb.classList.remove(`.header__lightbulb--active`);
    fetch('/site/clear-event-ribbon');
  });
})();
