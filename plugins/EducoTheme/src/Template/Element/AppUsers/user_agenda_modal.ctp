<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h1 class="modal-title"><?= __('Pending Session') ?></h1>
            </div>
            <div class="modal-body">
            	<h2><a href="#" id="session-title"></a></h2>
                <div hidden><h3 id="session-user"></h3></div>
                <h3 id="session-coach"></h3>
            	<h3 id="session-schedule"></h3>
            </div>
            <div class="modal-footer">
               	<?= $this->Form->create(null, ['url'=> ['controller'=>'Sessions', 'action'=>'cancelRequestCalendar']]);?>
            		<?= $this->Form->hidden('id', ["id" => "session-id"]);?>
                	<?= $this->Form->unlockField('id');?>
    				<?= $this->Form->button(__('Cancel Request'), ['class' => 'btn ed_btn ed_green pull-right small', 'method' => 'post']);?>
    			<?= $this->Form->end() ?>
            </div>
        </div>
    </div> 
</div>