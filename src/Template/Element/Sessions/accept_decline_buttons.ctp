<?= $this->Html->link(__d('Session', 'Accept'), ['plugin' => false,'action' =>'approveSession',$session->id, 'controller' => 'Sessions'],
	['class' => 'buttonS','id' =>'accept','name'=>$session->id]);?>
<?= $this->Form->postLink(__('Decline'),['plugin' => false,'action' => 'rejectSession',$session->id, 'controller' => 'Sessions'], 
    ['class' => 'buttonS', 'method'=>'post','id' =>'decline','confirm' => __('Are you sure you want to reject this requested session?')]);?>