(function($) {
	var WidgetwewSearchHandler = function($scope, $) {

		var $search = $scope;

		function wewAjaxSearch(e) {

			var $ajaxurl 		= $search.find('.wew-search-wrap').data('ajaxurl'),
				$searchResults 	= $search.find('.wew-search-results'),
				$loadingSpinner = $search.find('.wew-search-wrap .wew-ajax-loading'),
				$postType		= $search.find('.wew-ajax-search input.post-type').val();
				
			$.ajax({
				type: 'post',
				url	: $ajaxurl,
				data: {
				    action: 'wew_ajax_search',
				    search: e,
					post_type: $postType
			    },
				beforeSend: function() {
					$searchResults.slideUp(200);
					setTimeout(function() {
						$loadingSpinner.fadeIn(50);
					}, 150);
				},
				success: function(result) {
					if(result === 0 || result == '0') {
						result = '';
					} else {
						$searchResults.html(result);
					}
				},
				complete: function() {
					$loadingSpinner.fadeOut(200);
					setTimeout(function() {
						$searchResults.slideDown(400).addClass('filled');
					}, 200);
				}
			});

		}

	    $search.find('.wew-ajax-search input.field').on('keyup', function() {

			var $searchValue 		= $(this).val(),
				$lastSearchValue 	= '',
				$searchTimer 		= null;

			clearTimeout($searchTimer);

			if($lastSearchValue != $.trim($searchValue) && $searchValue.length >= 3) {
				$searchTimer = setTimeout(function() {
					wewAjaxSearch($searchValue);
				}, 400);
			}

		});

		$(document).on('click', function() {
			$('.wew-search-results.filled').slideUp(200);
		}).on('click', '.wew-ajax-search, .wew-search-results', function(e) {
		    e.stopPropagation();
		}).on('click', '.wew-ajax-search', function() {
		    $(this).parent().find('.wew-search-results.filled').slideDown(400);
		});

	};
	
	// Make sure we run this code under Elementor
	$(window).on('elementor/frontend/init', function() {
		elementorFrontend.hooks.addAction('frontend/element_ready/wew-search.default', WidgetwewSearchHandler);
	});
})(jQuery);