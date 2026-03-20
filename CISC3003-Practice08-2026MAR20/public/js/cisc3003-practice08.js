$(function () {
    const defaults = {
        imageSrc: "images/medium/painting1.jpg",
        title: "Still Life with Flowers in a Glass Vase",
        artist: "Jan Davidsz. de Heem, 1650 - 1683",
        opacity: 100,
        saturation: 100,
        brightness: 100,
        hue: 0,
        gray: 0,
        blur: 0
    };

    const $mainImage = $("#mainImage");

    function updateCaption(title, artist) {
        $("#captionTitle").text(title);
        $("#captionArtist").text(artist);
    }

    function updateFigure(imageSrc, title, artist) {
        $mainImage.attr({
            src: imageSrc,
            alt: title,
            title: artist
        });
        updateCaption(title, artist);
    }

    function updateSliderNumbers() {
        $("#numOpacity").text($("#sliderOpacity").val());
        $("#numSaturation").text($("#sliderSaturation").val());
        $("#numBrightness").text($("#sliderBrightness").val());
        $("#numHue").text($("#sliderHue").val());
        $("#numGray").text($("#sliderGray").val());
        $("#numBlur").text($("#sliderBlur").val());
    }

    function isDefaultFilterState() {
        return $("#sliderOpacity").val() === String(defaults.opacity) &&
            $("#sliderSaturation").val() === String(defaults.saturation) &&
            $("#sliderBrightness").val() === String(defaults.brightness) &&
            $("#sliderHue").val() === String(defaults.hue) &&
            $("#sliderGray").val() === String(defaults.gray) &&
            $("#sliderBlur").val() === String(defaults.blur);
    }

    function buildFilterValue() {
        if (isDefaultFilterState()) {
            return "none";
        }

        return "opacity(" + $("#sliderOpacity").val() + "%) " +
            "saturate(" + $("#sliderSaturation").val() + "%) " +
            "brightness(" + $("#sliderBrightness").val() + "%) " +
            "hue-rotate(" + $("#sliderHue").val() + "deg) " +
            "grayscale(" + $("#sliderGray").val() + "%) " +
            "blur(" + $("#sliderBlur").val() + "px)";
    }

    function applyFilters() {
        const filterValue = buildFilterValue();
        $mainImage.css("filter", filterValue);
        $mainImage.css("-webkit-filter", filterValue);
    }

    function resetControls() {
        $("#sliderOpacity").val(defaults.opacity);
        $("#sliderSaturation").val(defaults.saturation);
        $("#sliderBrightness").val(defaults.brightness);
        $("#sliderHue").val(defaults.hue);
        $("#sliderGray").val(defaults.gray);
        $("#sliderBlur").val(defaults.blur);
        updateSliderNumbers();
        applyFilters();
    }

    $("#thumbBox img").on("click", function () {
        const $thumbnail = $(this);
        const mediumImage = $thumbnail.attr("src").replace("/small/", "/medium/");
        updateFigure(mediumImage, $thumbnail.attr("alt"), $thumbnail.attr("title"));
    });

    $(".sliders").on("input", function () {
        updateSliderNumbers();
        applyFilters();
    });

    $("#resetFilters").on("click", function () {
        updateFigure(defaults.imageSrc, defaults.title, defaults.artist);
        resetControls();
    });

    resetControls();
});
