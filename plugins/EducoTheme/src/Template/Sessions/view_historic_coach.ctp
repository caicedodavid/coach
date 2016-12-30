<?php use Cake\Routing\Router; ?>
<?= $this->extend('/Element/Sessions/session_layout');?>
<?php $this->start('banner') ?>
    <?php echo $this->element('banner', ['title' => 'Past Session']); ?>
<?php $this->end() ?>

<?php $this->start('image_title') ?>
    <?php echo $this->element('Sessions/image_title_coach', ['session' => $session]); ?>
<?php $this->end() ?>

<ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#description" aria-controls="description" role="tab" data-toggle="tab"><?=__("Details")?></a></li>
    <li role="presentation"><a href="#rating" aria-controls="rating" role="tab" data-toggle="tab"><?=__("My Rating")?></a></li>
    <li role="presentation"><a href="#user-comments" aria-controls="user-comments" role="tab" data-toggle="tab"><?=__("Coachee Comments")?></a></li>
</ul>
<!-- Tab panes -->
<div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="description">
        <div class="ed_course_tabconetent">       
            <table class="vertical-table">
                <?= $this->element('Sessions/general_view', ["session" => $session]);?>
            </table>
        </div>
    </div>
    <div role="tabpanel" class="tab-pane" id="rating">
        <div class="ed_course_tabconetent">       
            <?php if(!$session->coach_rating):?>
                <?= $this->Html->tag('br');?>
                <?= $this->Html->link(__('Rate this Session'), '#', ["class" => "ed_btn ed_green",  "data-toggle" => "modal", "data-target" => "#myModal"]);?>
            <?php else:?>
                <?= $this->Form->input('rate-input', ['id' => 'rate-input', 'value' => $session->coach_rating, 'class' => 'rating rate-input', 'data-size' => 'xs']);?>
                <?= $this->Html->tag('br');?>
                <?= $session->user_comments;?>
            <?php endif;?>
        </div>
    </div>
    <div role="tabpanel" class="tab-pane" id="user-comments">
        <div class="ed_course_tabconetent">       
            <?= $session->user_comments?$session->user_comments:"<b> The coachee has not rated the session yet.</b>"?>
        </div>
    </div>
</div>

<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
    <?= $this->Form->create($session,['type' => 'post','url'=> Router::url(['plugin' => false,'controller' => 'Sessions', 'action' => 'rateCoach',$session->id, 'prefix' => false],true)])?>
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><?= __('Rate your session') ?></h4>
      </div>
      <div class="modal-body">
            <?= $this->TinyMCE->editor(['theme' => 'modern', 'selector' => 'textarea']);?>    
            <fieldset>
                <?php
                    echo $this->Form->label('Session.coach_rating','Rate the class ',['class'=>'control-label']);
                    echo $this->Form->input('coach_rating',  ['class' => 'rating','data-min'=>'0', 'data-max'=>'5', 'data-step'=>'1','label'=>false,'data-size'=>'xs','required' => true]);
                    echo $this->Form->input('coach_comments',['class' => 'form-control']);
                    ?>
            </fieldset>
      </div>
      <div class="modal-footer">
        <?= $this->Form->button(__('Submit'), ['class' => 'btn btn-primary pull-right']) ?>
        <?= $this->Form->button(__('Cancel'),['class' => 'btn btn-default', 'data-dismiss'=>'modal']) ?>
        <?= $this->Form->end() ?>
      </div>
    </div>
  </div>
</div>