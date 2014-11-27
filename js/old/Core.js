// Насоздаем всякую чушь
var GlobalMap           = new MapClass();
var GlobalMarkerManager = new MarkerManager();
var GlobalLineFactory   = new LineFactory();
var GlobalMainPanel     = new MainPanel();

var LineStyleGlobal = {
    geodesic: true, // Изогнутость
    strokeColor: '#FF0000', // Цвет линии
    strokeOpacity: 1.0, // Прозрачность линии
    strokeWeight: 2 // Ширина линии
};

/**
 * Возвращает текущий z-score
 * @returns {*|jQuery}
 */
function getZScore() {
    return $('input[name="min-z-score"]').val();
}

// Функция инициализации
function initialize() {
    // Пишем в объект карты указатель на гуглкарту
    GlobalMap.GoogleMap = new google.maps.Map(document.getElementById("map_canvas"), {
        center: new google.maps.LatLng(37.09024, -95.712891),
        zoom: 4,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        draggableCursor:'crosshair'
    });

    // Регистрируем события
    google.maps.event.addListener(GlobalMap.GoogleMap, "rightclick", Map.prototype.onRightClick);
    google.maps.event.addListener(GlobalMap.GoogleMap, "click", Map.prototype.onLeftClick);

    GlobalMarkerManager.loadMarkersDB();
    GlobalMainPanel.init();
}