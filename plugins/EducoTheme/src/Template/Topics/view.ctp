<?php $this->start('banner') ?>
    <?php echo $this->element('banner', ['title' => __('Topic')]); ?>
<?php $this->end() ?>
<?php $this->assign('customBackgroundClass','ed_course_single')?>
<div class="row">
    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
        <div class="ed_course_single_item">
            <div class="ed_course_single_image">
                <?php echo $this->Img->displayImage($topic['topic_image'], 'big');?>
            </div>
            <div class="ed_course_single_info">
                <h2><?= h($topic->name) ?><span><?= $this->Number->currency($topic->price, 'USD');?></span></h2>
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">
                        <div class="course_detail">
                            <div class="course_faculty">
                                <?php echo $this->Img->display($topic->coach['user_image'], 'extra-small', ['url' => ['action' => 'coachProfile', 'controller' => 'AppUsers', $topic->coach['id']]]);?><?= $this->Html->link(__($topic->coach['full_name']), ['action' => 'coachProfile', $topic->coach['id'], 'controller' => 'AppUsers']) ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6 pull-right text-right">
                        <div class="ed_course_duration">
                        <?php echo __('duration: {0} minutes', [$topic->duration]) ?>
                    </div>
                    </div>
                </div>
            </div>
            <div class="ed_course_single_tab">
                <div role="tabpanel">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#description" aria-controls="description" role="tab" data-toggle="tab"><?=__('Description')?></a></li>
                        <li role="presentation"><a href="#students" aria-controls="students" role="tab" data-toggle="tab"><?=__('Students')?></a></li>
                    </ul>
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="description">
                            <div class="ed_course_tabconetent">
                                <?= $topic->description; ?>
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="students">
                            <div class="ed_inner_dashboard_info">
                                <div class="ed_course_single_info">
                                    <h2>total students :- <span>0</span></h2>
                                    <h5>0 students recently join this course</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!--tab End-->
            <?= $this->AuthLink->link(__d('Topics', 'Edit topic'), ['plugin' => false,'controller' => 'Topics', 'action' => 'edit', $topic->id, 'prefix' => false], ['class' => 'btn ed_btn pull-right ed_orange pull-right small topic-button']);?>
            <?= $this->AuthLink->link(__d('Topics', 'Delete topic'), ['plugin' => false, 'controller' => 'Topics', 'action' => 'delete', $topic->id, 'prefix' => false], ['class' => 'btn ed_btn pull-right ed_green pull-right small topic-button', 'confirm' => __('Are you sure you want to delete this topic?')]);?> 
        </div>
    </div>
<!--Sidebar Start-->
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <div class="sidebar_wrapper_upper">
            <div class="sidebar_wrapper">
                <aside class="widget widget_button">
                    <?= !$isCoach ? $this->Html->link(__d('Session', 'Request Session'), ['controller' => 'Sessions', 'action' => 'add', $topic->coach_id, $topic->id, 'plugin' => false, 'prefix' => false],['class' => 'ed_btn ed_green']) : null;?>
                </aside>
                <aside class="widget widget_sharing">
                    <h4 class="widget-title"><?=__('share this course')?></h4>
                    <ul>
                        <li><a href="course_single.html"><i class="fa fa-facebook"></i> facebook</a></li>
                        <li><a href="course_single.html"><i class="fa fa-linkedin"></i> linkedin</a></li>
                        <li><a href="course_single.html"><i class="fa fa-twitter"></i> twitter</a></li>
                        <li><a href="course_single.html"><i class="fa fa-google-plus"></i> google+</a></li>
                    </ul>
                </aside>
            </div>
        </div>
    </div>
<!--Sidebar End-->
</div>