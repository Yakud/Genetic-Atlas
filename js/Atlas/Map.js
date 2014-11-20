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
        var populations = t.calcPopulation([marker]);
        t.showPopulations(populations);
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

    google.maps.event.addListener(Map.MarkerCluster, "click", this.onClickCluster());
};

Atlas.Map.prototype.onClickCluster = function() {
    var t = this;

    return function(cluster) {
        var markers = cluster.getMarkers();
        var populations = t.calcPopulation(markers);
        t.showPopulations(populations);
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

Atlas.Map.prototype.calcPopulation = function(markers) {
    var populations = {};
    var population;
    var markerData;

    for (var i = 0; i < markers.length; i++ ){
        markerData = markers[i].data.data;
        population = markerData.population_id;

        if (populations[population] === undefined) {
            populations[population] = {
                total   : 1,
                markers : [
                    markers[i]
                ],
                min_age : markerData.age ? markerData.age : markerData.age_from,
                max_age : markerData.age ? markerData.age : markerData.age_to
            };
        } else {
            populations[population].total ++;
            populations[population].markers.push(markers[i]);

            populations[population].min_age = markerData.age ? (
                markerData.age < populations[population].min_age ? markerData.age : populations[population].min_age
            ) : (
                markerData.age_from < populations[population].min_age ? markerData.age_from : populations[population].min_age
            );

            populations[population].max_age = markerData.age ? (
                markerData.age > populations[population].max_age ? markerData.age : populations[population].max_age
            ) : (
                markerData.age_to > populations[population].max_age ? markerData.age_to : populations[population].max_age
            );
        }
    }

    return populations;
};

Atlas.Map.prototype.showPopulations = function(populations) {
    var ul = $('#click-details ul');
    var markers;
    var markerData;

    ul.html('');

    for (var population in populations) {
        markers = '<ul class="population-markers" data-population="' + population + '">';
        for (var markerIndex in populations[population].markers) {
            markerData = populations[population].markers[markerIndex].data.data;

            markers += '<li>';
            markers += markerData.population_id + ' ';
            if (markerData.age) {
                markers += '[' + markerData.age + ']';
            } else {
                markers += '[' + markerData.age_from + ' - ' + markerData.age_to + ']';
            }

            markers += '</li>';
        }
        markers += '</ul>';

        var jsEvent = "$('.population-markers[data-population=" + population + "]').toggle();";
        ul.append(
            '<li>' +
                '<a href="javascript: ' + jsEvent +  '  void(0);" class="toggle-markers">' + (population) + '</a>' +
                ' (' + (populations[population].total) + ')' +
                ' [' + (populations[population].min_age) + ' - ' + (populations[population].max_age) + ']' +
                markers +
            '</li>'
        );
    }
};