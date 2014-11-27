<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
    <title>Map</title>

    <link rel="stylesheet" href="/css/main.css" />
    <link rel="stylesheet" href="/css/jquery-ui.css" />

    <!-- jQuery -->
    <script src="/js/jquery.min.js"></script>
    <script src="/js/jquery-ui.min.js"></script>

    <!-- Google Maps -->
    <script src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>
    <script src="http://google-maps-utility-library-v3.googlecode.com/svn/trunk/markerclustererplus/src/markerclusterer.js"></script>
<!--    <script src="/js/markerclusterer.js"></script>-->

    <!-- Application -->
    <script src="/js/namespace.js"></script>
    <script src="/js/Atlas/Map.js"></script>
    <script src="/js/Atlas/SelectionPanel.js"></script>
    <script src="/js/Atlas/Marker/MarkerModel.js"></script>
    <script src="/js/Atlas/Marker/MarkerFactory.js"></script>
    <script src="/js/Atlas/Marker/MarkersStorage.js"></script>
    <script src="/js/Atlas/Search.js"></script>
    <script src="/js/TxtOverlay.js"></script>

    <!-- Bootstrap -->
    <script src="/js/bootstrap.js"></script>
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

    <div id="panel-back" class="panel"></div>
    <div class="ui-element" id="panel-wrapper">
        <div id="panel-search" class="panel">
            <h3 class="panel-header">Найденные образцы</h3>
            <div class="panel-ul-result">
                <ul></ul>
            </div>
        </div>
        <div id="panel-checked" class="panel">
            <h3 class="panel-header">Выделенные образцы</h3>
            <div class="panel-ul-result">
                <ul></ul>
            </div>
            <a href="javascript: Panel.clearSelectionPanel(); void(0);" class="remove-selected">Remove selected</a>
        </div>
    </div>

    <div id="buttons" class="ui-element">
        <div class="check-type inline btn btn-active" data-type="PCA">
            PCA
        </div>
        <div class="check-type inline btn" data-type="ALDER">
            ALDER
        </div>
    </div>

    <div id="map_canvas"></div>
</body>
</html>
