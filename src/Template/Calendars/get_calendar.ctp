<?php
	foreach($events as $event):
		echo 'START ' . $event->start;
		echo ' END '. $event->end;
		echo "<br>";
	endforeach;
?>