<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h1 class="modal-title"><?= __('Pending Session') ?></h1>
            </div>
            <div class="modal-body">
            	<h2><a href="www.google.com" id="session-title">gues</a></h2>
                <h3 id="session-user"></h3>
            	<h3 id="session-schedule"></h3>
            </div>
            <div class="modal-footer">
               	<?= $this->Form->create(null, ['url'=> ['controller'=>'Sessions', 'action'=>'calendarRequestSession'], 'id' => 'payment-form']);?>
            		<?= $this->Form->hidden('id', ["id" => "session-id"]);?>
                	<?= $this->Form->unlockField('id');?>
                	<?= $this->Form->hidden('method');?>
                	<?= $this->Form->unlockField('id');?>
                	<?= $this->Form->unlockField('method');?>
                	<?= $this->Form->button(__('Accept'), ['class' => 'btn ed_btn ed_green pull-right small', 'method' => 'post', 'id' => 'acceptButton']);?>
    				<?= $this->Form->button(__('Decline'), ['class' => 'btn ed_btn ed_green pull-right small', 'method' => 'post', 'id' => 'rejectButton']);?>
    			<?= $this->Form->end() ?>
            </div>
        </div>
    </div> 
</div>