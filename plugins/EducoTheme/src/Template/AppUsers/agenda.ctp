<?php $this->start('headCss'); ?>
	<?php echo $this->AssetCompress->css('EducoTheme.calendar');?>
	<?php echo $this->AssetCompress->css('EducoTheme.printCalendar', ['media' => 'print']);?>
<?php $this->end('headCss'); ?>
<?php
    use App\Controller\AppUsersController;
    $this->extend('/Element/AppUsers/dashbord_sidebar');
    $this->assign('title', 'Coach');
?>
<?php $this->start('tabs') ?>
    <?= $this->element('AppUsers/sidebar',$this->Sidebar->tabs($user, AppUsersController::PROFILE_TABS_AGENDA))?>
<?php $this->end('tabs') ?>
<ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#view" aria-controls="view" role="tab" data-toggle="tab"><?=__('Agenda')?></a></li>
</ul>
<div class="tab-content calendar">
	<?php
		echo $this->Html->tag('div', null, ['id' => 'calendar']);
		echo $this->Html->tag('/div');
		echo $this->Html->tag('br');
    	echo $this->Html->link('click here',$url);
	?>
</div>

<?php $this->start('bottomScript'); ?>
	<?php echo $this->AssetCompress->script('EducoTheme.calendar');
		?>
	<script>
	$(document).ready(function() {
	    $('#calendar').fullCalendar({
	    	header: {
				left: 'prev,next today',
				center: 'title',
				right:''
			},
			timezone: moment.tz.guess(),
			defaultView: 'agendaWeek',
			editable: true,
			eventClick: function(calEvent, jsEvent, view) {
				if (calEvent.status == 'tentative') {
					var newItem = document.createElement("h2");
					newItem.setAttribute('id', 'session-title');
					var textnode = document.createTextNode(calEvent.title);
					newItem.appendChild(textnode);
					$("#session-title").replaceWith(newItem);
					var newItem = document.createElement("h3");
					newItem.setAttribute('id', 'session-schedule');
					var date = new Date(calEvent.start);
					var textnode = document.createTextNode(date.toDateString() + ', ' + date.getHours() + ':' + date.getMinutes());
					newItem.appendChild(textnode);
					$("#session-schedule").replaceWith(newItem);
					$("input[name='id']").val(calEvent.sessionId);
					$('#myModal').modal('show');
				}
    		},
			height: 650,
			slotDuration: '00:30:00',
			slotLabelInterval: 30,
			slotLabelFormat: 'hh:mm a',
			allDayText: 'Your Session',	
			eventDurationEditable: false,
			eventOverlap: false,
			allDaySlot: false,
			events: <?=$events?>
	    })
	});
	</script>
<?php $this->end('bottomScript'); ?>

<div id="myModal" class="modal fade" role="dialog">
	
    	<div class="modal-dialog">
    	    <div class="modal-content">
    	        <div class="modal-header">
    	            <button type="button" class="close" data-dismiss="modal">&times;</button>
    	            <h1 class="modal-title"><?= __('Pending Session') ?></h1>
    	        </div>
    	        <div class="modal-body">
    	        	<h2 id="session-title"></h2>
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