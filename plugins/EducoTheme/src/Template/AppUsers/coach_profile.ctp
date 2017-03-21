<?php
    use App\Controller\AppUsersController;
    $this->extend('/Element/AppUsers/profile');
    $this->assign('title', 'Coach');
    $this->assign('isCoach', $isCoach)
?>