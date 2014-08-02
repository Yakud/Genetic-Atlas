/**
 * Фабрика маркеров
 * @constructor
 */
Atlas.Marker.MarkerFactory = {

    /**
     * Создает новый маркер
     * @param {object} data
     * @returns {Atlas.Marker.MarkerModel}
     */
    makeMarker: function(data) {
        return new Atlas.Marker.MarkerModel(data);
    },
};