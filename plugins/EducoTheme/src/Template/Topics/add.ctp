<?php
$this->extend('/Element/Topics/form');
$this->assign('action', 'coachTopics');
$this->assign('param' , $userAuth['id']);
$this->assign('title', __('Add Topic'));
$this->assign('isFree', false);
$this->assign('price', '');
$this->assign('categoryIds', '[]');
?>
