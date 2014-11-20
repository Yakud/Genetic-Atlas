/**
 *
 * @param {Atlas.Marker.MarkerStorage} MarkerStorage
 * @param {Atlas.Map} Map
 * @constructor
 */
Atlas.Search = function(MarkerStorage, Map) {
    this.MarkerStorage = MarkerStorage;
    this.Map = Map;

    this.onSearchEvent = null;
};

/**
 *
 * @param text
 * @param ageFrom
 * @param ageTo
 */
Atlas.Search.prototype.search = function(text, ageFrom, ageTo) {
    var t = this;

    if (!text.length) {
        text = '*';
    }

    t.MarkerStorage.searchMarkersFullText(text, ageFrom, ageTo, function(Markers) {
        t.Map.clearAll();

        Markers.map(function(Marker) {
            t.Map.addMarker(Marker);
        });

        if (t.onSearchEvent !== null) {
            t.onSearchEvent(Markers);
        }

        t.Map.reindexMarkers();
    });
};
