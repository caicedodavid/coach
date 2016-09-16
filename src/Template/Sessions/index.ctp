<div class="page index">
    <div id="pagination-container">
    	<?php if(!$coach):
        	echo $this->element('Sessions/coach_list');
       	else:
       		echo $this->element('Sessions/user_list');
       		echo $sessions->coach;
       	endif;?>
    </div>
</div>