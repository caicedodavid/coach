<?php use Cake\Routing\Router ?>
<?php echo $this->Img->display($session->user['user_image'], 'large');?>
<table class="vertical-table">
    <br>
    <tr>
        <td><b><?= __('Subject: ') ?></b></td>
        <td><?= h($session->subject) ?></td>
    </tr>
    <br>
    <tr>
        <td><b><?= __('Date and time: ') ?></b></td>
        <td><?= h($session->schedule) ?></td>
    </tr>
    <br>
    <tr>
        <td><b><?= __('Comments: ') ?></b></td>
        <td><?= $session->comments ?></td>
    </tr>
    <br>
    <tr>
        <td><b><?= __('Coachee Comments: ') ?></b></td>
        <td><?= $session->user_comments?$session->user_comments:"<b> The coachee has not rated the session yet.</b>"?></td>
    </tr>
</table>

<h3> Your Rating </h3>
<?php if(!$session->coach_rating):?>
    <br>
    <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Rate this Session</button>
<?php else:
    echo '<input id="rate-input" value="' . (string)$session->coach_rating . '" class="rating rate-input" data-size="xs">';
    echo "<br>";
    echo $session->coach_comments;
endif;?>

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