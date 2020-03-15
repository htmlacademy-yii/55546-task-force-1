(() => {
  const btn = document.querySelector(`#submit-btn`);
  const files = [];

  btn.addEventListener(`click`, evt => {
    evt.preventDefault();

    const formData = new FormData(document.forms[0]);
    if (files) {
      files.forEach(it => formData.append('files[]', it));
    }

    $.ajax({
      url: window.location.href,
      method: `POST`,
      cache: false,
      contentType: false,
      processData: false,
      data: formData,
    });
  });

  Dropzone.autoDiscover = false;
  let dropzone = new Dropzone(`div.create__file`, {
    url: function() {},
    uploadMultiple: true,
    acceptedFiles: 'image/*'
  });
  dropzone.on("addedfile", file => files.push(file));

  const inputAutoComplete = document.querySelector(`#autoComplete`);
  const citiesList = document.querySelector(`#cities-list`);
  inputAutoComplete.addEventListener(`input`, async ({ target }) => {
    const data = await fetch(`/tasks/ajax-get-yandex-place?place=${target.value}`,
      { Method: `GET`, 'Content-Type': `json/application` })
      .then(res => res.json());

    citiesList.innerHTML = (data || []).map(({GeoObject}) => `<option value="${GeoObject.name}">`).join(``);
  });
}) ();
