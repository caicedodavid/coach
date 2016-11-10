<div class="ed_courses ed_toppadder80 ed_bottompadder80">
    <div class="container">
        <div class="row">
            <?php if (!$topics->count()):?>
                <div class="alert alert-info"><?= __('You have no topics')?></div>
            <?php else: ?>
                <div id="items">
                     <?php foreach ($topics as $topic): ?>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                            <div class="ed_mostrecomeded_course">
                                <div class="ed_item_img">
                                    <?php echo $this->Img->displayImage($topic['topic_image'], 'big', ['url' => ['action' => 'view', $topic->id]]);?>
                                </div>
                                <div class="ed_item_description ed_most_recomended_data">
                                    <h4>
                                        <?= $this->Html->link(__($topic->name), ['controller' => 'Topics', 'plugin' => false, 'action' => 'view', $topic->id])?>
                                        <span><?= $this->Number->currency($topic->price, 'USD');?></span>
                                    </h4>
                                    <div class="course_detail">
                                        <div class="course_faculty">
                                            <?php echo $this->Img->display($topic->coach['user_image'], 'extra-small',['url' => ['action' => 'coachProfile', 'controller' => 'AppUsers', $topic->coach['id']]]);?><?= $this->Html->link(__($topic->coach['full_name']), ['action' => 'coachProfile', $topic->coach['id'], 'controller' => 'AppUsers']) ?>
                                        </div>
                                        <p><?= substr($topic->description,0,100) . ((strlen($topic->description) > 100) ? '...  ':'  ')?><?= $this->Html->link(__('See More'), ['controller' => 'Topics', 'plugin' => false, 'action' => 'view', $topic->id])?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        <div>
            <center><?php echo $this->element('endless_pagination'); ?></center>
        </div>
    </div>         
</div>