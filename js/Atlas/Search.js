/**
 *
 * @param {Atlas.Marker.MarkerStorage} MarkerStorage
 * @param {Atlas.Map} Map
 * @param Panel
 * @constructor
 */
Atlas.Search = function(MarkerStorage, Map, Panel) {
    this.MarkerStorage = MarkerStorage;
    this.Map           = Map;
    this.Panel         = Panel;
    this.onSearchEvent = null;
};

/**
 *
 * @param params
 */
Atlas.Search.prototype.search = function(params) {
    var t = this;

    t.MarkerStorage.searchMarkersFullText(params, function(Markers) {
        t.Map.clearAll();

        Markers.map(function(Marker) {
            t.Map.addMarker(Marker);
        });

        // Добавим в табличку поиска
//        t.Panel.clearSearchPanel();
        t.Panel.addToSearchPanel(Markers);

        if (t.onSearchEvent !== null) {
            t.onSearchEvent(Markers);
        }

        t.Map.reindexMarkers();
    });
};
