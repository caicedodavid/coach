<?php
    use App\Controller\AppUsersController;
    $this->extend('/Element/AppUsers/profile');
    $this->assign('title', 'Coachee');
    $this->assign('isCoach', true);
?>