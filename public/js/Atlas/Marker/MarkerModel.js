/**
 * Модель маркера
 * @constructor
 */
Atlas.Marker.MarkerModel = function(markerData) {
    this.id   = 0;
    this.lat  = 0;
    this.lon  = 0;
    this.type = null;
    this.data = {};

    // Импортируем данные
    this.import(markerData);
};

/**
 * Импортирует данные в модель
 * @param data
 * @param data
 * @returns {Atlas.Marker.MarkerModel}
 */
Atlas.Marker.MarkerModel.prototype.import = function (data) {

    this.id   = data.id   !== undefined ? data.id : 0;
    this.lat  = data.lat  !== undefined ? data.lat  : 0;
    this.lon  = data.lon  !== undefined ? data.lon  : 0;
    this.type = data.type !== undefined ? data.type : null;
    this.data = data.data !== undefined ? data.data : {};

    return this;
};

/**
 * Экспортирует данные из модели
 * @returns {{id: *, lat: *, lon: *, type: *, data: *}}
 */
Atlas.Marker.MarkerModel.prototype.export = function() {
    return {
        id   : this.id,
        lat  : this.lat,
        lon  : this.lon,
        type : this.type,
        data : this.data,
    };
};

Atlas.Marker.MarkerModel.prototype.getPosition = function() {
    return new google.maps.LatLng(this.lat, this.lon);
};