<?php
    $title = $this->fetch('title');
    $tabs = json_decode($this->fetch('tabs'),true);
    $image = $this->fetch('image');
    $name = $this->fetch('name');
?>
<div class="ed_pagetitle" data-stellar-background-ratio="0.5" data-stellar-vertical-offset="0" style="background-image: url(http://placehold.it/921X533);">
<div class="ed_img_overlay"></div>
	<div class="container">
		<div class="row">
			<div class="col-lg-6 col-md-4 col-sm-6">
				<div class="page_title">
					<h2><?= 'Educo' . $title;?></h2>
				</div>
			</div>
			<div class="col-lg-6 col-md-8 col-sm-6">
				<ul class="breadcrumb">
					<li><a href="index.html">home</a></li>
					<li><i class="fa fa-chevron-left"></i></li>
					<li><a href="instructor.html">Educo Instructor</a></li>
				</ul>
			</div>
		</div>
	</div>
</div>
<div class="ed_dashboard_wrapper">
	<div class="container">
		<div class="row">
			<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
				<div class="ed_sidebar_wrapper">
					<div class="ed_profile_img">
						<?php echo $this->Img->display($user['user_image'], 'medium', ['url' => ['action' => 'view', $user->id]]);?>
					</div>
					<h3><?= $name;?></h3>
					 <div class="ed_tabs_left">
						<ul class="nav nav-tabs">
							<?= $this->fetch('tabs')?>
						</ul>
					</div>
				</div>
			</div>
			<div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
			<div class="ed_dashboard_tab">
				<div class="tab-content">
					<div class="tab-pane active" id="a">
						<div class="ed_dashboard_inner_tab">
							<div role="tabpanel">
								<?= $this->fetch('content') ?>
							<div>
						</div>
					</div>
				</div>
			</div>
			</div>
		</div>
	</div>
</div>