<?php
	if (!$events){
		echo $this->Html->link(__('Create Calendar'), ['controller' => 'calendars', 'action' => 'createCalendar'], ['class' => 'btn btn-default']);
		echo $this->Html->link(__('Sync Google Calenadar'), ['controller' => 'calendars', 'action' => 'getToken'], ['class' => 'btn btn-default']);
	} else {
		foreach($events as $event):
			echo $event->getSummary();
			echo ' '.$event->start->dateTime;
			echo "<br>";
		endforeach;
	}
	echo $this->Html->link(__('Create Event'), ['controller' => 'calendars', 'action' => 'createEvent'], ['class' => 'btn btn-default']);
?>