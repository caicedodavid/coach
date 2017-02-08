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
			height: 650,
			slotDuration: '00:30:00',
			slotLabelInterval: 30,
			slotLabelFormat: 'hh:mm a',
			allDayText: 'Your Session',	
			eventDurationEditable: false,
			eventOverlap: false,
			allDaySlot: false,
			events: <?php echo $events?>
	    })
	});
	</script>
<?php $this->end('bottomScript'); ?>