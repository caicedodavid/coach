<?= $this->Html->link(__d('Session', 'Accept'), ['plugin' => false,'action' =>'approveSession',$session->id, 'controller' => 'Sessions'],
	['class' => 'btn ed_btn pull-right ed_orange pull-right small','id' =>'accept','name'=>$session->id]);?>
<?= $this->Form->postLink(__('Decline'),['plugin' => false,'action' => 'rejectSession',$session->id, 'controller' => 'Sessions'], 
    ['class' => 'btn ed_btn ed_green pull-right small', 'method'=>'post','id' =>'decline','confirm' => __('Are you sure you want to reject this requested session?')]);?>