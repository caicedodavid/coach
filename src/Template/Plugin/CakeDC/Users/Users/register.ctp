<?php
if((substr($this->request->here, -5)==="coach")):
    echo $this->element('AppUsers/coach_register');
else:
    echo $this->element('AppUsers/user_register');
endif;
?>