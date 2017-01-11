<?php $this->start('banner') ?>
    <?php echo $this->element('banner', ['title' => __('Request Session')]); ?>
<?php $this->end() ?>
<?php $this->assign('customBackgroundClass','ed_purchase_course course_purchase_wrapper')?>
<?php use Cake\Routing\Router;?>
<div class="row">
    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
        <div class="ed_course_single_item">
            <div class="row">
                <div class="col-lg-5 col-md-5 col-sm-12">
                    <div class="ed_course_single_image">
                        <?php echo $this->Img->displayImage($topic ? $topic->topic_image : null, 'medium-wide');?>
                    </div>
                </div>
                <div class="col-lg-7 col-md-7 col-sm-12">
                    <div class="ed_course_single_info">
                        <h2><?= $session->subject ?><span><?= $topic ? $this->Number->currency($topic->price, 'USD') : null;?></span></h2>
                        <div class="ed_abbcart">
                        </div>
                    </div>
                    <?= $this->Form->input('', [
                        'options' => $topicSelector, 
                        'class' => 'form-control', 
                        'empty' => $topic ? [$topic->id => $session->subject] : __('Select...'), 
                        'value' => 'GO',
                        'required' => true,
                        'label' => __('Topics from this coach'),
                        'id' => 'topic-selector',
                        'coach-id' => $coachId
                    ]);?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="ed_course_single_tab ed_toppadder40">
                        <div role="tabpanel">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs" role="tablist">
                                <li role="presentation" class="active"><a href="#description" aria-controls="description" role="tab" data-toggle="tab"><?=__("Description")?></a></li>
                            </ul>
                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane active" id="description">
                                    <div class="ed_course_tabconetent">       
                                        <div class="ed_contact_form ed_toppadder60">
                                            <div class="sessions form">
                                            <?php if($topic):?>
                                                <?= $this->TinyMCE->editor(['theme' => 'modern', 'selector' => 'textarea']);?>
                                                <?= $this->Form->create($session) ?>
                                                    <fieldset>
                                                        <div class="form-group">
                                                            <?php
                                                                echo "<b>" . __("Date and time of session") . "</b>";
                                                                echo $this->Form->input('schedule',[
                                                                    'error'=> false,
                                                                    'id' => 'schedule',
                                                                    'class' => 'form-control',
                                                                    'type'=>'text',
                                                                    'placeholder'=>'YYYY-MM-DD HH:mm',
                                                                    'label' => false,
                                                                    'templates' => [
                                                                    'inputContainer' => '<div class="input text required"><div class="input-group date" id="date1" name ="date">{{content}}<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span></div></div>'
                                                                    ],
                                                                ]);
                                                                if ($this->Form->isFieldError('schedule')) {
                                                                    echo $this->Form->error('schedule');
                                                                }
                                                            ?>
                                                        </div>
                                                        <div class="form-group">
                                                            <?php
                                                                if ($session->subject){
                                                                    echo $this->Form->input('subject',  ['type' => 'hidden']); 
                                                                } else {
                                                                    echo $this->Form->input('subject',  ['class' => 'form-control']); 
                                                                }
                                                            ?>
                                                        </div>
                                                        <div class="form-group">
                                                            <?= $this->Form->input('comments',['class' => 'form-control']);?>
                                                        </div>
                                                    </fieldset>

                                                    <?= $this->Form->button(__('Submit'), ['class' => 'btn ed_btn ed_orange pull-right']) ?>
                                                    <?= $this->Html->link(__('Cancel'), ['controller' => 'AppUsers', 'action' => 'coaches'],['class' => 'btn ed_btn ed_green pull-right']) ?>
                                                <?= $this->Form->end() ?>
                                            <?php endif;?>
                                            </div> 
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!--tab End-->
                </div>
            </div>
        </div>  
    </div>
    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
        <div class="sidebar_wrapper_upper">
            <div class="sidebar_wrapper">
                <aside class="widget widget_sharing">
                    <h4 class="widget-title">share this course</h4>
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

