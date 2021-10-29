(function ($) {
	/**
	 * @param $scope The Widget wrapper element as a jQuery element
	 * @param $ The jQuery alias
	 */

	var WidgetOwlCarouselHandler = function ($scope, $) {
		var $carouselContainer = $scope.find(".js-owce-carousel-container");
		var $carousel = $carouselContainer.find(".js-owce-carousel");

		if (!$carousel.length) {
			return;
		}

		var $options = $carousel.data("options");

		// console.log($options);

		// $carousel.each(function () { // each not necessary
		$carousel.owlCarousel({
			// loop: $options.loop,
			// margin: $options.margin, // dont' delete
			// nav: $options.nav,
			// items: $options.items_count,
			// dots: $options.dots,
			lazyLoad: $options.lazyLoad,
			autoHeight: $options.auto_height,
			autoplay: $options.autoplay,
			autoplayTimeout: $options.autoplay_timeout
				? $options.autoplay_timeout
				: 5000,
			autoplayHoverPause: $options.autoplay_hover_pause,
			mouseDrag: $options.mouse_drag,
			touchDrag: $options.touch_drag,
			// rewind: true,
			smartSpeed: $options.smart_speed,
			// dotsEach: true,
			// dotsData: true,
			// slideBy: 1,
			// slideTransition: "ease",
			// startPosition: 2,
			// autoWidth: true, // dangerous to give this option
			// stagePadding: 15,
			// center: true,
			// animateOut: "slideOutDown",
			// animateIn: "flipInX",
			navText: [
				"<i class='eicon-chevron-left' aria-hidden='true'></i>",
				"<i class='eicon-chevron-right' aria-hidden='true'></i>"
			],
			responsiveClass: true,
			responsive: {
				0: {
					items: $options.items_count_mobile,
					margin: owce_value_exists(
						$options.margin_mobile,
						$options.margin
					),
					nav: $options.nav_mobile,
					dots: $options.dots_mobile,
					loop: $options.loop_mobile
				},
				768: {
					items: $options.items_count_tablet,
					margin: owce_value_exists(
						$options.margin_tablet,
						$options.margin
					),
					nav: $options.nav_tablet,
					dots: $options.dots_tablet,
					loop: $options.loop_tablet
				},
				1024: {
					items: $options.items_count,
					margin: $options.margin,
					nav: $options.nav,
					dots: $options.dots,
					loop: $options.loop
				}
			}
		});
		// }); // end each

		// $(".my-next-button").click(function () {
		// 	$carousel.trigger("next.owl.carousel");
		// });

		// $(".my-previous-button").click(function () {
		// 	$carousel.trigger("prev.owl.carousel");
		// });

		if ($(".js-elementor-not-clickable").length) {
			$(".js-elementor-not-clickable")
				.parent(".owl-thumb")
				.addClass("js-elementor-not-clickable");
		}
	};

	// Make sure you run this code under Elementor.
	$(window).on("elementor/frontend/init", function () {
		elementorFrontend.hooks.addAction(
			"frontend/element_ready/owl-carousel-elementor.default",
			WidgetOwlCarouselHandler
		);
	});

	// helpers
	function owce_value_exists(val, defaultVal = "") {
		if (val || val === 0) {
			return val;
		}
		return defaultVal;
	}
})(jQuery);
