<?php
if((substr($this->request->here, -5)==="coach")):
    echo $this->element('coach_register');
else:
    echo $this->element('user_register');
endif;
?>