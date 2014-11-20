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
 */
Atlas.Search.prototype.search = function(text) {
    var t = this;


    if (!text.length) {
        t.MarkerStorage.getMarkers(function(Markers) {
            t.Map.clearAll();

            Markers.map(function(Marker) {
                t.Map.addMarker(Marker);
            });

            if (t.onSearchEvent !== null) {
                t.onSearchEvent(Markers);
            }

            t.Map.reindexMarkers();
        });
    } else {
        t.MarkerStorage.searchMarkersFullText(text, function(Markers) {
            t.Map.clearAll();

            Markers.map(function(Marker) {
                t.Map.addMarker(Marker);
            });

            if (t.onSearchEvent !== null) {
                t.onSearchEvent(Markers);
            }

            t.Map.reindexMarkers();
        });
    }
};
