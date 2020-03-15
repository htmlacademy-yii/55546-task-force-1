(() => {
  const btn = document.querySelector(`#submit-btn`);
  const files = [];

  btn.addEventListener(`click`, evt => {
    evt.preventDefault();

    if(files.length === 0) {
      return document.forms[0].submit();
    }

    const formData = new FormData();
    files.forEach(it => formData.append('files[]', it));

    $.ajax({
      url: '/settings/photo-load',
      method: `POST`,
      cache: false,
      contentType: false,
      processData: false,
      data: formData,
      success() {
        document.forms[0].submit();
      },
    });
  });

  Dropzone.autoDiscover = false;
  let dropzone = new Dropzone(".dropzone", {
    url: function() {},
    maxFiles: 6,
    uploadMultiple: true,
    acceptedFiles: 'image/*',
    previewTemplate: '<a href="#"><img data-dz-thumbnail alt="Фото работы"></a>',
  });

  dropzone.on("addedfile", file => files.push(file));
}) ();
