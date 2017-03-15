<?php
$this->extend('/Element/Topics/form');
$this->assign('action', ['action' => 'coachTopics', $userAuth['id'], 'controller' => 'Topics']);
$this->assign('title', __('Add Topic'));
$this->assign('isFree', false);
$this->assign('price', '');
$this->assign('categoryIds', []);
?>
