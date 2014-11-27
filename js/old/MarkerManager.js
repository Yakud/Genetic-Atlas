/**
 * Менеджер маркеров
 * @constructor
 */
function MarkerManager() {
    /**
     * Массив маркеров
     * @type {Marker[]}
     */
    this.allMarkers = [];
}

/**
 * Создает маркер в указанной позиции
 * @param latLng
 * @param title
 */
MarkerManager.prototype.makeMarker = function (title, latLng) {
    // Создаем маркер
    var marker = new Marker(title, latLng);

    // Ебашим маркер в массив
    this.allMarkers.push(marker);

    // Проиндексируем маркеры
    GlobalMarkerManager.reindexMarkers();
};

/**
 * Сохраняет данные в БД
 */
MarkerManager.prototype.saveMarkersDB = function () {
    var db = [],
        marker;

    // Перестраиваем индексы маркеров
    GlobalMarkerManager.reindexMarkers();

    for (var i in this.allMarkers) {
        marker = this.allMarkers[i];
        db.push(marker.exportObject());
    }

    $.post('/markers/save', {
        'markers' : db
    }, function(response) {
        if (response.status !== true) {
            alert(response.message);
        }
    });
};

MarkerManager.prototype.loadMarkersDB = function () {
    var t = this;
    $.getJSON('/markers', function(response) {
        if (!response.status) {
            alert(response.message);
            return;
        }

        var marker,
            title;

        for (var i in response.markers) {
            marker = response.markers[i];

            if (marker.title != marker.dbName) {
                title = marker.title + '#' + marker.dbName;
            } else {
                title = marker.title+ '#' + marker.title;
            }

            t.makeMarker(title, new google.maps.LatLng(marker.lat, marker.lng));
        }
    });
};


/**
 * Возвращает маркер с указанным заголовком
 * @param dbName
 * @returns {Marker|null}
 */
MarkerManager.prototype.getMarkerByDbName = function(dbName) {
    var marker;

    // Ищем среди всех маркеров, маркер с переданным заголовком
    for (var i in this.allMarkers) {
        marker = this.allMarkers[i];
        if (marker.dbName == dbName) {
            return marker;
        }
    }

    return null;
};

/**
 * Перестраиваем индексы маркеров
 * Юзаем, например при удалении маркера, т.к. удаляем из общего массива
 * А все маркеры должны знать свою позицию в массиве
 */
MarkerManager.prototype.reindexMarkers = function() {
    // Пробегаемся по маркерам и говорим им какой сейчас у них индекс
    for (var i in this.allMarkers) {
        this.allMarkers[i].indexMarker = i;
    }
};