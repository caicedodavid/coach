<?php
    use App\Controller\AppUsersController;
    $this->extend('/Element/AppUsers/dashbord_sidebar');
    $this->assign('title', 'Coach');
?>
<?php $this->start('tabs') ?>
    <?= $this->element('AppUsers/sidebar',$this->Sidebar->tabs($user, AppUsersController::PROFILE_TABS_PROFILE))?>
<?php $this->end('tabs') ?>
<ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#view" aria-controls="view" role="tab" data-toggle="tab"><?=__('Agenda')?></a></li>
</ul>
<div class="tab-content">
	<?=
		$this->Html->tag('div', null, ['id' => 'calendar']);
		$this->Html->tag('/div');
		$this->Html->tag('br');
    	$this->Html->link('click here',$url) ?>
</div>

<?php $this->start('bottomScript'); ?>
	<?php echo $this->AssetCompress->script('EducoTheme.calendar');
	 	  echo $this->Html->script('calendar'); ?>
	<script>
	$(document).ready(function() {
	    $('#calendar').fullCalendar({
	    	header: {
				left: 'prev,next today',
				center: 'title',
				right:''
			},
			timezone: 'UTC',
			defaultView: 'agendaWeek',
			editable: true,
			height: 650,
			slotDuration: '00:30:00',
			slotLabelInterval: 30,
			slotLabelFormat: 'h(:mm)a',
			allDayText: 'Your Session',	
			eventDurationEditable: false,
			eventOverlap: false,
			allDaySlot: false,
			events: <?=$events?>
	    })
	});
	</script>
<?php $this->end('bottomScript'); ?>