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