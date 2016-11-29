<?php 
    use App\Controller\AppUsersController;
    $this->extend('/Element/AppUsers/dashbord_sidebar');
    $this->assign('title', 'Coach');
?>
<?php $this->start('tabs') ?>
    <?= $this->element('AppUsers/sidebar',$this->Sidebar->tabs($user, AppUsersController::PROFILE_TABS_TOPICS))?>
<?php $this->end('tabs') ?>

<ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#view" aria-controls="view" role="tab" data-toggle="tab">Topics</a></li>
</ul>
<div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="my">
    <div class="ed_inner_dashboard_info">
        <h2><?= $user->first_name . '\'s Topics'?></h2>
        <div class="row">
            <div class="ed_mostrecomeded_course_slider">
                <?php if (!$topics->count()):?>
                    <div class="alert alert-info"><?= __('There are no topics')?></div>
                <?php else: ?>
                    <div id="pagination-container">
                        <?php foreach ($topics as $topic): ?>
                            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 ed_bottompadder20">
                                <div class="ed_item_img">
                                    <?php echo $this->Img->displayImage($topic['topic_image'], 'medium-small',['url' => ['controller' => 'Topics', 'plugin' => false, 'action' => 'view', $topic->id]]);?>
                                </div>
                                <div class="ed_item_description ed_most_recomended_data">
                                    <h4><a href="course_single.html">
                                        <?= $this->Html->link(__($topic->name), ['controller' => 'Topics', 'plugin' => false, 'action' => 'view', $topic->id]) ?>
                                    </a></h4>
                                    <p><?= $topic->description ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                         <div class="paginator ed_blog_bottom_pagination">
                            <nav>
                                <?php echo $this->element('classic_pagination'); ?>
                            </nav>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?= $this->AuthLink->link(__('Add Topic'), ['plugin' => false,'action' =>'add', $user['id'], 'controller' => 'Topics'], ['class' => 'btn ed_btn pull-right ed_orange pull-right small']) ?>
    </div>
    </div>
</div>

