<?php
    $title = $this->fetch('title');
?>
<?php $this->start('banner') ?>
	<?php echo $this->element('banner', ['title' => $title]); ?>
<?php $this->end() ?>
<?php $this->start('background')?>
	<div class="ed_dashboard_wrapper ed_toppadder80 ed_bottompadder80">
<?php $this->end()?>

<div class="row">
	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
		<div class="ed_sidebar_wrapper">
			<?= $this->fetch('tabs')?>
		</div>
	</div>
	<div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
	<div class="ed_dashboard_tab">
		<div class="tab-content">
			<div class="tab-pane active" id="a">
				<div class="ed_dashboard_inner_tab">
					<div role="tabpanel">
						<?= $this->fetch('content') ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	</div>
</div>
