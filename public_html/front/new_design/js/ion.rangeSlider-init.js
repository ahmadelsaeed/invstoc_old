// Ion.RangeSlider
// version 2.2.0 Build: 380
// © Denis Ineshin, 2017
// https://github.com/IonDen
//
// Project page:    http://ionden.com/a/plugins/ion.rangeSlider/en.html
// GitHub page:     https://github.com/IonDen/ion.rangeSlider
//
// Released under MIT licence:
// http://ionden.com/a/plugins/licence-en.html

CRUMINA.rangeSlider = function () {
	$(".range-slider-js").ionRangeSlider({
			type: "double",
			grid: true,
			min: 0,
			max: 1000,
			from: 200,
			to: 800,
			prefix: "$"
		}
	);
};

$(document).ready(function () {
	CRUMINA.rangeSlider();
});