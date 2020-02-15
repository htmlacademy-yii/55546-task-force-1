(() => {
  var dropzone = new Dropzone("div.create__file", {url: "/tasks/create", paramName: "Attach"});

  const inputAutoComplete = document.querySelector(`#autoComplete`);
  const citiesList = document.querySelector(`#cities-list`);
  inputAutoComplete.addEventListener(`input`, async ({ target }) => {
    const apiKey = document.querySelector(`[name="yandex-api-key"]`);
    const data = await fetch(`https://geocode-maps.yandex.ru/1.x?apikey=${apiKey.value}&format=json&geocode=${target.value}`,
      { Method: `GET`, 'Content-Type': `json/application` })
      .then(res => res.json());

    citiesList.innerHTML = data.response.GeoObjectCollection.featureMember.map(({GeoObject}) => `<option value="${GeoObject.name}">`).join(``);
  });
}) ();
