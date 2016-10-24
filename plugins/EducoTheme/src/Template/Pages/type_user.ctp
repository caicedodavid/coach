<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <?php
        echo $this->Html->link(__d('AppUsers', 'Register for coaches'), ['plugin' => false,'action' => 'register','coach','controller' => 'AppUsers'],['class' => 'btn ed_btn ed_orange','id' =>'coach-register']);
        echo $this->Html->link(__d('AppUsers', 'Register for coachees'),['plugin' => false,'action' => 'register','user','controller' => 'AppUsers'], ['class' => 'btn ed_btn ed_orange','id' =>'user-register']);
        ?>
    </div>
</div>