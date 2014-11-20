<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />

    <title>Map</title>

    <link rel="stylesheet" href="/css/main.css" />
    <link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/themes/smoothness/jquery-ui.css" />
<!--    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">-->
<!--    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap-theme.min.css">-->

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>
    <script src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>
<!--    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>-->

    <script src="/js/namespace.js"></script>
    <script src="/js/Atlas/Map.js"></script>
    <script src="/js/Atlas/Marker/MarkerModel.js"></script>
    <script src="/js/Atlas/Marker/MarkerFactory.js"></script>
    <script src="/js/Atlas/Marker/MarkersStorage.js"></script>
    <script src="/js/Atlas/Search.js"></script>
    <script src="/js/TxtOverlay.js"></script>
    <script src="http://google-maps-utility-library-v3.googlecode.com/svn/trunk/markerclustererplus/src/markerclusterer.js"></script>

</head>
<body>
    <div class="ui-element" style="left: 50%; margin-left: -175px;">
        <input type="text" id="fulltext-search" placeholder="Search" />
        <div class="clear-search">
            <a href="#">
                x
            </a>
        </div>

        <div id="age-inputs" >
            <div id="age-range"></div>
            <input type="text" class="amount" id="amount-min" readonly>
            <input type="text" class="amount" id="amount-max" readonly>
        </div>

        <div id="search-result"></div>
    </div>

    <div class="ui-element" id="click-details-back"></div>
    <div class="ui-element" id="click-details"><ul></ul></div>

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
                    var $range = $("#age-range");
                    Search.search($(this).val(), $range.slider("values", 0), $range.slider("values", 1));
                }
            });

            $.get('age.php', {}, function(response) {
                var $range = $("#age-range");

                $range.slider({
                    range: true,
                    min: response.range.min,
                    max: response.range.max,
                    values: [ response.range.min, response.range.max ],
                    slide: function( event, ui ) {
                        $("#amount-min").val(ui.values[0]);
                        $("#amount-max").val(ui.values[1]);
                    }
                });

                $("#amount-min").val($range.slider("values", 0));
                $("#amount-max").val($range.slider("values", 1));
            });

            $('.clear-search a').click(function() {
                var $range = $("#age-range");
                Search.search("*", $range.slider("values", 0), $range.slider("values", 1));
            });
        });
    </script>
</body>
</html>
