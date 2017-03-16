<?php
$this->extend('/Element/Topics/form');
$this->assign('action', 'view');
$this->assign('param' , $topic->id);
$this->assign('title', __('Edit Topic'));
$this->assign('isFree', $topic->price === 0);
$this->assign('price', $topic->price === 0? '' : $topic->price);
$this->assign('categoryIds', json_encode($topicCategories));
?>