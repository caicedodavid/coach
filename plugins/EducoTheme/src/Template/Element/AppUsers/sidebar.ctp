 <div class="ed_sidebar_wrapper">
	<div class="ed_profile_img">
		<?php echo $this->Img->display($user['user_image'], 'medium', ['url' => ['action' => 'view', $user->id]]);?>
	</div>
	<h3><?= $user['full_name'];?></h3>
	<?= $this->Balance->display($user);?>
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
