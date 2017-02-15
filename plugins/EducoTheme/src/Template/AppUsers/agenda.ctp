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
		if($events) {
			echo $this->Html->tag('div', null, ['id' => 'calendar']);
			echo $this->Html->tag('/div');
			echo $this->Html->tag('br');
    		
    	} else {
    		echo $this->Html->link('Please, Sync you calendar here', $url, ['class' => 'btn ed_btn ed_orange pull-left big']);
    	}
	?>
</div>

<?php $this->start('bottomScript'); ?>
	<?php echo $this->AssetCompress->script('EducoTheme.calendar');
		?>
	<script>
	$(document).ready(function() {

		function replaceTag(tag, id, text) {
    		var newItem = document.createElement(tag);
			newItem.setAttribute('id', id);
			var textnode = document.createTextNode(text);
			newItem.appendChild(textnode);
			$('#' + id).replaceWith(newItem);
		}

		function clickEvent(calEvent) {
    		if (calEvent.status == 'tentative') {
    			replaceTag('h2', 'session-title', calEvent.title);
    			var date = new Date(calEvent.start);
    			var string = date.toDateString() + ', ' + date.getHours() + ':' + (date.getMinutes()<10?'0':'') + date.getMinutes();
    			console.log(string);
    			replaceTag('h3', 'session-schedule', string);
				$("input[name='id']").val(calEvent.sessionId);
				$('#myModal').modal('show');
			}
		}
	    $('#calendar').fullCalendar({
	    	header: {
				left: 'prev,next today',
				center: 'title',
				right:''
			},
			timezone:  <?="'" . $timezone . "'"?>,
			defaultView: 'agendaWeek',
			editable: true,
			eventClick: function(calEvent, jsEvent, view) {
				clickEvent(calEvent);
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
<?= $this->element('AppUsers/agenda_modal')?>