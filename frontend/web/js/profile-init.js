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
  let dropzone = new Dropzone(".dropzone", {
    url: function () {
    },
    maxFiles: 6,
    uploadMultiple: true,
    acceptedFiles: 'image/*',
    previewTemplate: '<a href="#"><img data-dz-thumbnail alt="Фото работы"></a>',
  });

  dropzone.on("addedfile", file => files.push(file));
})();
