<?php
if(($user["role"]==="coach") or (substr($this->request->referer(), -5)==="coach")):
    echo $this->element('coach_register');
else:
    echo $this->element('user_register');
endif;
?>