Atlas.SelectionPanel = function() {
    this.CurrentPopulations = [];
    this.CheckedPopulations = {};
};


/* ****************************
 * SEARCH PANEL
 * ****************************
 */

/**
 * Добавляет маркеры в панель поиска
 * @param Markers
 */
Atlas.SelectionPanel.prototype.addToSearchPanel = function(Markers) {
    var t = this;
    t.CurrentPopulations = t.calcPopulation(Markers);
    t.showPopulations(t.CurrentPopulations);
};

/* ****************************
 * SELECTION PANEL
 * ****************************
 */

/**
 * Добавлят маркеры в панель "выделенного"
 */
Atlas.SelectionPanel.prototype.checkMarker = function(populationName, markerId) {
    var t = this;
    var marker  = t.CurrentPopulations[populationName].markers[markerId];
    var $marker = $('.marker-element[data-id=' + markerId + ']');

    $('a.add-marker', $marker).hide();
    if (t.CheckedPopulations[populationName] === undefined) {
        t.CheckedPopulations[populationName] = [];
    }

    t.CheckedPopulations[populationName][marker.id] = marker;
    t.CurrentPopulations[populationName].markers[markerId].check = true;

    t.showCheckedPanel();
};

Atlas.SelectionPanel.prototype.showCheckedPanel = function() {
    var t = this;
    var $checkedPanel = $('#panel-checked ul');
    $checkedPanel.html('');

    for (var population in t.CheckedPopulations) {
        var markers = '<ul class="checked-population-markers" data-population="' + population + '">';
        var sizeOfChecked = 0;
        for (var markerIndex in t.CheckedPopulations[population]) {
            marker         = t.CheckedPopulations[population][markerIndex];
            var markerData = marker.data;

            markers += '<li class="marker-element" data-id="' + marker.id + '">';
            markers += markerData.population_id + ' ';
            if (markerData.age) {
                markers += '[' + markerData.age + ']';
            } else {
                markers += '[' + markerData.age_from + ' - ' + markerData.age_to + ']';
            }

            markers += '</li>';
            sizeOfChecked++;
        }
        markers += '</ul>';

        var jsEvent = "$('.checked-population-markers[data-population=" + population + "]').toggle();";
        var selectAlder = '';

        selectAlder = '<select class="alder-select inline" style="' + (CurrentTypeAnalyze == TYPE_ALDER ? '' : 'display: none;') +' ">';
        selectAlder += '<option>None</option>';
        selectAlder += '<option>Ref1</option>';
        selectAlder += '<option>Ref2</option>';
        selectAlder += '<option>Test</option>';
        selectAlder += '</select> ';

        $checkedPanel.append(
            '<li class="population-wrapper" data-population="' + population + '">' +
                selectAlder +
                '<a class="inline" href="javascript: ' + jsEvent +  '  void(0);" class="toggle-markers">' + (population) + '</a> '+
                ' (' + (sizeOfChecked) + ') ' +
                markers +
            '</li>'
        );
    }
};

Atlas.SelectionPanel.prototype.checkPopulation = function(populationName) {
    var t = this;

    var markers = t.CurrentPopulations[populationName].markers;
    var $populationWrapper = $('li.population-wrapper[data-population="' + populationName + '"]');

    markers.map(function(marker) {
        t.checkMarker(populationName, marker.id);
    });

    $('a.add-population', $populationWrapper).hide();
};

/**
 * Очищает панель выделенного
 */
Atlas.SelectionPanel.prototype.clearSelectionPanel = function() {
    var t = this;
    var $checkedPanel = $('#panel-checked ul');
    $checkedPanel.html('');
    t.CheckedPopulations = [];
};

/**
 * Вычисляет из массива маркеров, массив популяций
 * @param markers
 * @returns {{}}
 */
Atlas.SelectionPanel.prototype.calcPopulation = function(markers) {
    var populations = {};
    var population;
    var markerData;

    for (var i = 0; i < markers.length; i++ ){
        markerData = markers[i].data;
        if (markerData.population_id === undefined) {
            continue;
        }

        population = markerData.population_id;

        if (populations[population] === undefined) {
            var markersTemp = [];
            markersTemp[markers[i].id] = markers[i];

            populations[population] = {
                total   : 1,
                markers : markersTemp,
                min_age : markerData.age ? markerData.age : markerData.age_from,
                max_age : markerData.age ? markerData.age : markerData.age_to
            };
        } else {
            populations[population].total ++;
            populations[population].markers[markers[i].id] = markers[i];

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

/**
 * Отображение панели популяций
 * @param populations
 */
Atlas.SelectionPanel.prototype.showPopulations = function(populations) {
    var ul = $('#panel-search ul');
    var markers;
    var markerData;
    var marker;

    ul.html('');

    for (var population in populations) {
        markers = '<ul class="population-markers" data-population="' + population + '">';
        for (var markerIndex in populations[population].markers) {
            marker     = populations[population].markers[markerIndex];
            markerData = marker.data;

            markers += '<li class="marker-element" data-id="' + marker.id + '">';
            markers += markerData.population_id + ' ';
            if (markerData.age) {
                markers += '[' + markerData.age + ']';
            } else {
                markers += '[' + markerData.age_from + ' - ' + markerData.age_to + ']';
            }

            var jsAddMarker = 'javascript: Panel.checkMarker(\'' + population + '\', ' + marker.id + '); void(0);';
            markers += ' <a href="' + jsAddMarker + '" class="add-marker link-no-line">+</a>';
            markers += '</li>';
        }
        markers += '</ul>';

        var jsEvent = "$('.population-markers[data-population=" + population + "]').toggle();";
        var jsAddPopulation = 'javascript: Panel.checkPopulation(\'' + population + '\'); void(0);';

        ul.append(
            '<li class="population-wrapper" data-population="' + population + '">' +
                '<a href="javascript: ' + jsEvent +  '  void(0);" class="toggle-markers">' + (population) + '</a>' +
                ' (' + (populations[population].total) + ')' +
                ' [' + (populations[population].min_age) + ' - ' + (populations[population].max_age) + '] ' +
                '<a href="' + jsAddPopulation + '" class="add-population link-no-line">+</a>'+
                markers +
            '</li>'
        );
    }
};

