 <div class="ed_sidebar_wrapper">
	<div class="ed_profile_img">
		<?php echo $this->Img->display($user['user_image'][0], 'medium');?>
	</div>
	<h3><?= $user['full_name'];?></h3>
	<?= $this->Balance->display($user);?>
	 <div class="ed_tabs_left">
		<ul class="nav nav-tabs">
			<?php
			 	foreach($tabs as $tab) {
			 		if ($tab['isAuthLink']) {
			 			if ($this->AuthLink->isAuthorized($tab['url'])) {
			 				echo $this->Html->tag('li', null, ["class" => $tab['isActive'] ? 'active' : null]);
			 				echo $this->AuthLink->link(__($tab['title']), array_merge($tab['url'],['data-toggle'=>"tab"]));
			 				echo $this->Html->tag('/li');
			 			}		
			 		} else {
			 			echo $this->Html->tag('li', null, ["class" => $tab['isActive'] ? 'active' : null]);
			 			echo $this->Html->link(__($tab['title']), array_merge($tab['url'],['data-toggle'=>"tab"]));
			 			echo $this->Html->tag('/li');		 			
			 		}
			 	}
			?>
		</ul>
	</div>
</div>			
