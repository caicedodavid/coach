$(function() {
   	$( ".datepicker" ).datepicker({
   		dateFormat: 'yy-mm-dd'
   	});
 });
$(function() {
	$('.timepicker').timepicker({
	    timeFormat: 'HH:mm',
	    interval: 30,
	    minTime: '00',
	    maxTime: '23:30',
	    defaultTime: '12',
	    startTime: '00',
	    dynamic: false,
	    dropdown: true,
	    scrollbar: true
	});
 });

$(document).ready(function() {
	$(document).on('click', '#pagination-button a', function () {
		var thisHref = $(this).attr('href');
		if (!thisHref) {
			return false;
		}
		$.get(thisHref, function(data) {
			var users = $(data).find('#users');
			var paging = $(data).find('div.paging');
			$('#users').append(users);
			$( "div.paging" ).replaceWith(paging);
		});
		return false;
	});
});

$(document).ready(function() {
	$('div.pagination').on('click','a', function () {
		var thisHref = $(this).attr('href');
		$('$ajaxContainer').load(thisHref, function() {
			$('html, body').animate({
				scrollTop: $('$ajaxContainer').offset().top
			}, 200);
		});
		return false;
	});
});

$.fn.stars = function() {
    return $(this).each(function() {
        // Get the value
        var val = parseFloat($(this).attr("data-rating"));
        // Make sure that the value is in 0 - 5 range, multiply to get width
        var size = Math.max(0, (Math.min(5, val))) * 16;
        // Create stars holder
        var $span = $('<span />').width(size);
        // Replace the numerical value with stars
        $(this).html($span);
    });
};

$(function() {
    $('span.stars').stars();
});