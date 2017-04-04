<?php
$this->Breadcrumbs->prepend([
    ['url' => ['action' => 'myProfile', 'controller' => 'AppUsers', 'plugin' => false], 'title' => __('Home')],
]);
$this->Breadcrumbs->templates([
    'wrapper' => '<ul class="breadcrumb">{{content}}</ul>',
    'item' => '<li><a href="{{url}}">{{title}}</a></li>{{separator}}',
]);
?>
<div class="ed_pagetitle" data-stellar-background-ratio="0.5" data-stellar-vertical-offset="0" style="background-image: url(http://placehold.it/921X533);">
    <div class="ed_img_overlay"></div>
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-4 col-sm-6">
                <div class="page_title">
                    <h2><?= $title?></h2>
                </div>
            </div>
            <div class="col-lg-6 col-md-8 col-sm-6">
                <?=$this->Breadcrumbs->render([],['separator' => '<li><i class="fa fa-chevron-left"></i></li>'])?>
            </div>
        </div>
    </div>
</div>