Atlas.Map = function(canvasId) {
    /**
     * @type {google.maps.Map}
     */
    this.GoogleMap = null;

    /**
     * Кластер маркеров
     * @type {MarkerClusterer}
     */
    this.MarkerCluster = null;

    /**
     * Маркеры на карте
     * @type {google.maps.Marker[]}
     */
    this.markers = [];

    // Инициализируем инстанс карты
    this.init(canvasId);
};

/**
 * Инициализация карты
 */
Atlas.Map.prototype.init = function(canvasId) {
    this.GoogleMap = new google.maps.Map(document.getElementById(canvasId), {
        center: new google.maps.LatLng(37.09024, -95.712891),
        zoom: 4,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        draggableCursor:'crosshair'
    });
};

/**
 * Добавляет маркер
 * @param {Atlas.Marker.MarkerModel} Marker
 */
Atlas.Map.prototype.addMarker = function(Marker) {
    var t = this;
    var marker = new google.maps.Marker({
        position : Marker.getPosition(),
        map      : this.GoogleMap
    });

    google.maps.event.addListener(marker, 'click', function() {
        var markers = [];
        markers.push(new MarkerModel(marker.data));
        Panel.addToSearchPanel(markers);
    });

    marker.data = Marker.export();

    this.markers.push(marker);

//    this.textOverlay = new TxtOverlay(Marker.getPosition(), Marker.data.population_id, "titleOfMarker");
//    this.textOverlay.setMap(this.GoogleMap);
};

/**
 * Перестраиваем индексы маркеров
 * Юзаем, например при удалении маркера, т.к. удаляем из общего массива
 * А все маркеры должны знать свою позицию в массиве
 */
Atlas.Map.prototype.reindexMarkers = function() {
    for (var i in this.markers) {
        this.markers[i].indexMarker = i;
    }

    if (this.MarkerCluster != null) {
        this.MarkerCluster.clearMarkers();
        this.MarkerCluster = null;
    }

    this.MarkerCluster = new MarkerClusterer(this.GoogleMap, this.markers, {
        zoomOnClick: false
    });

    google.maps.event.addListener(this.MarkerCluster, "click", this.onClickCluster());
};

Atlas.Map.prototype.onClickCluster = function() {
    var t = this;

    return function(cluster) {
        var markersCluster = cluster.getMarkers();
        var markers = [];

        markersCluster.map(function(marker) {
            var markerData = marker.data;
            markers.push(new MarkerModel(markerData));
        });

        Panel.addToSearchPanel(markers);
    };
};

Atlas.Map.prototype.clearAll = function() {
    this.markers.map(function(marker) {
        marker.map = null;
    });
    this.markers = [];

    if (this.MarkerCluster != null) {
        this.MarkerCluster.clearMarkers();
        this.MarkerCluster = null;
    }
};
