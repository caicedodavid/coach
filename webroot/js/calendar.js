$(document).ready(function(){
	$(document).on("click", ".fc-left", function () {
		var event = $('#calendar').fullCalendar( 'clientEvents',[1]);
		var week = $('#calendar').fullCalendar('getCalendar').view.start._d;
		var dateObj = moment(new Date(week)).add(1, 'days').format('YYYY-MM-DD');

		$('#calendar').fullCalendar( 'removeEvents', [1] );

		$('#calendar').fullCalendar( 'renderEvent', {
			id: 1,
			title: event[0].title,
			start: dateObj,
		}, true );
	});

	$('#sessionSubmit').click(function() {

		var event = $('#calendar').fullCalendar('clientEvents',[1]);
		var allDay = event[0]['_allDay'];
		var date = moment(new Date(event[0].start["_d"]));
		var date2 = date.tz('UTC').format();
		$("input[name='startTime']").val(date2);
		$("input[name='isSelected']").val(+ !allDay);
	});
});