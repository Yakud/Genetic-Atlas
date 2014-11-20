<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />

    <title>Map</title>

    <link rel="stylesheet" href="/css/main.css" />
    <link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.0/themes/smoothness/jquery-ui.css" />

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.0/jquery-ui.min.js"></script>

    <script src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>

    <script src="/js/namespace.js"></script>

    <script src="/js/Atlas/Map.js"></script>
    <script src="/js/Atlas/Marker/MarkerModel.js"></script>
    <script src="/js/Atlas/Marker/MarkerFactory.js"></script>
    <script src="/js/Atlas/Marker/MarkersStorage.js"></script>
    <script src="/js/Atlas/Search.js"></script>
    <script src="/js/TxtOverlay.js"></script>
<!--    <script src="http://google-maps-utility-library-v3.googlecode.com/svn/tags/markerclusterer/1.0.2/src/markerclusterer.js"></script>-->
    <script src="http://google-maps-utility-library-v3.googlecode.com/svn/trunk/markerclustererplus/src/markerclusterer.js"></script>

</head>
<body>
    <div class="ui-element" id="search-result"></div>

    <div class="ui-element" id="click-details-back"></div>
    <div class="ui-element" id="click-details" style="overflow: auto;">
        <ul>

        </ul>
    </div>

    <input type="text" class="ui-element" id="fulltext-search" placeholder="Fulltext search" />
    <!--suppress HtmlUnknownTarget -->
    <div id="map_canvas"></div>

    <script type="text/javascript">
        var MarkerModel   = Atlas.Marker.MarkerModel;
        var Map           = new Atlas.Map('map_canvas');
        var MarkerStorage = new Atlas.Marker.MarkerStorage();
        var Search        = new Atlas.Search(MarkerStorage, Map);

        Search.onSearchEvent = function(Markers) {
            var text = "Find " + (Markers.length) + " markers";

            $('#search-result').text(text);
        };

        MarkerStorage.getMarkers(function(Markers) {
            Markers.map(function(Marker) {
                Map.addMarker(Marker);
            });

            Map.reindexMarkers();
        });

        $(function() {
            $("#fulltext-search").keyup(function(event){
                if(event.keyCode == 13){
                    Search.search($(this).val());
                }
            });
        });
    </script>
</body>
</html>
