(() => {
  const btn = document.querySelector(`#submit-btn`);
  const files = [];

  window.addEventListener(`load`, () => {
    btn.addEventListener(`click`, evt => {
      evt.preventDefault();
      window.sendFiles(document.forms[0], files);
    });
  });

  Dropzone.autoDiscover = false;
  let dropzone = new Dropzone(`div.create__file`, {
    url: function () {
    },
    uploadMultiple: true,
    acceptedFiles: 'image/*'
  });
  dropzone.on("addedfile", file => files.push(file));

  const inputAutoComplete = document.querySelector(`#autoComplete`);
  const citiesList = document.querySelector(`#cities-list`);
  inputAutoComplete.addEventListener(`input`, async ({target}) => {
    const data = await fetch(`/tasks/ajax-get-yandex-place?place=${target.value}`,
      {Method: `GET`, 'Content-Type': `json/application`})
      .then(res => res.json());

    citiesList.innerHTML = (data || []).map(({GeoObject}) => `<option value="${GeoObject.name}">`).join(``);
  });
})();
