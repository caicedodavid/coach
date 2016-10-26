<?php
 	foreach($tabs as $tabTitle => $content) {
 		echo "<li class=" . $content[0] . ">";
 		echo $this->AuthLink->link(__($tabTitle), array_merge($content[1],['data-toggle'=>"tab"]));
 		echo "</li>";
 	}
 ?>
