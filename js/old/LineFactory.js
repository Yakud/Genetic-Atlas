/**
 * Аля фабрика линий
 * @constructor
 */
function LineFactory() {
    // Элементы на карте. Линии/текст между линиями
    this.mapElements = [];
}

/**
 * Удаляет все элементы (линии/текст) с карты
 */
LineFactory.prototype.removeAllMapElements = function() {
    var element;

    // Убирем все с карты
    for (var i in this.mapElements) {
        element = this.mapElements[i];
        element.setMap(null);
    }

    // Чистим массив элементов
    this.mapElements = [];
};

/**
 * Построить все линии связанные с маркером
 * @param marker
 */
LineFactory.prototype.drawLinesWithMarker = function(marker) {
    var t = this;
    $.post('markers/db/get', {
        'marker' : marker.dbName
    }, function(response) {
        if (!response.status) {
            alert(response.message);
            return;
        }

        DB = response.db;
        t.draw(marker);
    });
};

/**
 * Рисуем
 * @param marker
 */
LineFactory.prototype.draw = function(marker) {
    var element, secondMarker;
    var zScore = getZScore();

    // Пробежимся по базе
    for (var elementDB in DB) {
        element = DB[elementDB];
        secondMarker = null;

        // Если элемент базы имеет заголовок переданного маркера
        if (element[0] == marker.dbName) {
            secondMarker = GlobalMarkerManager.getMarkerByDbName(element[1]);
        }

        // Нашли второй маркер и строим полигонную линию
        if (secondMarker !== null) {
            // Построим линию
            var lineSyleLocal = LineStyleGlobal;

            lineSyleLocal.path = [
                marker.latLng,
                secondMarker.latLng
            ];

            var line = new google.maps.Polyline(lineSyleLocal);
            this.mapElements.push(line);

            element[3] = parseFloat(element[3]);

            if (element[3] >= zScore) {
                // z-score нам подходит
                line.setMap(GlobalMap.GoogleMap);
            }

            // Построим текст между маркерами
            element.splice(0, 2);
            var megaText = '<ul>';
            for (var elementText in element) {
                megaText += '<li>' + element[elementText] + '</li>';
            }
            megaText += '</ul>'

            var text = new TxtOverlay(new google.maps.LatLng(
                secondMarker.latLng.lat(),
                secondMarker.latLng.lng()
            ), megaText, "listOfParams title");

            // Положим элементы в коллекцию элементов
            // Что бы смогли потом удалить

            this.mapElements.push(text);

            // Положим элементы на карту
            text.setMap(GlobalMap.GoogleMap);
        }
    }
}