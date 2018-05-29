jQuery(document).ready(function($){

	$('.wpstitch-badge').hover(function() {
		$(this).find('.wpstitch-badge-info').stop(true, true).fadeIn(200);
	}, function() {
		$(this).find('.wpstitch-badge-info').stop(true, true).fadeOut(200);
	});

	$.post(ajaxurl, {

		action: 'wpstitch_api_refresh_feed'

	}, function(response){

		console.log('AJAX complete');
	
	});

});