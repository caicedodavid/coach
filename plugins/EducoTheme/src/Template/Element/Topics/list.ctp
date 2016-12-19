<?php $this->assign('customBackgroundClass','ed_courses')?>
<div class="row">
    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
        <div class="row">
            <?php if (!$topics->count()):?>
                <div class="alert alert-info"><?= __('You have no topics')?></div>
            <?php else: ?>
                <div id="items">
                     <?php foreach ($topics as $topic): ?>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
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
                                            <?php echo $this->Img->display($topic->coach['user_image'][0], 'extra-small',['url' => ['action' => 'coachProfile', 'controller' => 'AppUsers', $topic->coach['id']]]);?><?= $this->Html->link(__($topic->coach['full_name']), ['action' => 'coachProfile', $topic->coach['id'], 'controller' => 'AppUsers']) ?>
                                        </div>
                                        <p><?= substr($topic->description,0,100) . ((strlen($topic->description) > 100) ? '...  ':'  ')?><?= $this->Html->link(__('See More'), ['controller' => 'Topics', 'plugin' => false, 'action' => 'view', $topic->id])?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <div>
                <center><?php echo $this->element('endless_pagination'); ?></center>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
        <div class="sidebar_wrapper_upper">
            <div class="sidebar_wrapper">
                <aside class="widget widget_search">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search...">
                        <span class="input-group-btn">
                            <button class="btn btn-default" type="button"><i class="fa fa-search"></i></button>
                        </span>
                    </div>
                </aside>
                <aside class="widget widget_categories">
                    <h4 class="widget-title">course Categories</h4>
                    <ul>
                    <?php foreach ($categories as $category):?>
                        <li>
                            <?= $this->Html->tag('a', null, ['href' => '#', 'category-id' => $category->id, 'class' => 'select-category'])?>
                                <i class="fa fa-chevron-right"></i><?=$category->name . __('({0})', $category->topic_count)?>
                            <?= $this->Html->tag('/a', null)?>
                        </li>
                    <?php endforeach;?>
                    </ul>
                    <div id="divCheckbox" style="display: none;">
                        <?= $this->element('PlumSearch.search'); ?>
                    </div>
                </aside>
            </div>
        </div>
    </div>
</div>

