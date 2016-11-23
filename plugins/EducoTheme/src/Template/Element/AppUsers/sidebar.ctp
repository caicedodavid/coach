 <div class="ed_sidebar_wrapper">
	<div class="ed_profile_img">
		<?php echo $this->Img->display($user['user_image'], 'medium');?>
	</div>
	<h3><?= $user['full_name'];?></h3>
	<?= $this->Balance->display($user);?>
	 <div class="ed_tabs_left">
		<ul class="nav nav-tabs">
			<?php
			 	foreach($tabs as $tabTitle => $content) {
			 		if ($content[2]) {
			 			echo $this->Html->tag('li', null, ["class" => $content[0]]);
			 			echo $this->Html->link(__($tabTitle), array_merge($content[1],['data-toggle'=>"tab"]));
			 			echo $this->Html->tag('/li');
			 		} else {
			 			if ($this->AuthLink->isAuthorized($content[1])) {
			 				echo $this->Html->tag('li', null, ["class" => $content[0]]);
			 				echo $this->AuthLink->link(__($tabTitle), array_merge($content[1],['data-toggle'=>"tab"]));
			 				echo $this->Html->tag('/li');
			 			}
			 		}
			 	}
			?>
		</ul>
	</div>
</div>			
