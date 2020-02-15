(() => {
  // Функция ymaps.ready() будет вызвана, когда
  // загрузятся все компоненты API, а также когда будет готово DOM-дерево.
  ymaps.ready(init);
  function init(){
    // Создание карты.

    const locationPosition = document.querySelector('[name="location-position"]');
    if(locationPosition) {
      const center = locationPosition.value.split(` `);
      const map = new ymaps.Map("map", {
        // Координаты центра карты.
        // Порядок по умолчанию: «широта, долгота».
        // Чтобы не определять координаты центра карты вручную,
        // воспользуйтесь инструментом Определение координат.
        center,
        // Уровень масштабирования. Допустимые значения:
        // от 0 (весь мир) до 19.
        zoom: 13
      });

      map.geoObjects.add(new ymaps.Placemark(center, {
        balloonContent: '<strong>Метка цели</strong>'
      }, {
        preset: 'islands#redIcon',
      }));

    }
  }
}) ();
