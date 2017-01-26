<?php
	foreach($events as $event):
		echo 'START ' . $event->start;
		echo ' END '. $event->end;
		echo "<br>";
	endforeach;
?>
<div id='calendar'></div>
<?php $this->start('bottomScript'); ?>
	<script>
	$(document).ready(function() {
	    $('#calendar').fullCalendar({
	    	header: {
				left: 'prev,next today',
				center: 'title',
				right:'month'
			},
			defaultView: 'agendaWeek',
			editable: true,
			height: 650,
			slotDuration: '00:30:00',
			slotLabelInterval: 30,
			slotLabelFormat: 'h(:mm)a',
			allDayText: 'Your Session',
			defaultTimedEventDuration: '00:30:00',	
			eventDurationEditable: false,
			eventOverlap: false,
			events: [
			]
	    })
		var event = $('#calendar').fullCalendar( 'clientEvents',[1]);
    	var week = $('#calendar').fullCalendar('getCalendar').view.start._d;
    	var dateObj = moment(new Date(week)).add(1, 'days').format('YYYY-MM-DD');
	
    	$('#calendar').fullCalendar( 'renderEvent', {
    	  	id: 1,
    	  	title: 'Topic',
    	  	start: dateObj,
    	}, true );
	});
	</script>
<?php $this->end('bottomScript'); ?>
