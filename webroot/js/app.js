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

$.fn.stars = function() {
    return $(this).each(function() {
        // Get the value
        var val = parseFloat($(this).html());
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