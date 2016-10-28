<div class="col-lg-5 col-md-5 col-sm-12">
    <div class="ed_course_single_image">
        <?php echo $this->Img->displayImage($session->topic['topic_image'], 'medium-wide');?>
    </div>
</div>
<div class="ed_course_single_info">
    <h2>
        <?php if($session->topic_id):?>
            <?= $this->Html->link(__($session->topic['name']), ['controller' => 'Topics', 'plugin' => false, 'action' => 'view', $session->topic['id']]) ?>
        <?php else:?>
            <?= h($session->subject); ?>
        <?php endif;?>
    </h2>
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
            <div class="course_detail">
                <div class="course_faculty">
                    <?php echo $this->Img->display($session->coach['user_image'], 'extra-small');?><?= $this->Html->link(__($session->coach['full_name']), ['action' => 'coachProfile', $session->coach['id'], 'controller' => 'AppUsers']) ?>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pull-right text-right">
            <div class="ed_course_duration">
            time duration: 60 min
        </div>
        </div>
    </div>
</div>