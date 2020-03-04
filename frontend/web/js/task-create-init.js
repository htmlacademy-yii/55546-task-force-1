(() => {
  var dropzone = new Dropzone("div.create__file", {url: "/tasks/create", paramName: "Attach"});

  const inputAutoComplete = document.querySelector(`#autoComplete`);
  const citiesList = document.querySelector(`#cities-list`);
  inputAutoComplete.addEventListener(`input`, async ({ target }) => {
    const data = await fetch(`/tasks/ajax-get-yandex-place?place=${target.value}`,
      { Method: `GET`, 'Content-Type': `json/application` })
      .then(res => res.json());

    citiesList.innerHTML = (data || []).map(({GeoObject}) => `<option value="${GeoObject.name}">`).join(``);
  });

}) ();
