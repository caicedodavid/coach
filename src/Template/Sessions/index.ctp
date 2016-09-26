<div class="page index">
    <?php if(!$coach):
    	echo $this->element('Sessions/coach_list');
    else:
    	echo $this->element('Sessions/user_list');
    endif;?>
</div>