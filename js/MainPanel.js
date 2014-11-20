/**
 * Главная панель. В ней описание + графики
 * @constructor
 */
MainPanel = function() {

};

MainPanel.prototype.init = function () {
    this.hide();
};

MainPanel.prototype.show = function() {
    $('.main-panel').show();
};

MainPanel.prototype.hide = function() {
    $('.main-panel').hide();
};

/**
 * Грузит описание
 * @param Marker
 */
MainPanel.prototype.getDescription = function(Marker) {
    $.post('markers/description', {
        name: Marker.dbName
    }, function(response) {
        if (!response.status) {
            alert(response.message);
            return;
        }

        $('#main-panel .description').html(response.data).data('name', response.name);
    });

    this.show();
};

/**
 * Грузит описание
 */
MainPanel.prototype.saveDescription = function() {
    var $description = $('#main-panel .description'),
        name = $description.data('name'),
        text = $description.html();

    $.post('markers/description/save', {
        name: name,
        text: text
    }, function(response) {
        if (!response.status) {
            alert(response.message);
        } else {
            alert('Is saved');
        }
    });
};