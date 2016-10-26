<?php
    $title = $this->fetch('title');
    $tabs = $this->fetch->('tabs');
    $image = $this->fetch->('image');
    $name = $this->fetch->('name');
?>
<div class="sessions">
    <h3><?= __('Your Sessions') ?></h3>
    <div class="container">
        <ul class="nav nav-tabs">
            <?php
            echo "<li class=" . $class1 . ">";
            echo $this->AuthLink->link(__('Upcomming'), ['action' => 'approved','controller' => 'Sessions', 'data-toggle'=>"tab"]);
            echo "</li>";
            echo "<li class=" . $class2 . ">";
            echo $this->AuthLink->link(__('Pending'), ['action' => 'pending','controller' => 'Sessions', 'data-toggle'=>"tab"]);
            echo "</li>";
            echo "<li class=" . $class3 . ">";
            echo $this->AuthLink->link(__('Historic'), ['action' => 'historic','controller' => 'Sessions', 'data-toggle'=>"tab"]);
            echo "</li>";
            ?>
        </ul>
        <?= $this->fetch('content') ?>
    </div>
</div>

<div class="ed_pagetitle" data-stellar-background-ratio="0.5" data-stellar-vertical-offset="0" style="background-image: url(http://placehold.it/921X533);">
<div class="ed_img_overlay"></div>
	<div class="container">
		<div class="row">
			<div class="col-lg-6 col-md-4 col-sm-6">
				<div class="page_title">
					<h2><?= $title;?></h2>
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
		<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
			<div class="ed_sidebar_wrapper">
				<div class="ed_profile_img">
					<?php echo $this->Img->display($user['user_image'], 'medium', ['url' => ['action' => 'view', $user->id]]);?>
				</div>
				<h3><?= $name;?></h3>
				 <div class="ed_tabs_left">
					<ul class="nav nav-tabs">
						<?php
						 	foreach($tabs as $tabTitle => $content) {
						 		echo "<li class=" . $content[0] . ">";
						 		echo $this->AuthLink->link(__($tabTitle), array_merge($content[1],['data-toggle'=>"tab"]));
						 		echo "</li>";
						 	}
						?>
					</ul>
				</div>
			</div>
		</div>
	<div>
<div>
active