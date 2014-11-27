const TYPE_PCA   = 'PCA';
const TYPE_ALDER = 'ALDER';

var MarkerModel;
var Map;
var MarkerStorage;
var Panel;
var Search;
var CurrentTypeAnalyze = TYPE_PCA;

$(function() {
    MarkerModel   = Atlas.Marker.MarkerModel;
    Map           = new Atlas.Map('map_canvas');
    MarkerStorage = new Atlas.Marker.MarkerStorage();
    Panel         = new Atlas.SelectionPanel();
    Search        = new Atlas.Search(MarkerStorage, Map, Panel);

    Search.onSearchEvent = function(Markers) {
        var text = "Find " + (Markers.length) + " markers";
        $('#search-result').text(text);
    };

    /**
     * todo Включить, если нужно загружать маркеры в самом начале
    MarkerStorage.getMarkers(function(Markers) {
        Markers.map(function(Marker) {
            Map.addMarker(Marker);
        });

        Map.reindexMarkers();
    });
    */

    $("#fulltext-search").keyup(function(event){
        if(event.keyCode == 13){
            var $range = $("#age-range");
            Search.search({
                text    : $(this).val(),
                ageFrom : $range.slider("values", 0),
                ageTo   : $range.slider("values", 1)
            });
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
        $('#fulltext-search').val('');
        Search.search({
            text    : "*",
            ageFrom : $range.slider("values", 0),
            ageTo   : $range.slider("values", 1)
        });
    });

    $('.btn.check-type').click(function() {
        var $this = $(this);

        CurrentTypeAnalyze = $this.data('type');

        if (CurrentTypeAnalyze == TYPE_ALDER) {
            $('.alder-select').show();
        } else {
            $('.alder-select').hide();
        }

        $('.btn.check-type').removeClass('btn-active');
        $this.addClass('btn-active');
    });
});