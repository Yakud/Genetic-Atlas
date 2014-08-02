var MapClass = function() {
    // Указатель на гугл карту
    this.GoogleMap = null;
};

/**
 * Событие нажатия правой кнопки на карте
 * @param event
 */
MapClass.prototype.onRightClick = function(event) {
    // Создаем маркер в позиции курсора
//    GlobalMarkerManager.makeMarker(window.prompt('Заголовок для маркера', ''), event.latLng);

    // Сохраняем маркеры в бд
//    GlobalMarkerManager.saveMarkersDB();
};

/**
 * Событие нажатия левой кнопки на карте
 * @param event
 */
MapClass.prototype.onLeftClick = function(event) {
    // Чистим все пути с карты
//    GlobalLineFactory.removeAllMapElements();
};