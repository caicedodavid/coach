<center>
<?php
echo $this->Html->link(__d('AppUsers', 'Register for coaches'), ['plugin' => false,'action' => 'register','coach','controller' => 'AppUsers'],['class' => 'buttonr','id' =>'coach-register', 'method'=>'get']);
echo $this->Html->link(__d('AppUsers', 'Register for coachees'),['plugin' => false,'action' => 'register','user','controller' => 'AppUsers'], ['class' => 'buttonr','id' =>'user-register', 'method'=>'get']);
?>
</center>