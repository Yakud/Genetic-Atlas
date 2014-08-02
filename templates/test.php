<div>
    <div>
        <span>Возраст:</span>
        <span>от </span>
        <input type="text" name="age-from" id="age-from"/>
        <span>до</span>
        <input type="text" name="age-to" id="age-to"/>
        <span>лет назад</span>
    </div>

    <label for="amount">Возраст:</label>
    <input type="text" id="amount" readonly style="border:0; color:#f6931f; font-weight:bold;">
</div>

<div id="slider-age"></div>
<script>
    var SliderAge = {
        $element: $("#slider-age"),
        $inputAgeFrom: $("#age-from"),
        $inputAgeTo: $("#age-to"),

        init: function() {
            this.$element.slider({
                range: true,
                min: 0,
                max: 10000,
                values: [0, 10000],
                slide: this.eventChangeSlider
            });

            this.$inputAgeFrom.keyup(this.eventChangeInput);
            this.$inputAgeTo.keyup(this.eventChangeInput);

            this.eventChangeSlider();
        },

        eventChangeInput: function() {
            var values = [
                SliderAge.$inputAgeFrom.val(),
                SliderAge.$inputAgeTo.val(),
            ];

            SliderAge.$element.slider("values", values);
        },

        eventChangeSlider: function() {
            var values = SliderAge.$element.slider("values");

            SliderAge.$inputAgeFrom.val(values[0]);
            SliderAge.$inputAgeTo.val(values[1]);
        }
    };

    $(function() {
        SliderAge.init();
    });
</script>