 /**
 * Маркер на карте
 * @param title
 * @param latLng
 * @constructor
 */
function Marker(title, latLng) {
    if (title.indexOf('#') + 1) {
        title = title.split('#');

        this.title = title[0];
        this.dbName = title[1];
    } else {
        this.title = title;
        this.dbName = title;
    }

    // Позиция маркера на карте
    this.latLng = latLng;

    // Индекс в массиве всех маркеров (перестроим позже)
    this.indexMarker = -1;

    // Создаем маркер на карте
    this.marker = new google.maps.Marker({
        position: this.latLng,
        title   : this.title,
        map     : GlobalMap.GoogleMap
    });

    var t = this;

    // Событие нажатия правой кнопки на маркере
    google.maps.event.addListener(this.marker, 'rightclick', function() {
        // Удаляем маркер
        t.remove();
    });

    // Событие нажатия левой кнопки на маркере
    google.maps.event.addListener(this.marker, 'click', function() {
        // Чистим все пути с карты
        GlobalLineFactory.removeAllMapElements();

        // Строим все пути с маркером
        GlobalLineFactory.drawLinesWithMarker(t);

        // Грузим и показываем описание в панельке
        GlobalMainPanel.getDescription(t);
    });

    // Заголовок для маркера на карте
    this.textOverlay = new TxtOverlay(this.latLng, this.title, "titleOfMarker");
    this.textOverlay.setMap(GlobalMap.GoogleMap);
}

/**
 * Удаляем маркер
 */
Marker.prototype.remove = function() {
    // Удаляем с карты маркер
    this.marker.setMap(null);
    this.textOverlay.setMap(null);

    // Удаляем маркер из массива
    GlobalMarkerManager.allMarkers.splice(this.indexMarker, 1);

    // Сохраняем маркеры в бд
    GlobalMarkerManager.saveMarkersDB();

    // Чистим все пути с карты
    GlobalLineFactory.removeAllMapElements();
};

/**
 * Экспортирует объект
 * @returns {{title: *, dbName: *, lat: *, lng: *}}
 */
Marker.prototype.exportObject = function() {
    return {
        'title' : this.title,
        'dbName' : this.dbName,
        'lat' : this.latLng.lat(),
        'lng' : this.latLng.lng()
    };
};