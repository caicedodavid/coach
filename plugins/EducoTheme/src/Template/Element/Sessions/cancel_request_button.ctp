<?= $this->Form->postLink(__('cancel'), ['plugin' => false, 'controller' => 'Sessions', 'action' => 'cancelRequest', $id], 
                            ['class' => 'btn ed_btn ed_green pull-right small', 'method'=>'post','id' =>'decline','confirm' => __('Are you sure you want to cancel this request?')]);?>
