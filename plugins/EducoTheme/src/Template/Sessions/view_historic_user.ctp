<?php use Cake\Routing\Router; ?>
<?= $this->extend('/Element/Sessions/session_layout');
?>
<?php $this->start('banner') ?>
    <?php echo $this->element('banner', ['title' => 'Past Session']); ?>
<?php $this->end() ?>

<?php $this->start('image_title') ?>
    <?php echo $this->element('Sessions/image_title_user', ['session' => $session]); ?>
<?php $this->end() ?>

<ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#description" aria-controls="description" role="tab" data-toggle="tab">Details</a></li>
    <li role="presentation"><a href="#rating" aria-controls="rating" role="tab" data-toggle="tab">My Rating</a></li>
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
            <?php if(!$session->user_rating):?>
                <br>
                <button type="button" class="ed_btn ed_green" data-toggle="modal" data-target="#myModal">Rate this Session</button>
            <?php else:
                echo '<input id="rate-input" value="' . (string)$session->user_rating . '" class="rating rate-input" data-size="xs">';
                echo "<br>";
                echo $session->user_comments;
            endif;?>
        </div>
    </div>
</div>

<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
    <?= $this->Form->create($session,['type' => 'post','url'=> Router::url(['plugin' => false,'controller' => 'Sessions', 'action' => 'rateUser',$session->id, 'prefix' => false],true)])?>
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><?= __('Rate your session') ?></h4>
      </div>
      <div class="modal-body">
            <?= $this->TinyMCE->editor(['theme' => 'modern', 'selector' => 'textarea']);?>     
            <fieldset>
                <?php
                    echo $this->Form->label('Session.user_rating','Rate the class ',['class'=>'control-label']);
                    echo $this->Form->input('user_rating',  ['class' => 'rating','data-min'=>'0', 'data-max'=>'5', 'data-step'=>'1','label'=>false,'data-size'=>'xs','required' => true]);
                    echo $this->Form->input('user_comments',['class' => 'form-control']);
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