var MarkersStorage = function () {
    this.urlGetPoints = 'points/get';
};

/**
 * Делает запрос к серверу
 * @param url
 * @param data
 * @param callback
 */
MarkersStorage.prototype.request = function(url, data, callback) {
    $.post(url, data, callback);
};

/**
 * Достает из базы точки
 */
MarkersStorage.prototype.getPoints = function(callback) {
    this.request(this.urlGetPoints, {}, function(response) {
        var points = [];
        var point;

        for (var pointData in response.points) {
            point = new Altas.Marker.MarkerModel(pointData);
            points.push(point);
        }

        callback(points);
    });
};