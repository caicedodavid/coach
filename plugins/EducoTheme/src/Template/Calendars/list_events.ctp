<?php $this->start('headCss'); ?>
	<?php echo $this->AssetCompress->css('EducoTheme.calendar');?>
	<?php echo $this->AssetCompress->css('EducoTheme.printCalendar', ['media' => 'print']);?>
<?php $this->end('headCss'); ?>
<?php
	if (!$events){
		echo $this->Html->link(__('Create Calendar'), ['controller' => 'calendars', 'action' => 'createCalendar'], ['class' => 'btn btn-default']);
		echo $this->Html->link(__('Sync Google Calendar'), ['controller' => 'calendars', 'action' => 'getToken'], ['class' => 'btn btn-default']);
		echo $this->Html->link(__('Create Event'), '#', ['class' => 'btn btn-default']);
		
	} else {
		echo $this->Form->create(null, ['url' => ['controller' => 'Calendars', 'action' => 'createEvent']]);
		echo $this->Form->hidden('startTime');
		echo $this->Form->unlockField('startTime');
		echo $this->Form->hidden('isSelected');
		echo $this->Form->unlockField('isSelected');
		echo $this->Html->tag('div', null, ['id' => 'calendar']);
		echo $this->Html->tag('/div');
		echo $this->Html->tag('br');
		echo $this->Form->submit(null,['id' => 'sessionSubmit', 'class' => 'btn btn-default']);
		echo $this->Html->link(__('TEST'), '#', ['id' => 'sessionSubmi', 'class' => 'btn btn-default']);
	}
	
?>
<?php $this->start('bottomScript'); ?>
	<?php echo $this->AssetCompress->script('EducoTheme.calendar');
	 	  echo $this->Html->script('calendar'); ?>
	<script>
	$(document).ready(function() {
	    $('#calendar').fullCalendar({
	    	header: {
				left: 'prev,next today',
				center: 'title',
				right:''
			},
			timezone: 'UTC',
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
			events: <?=$events?>
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