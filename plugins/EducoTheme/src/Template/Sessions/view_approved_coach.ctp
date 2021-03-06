<?= $this->extend('/Element/Sessions/session_layout');
?>
<?php $this->start('banner') ?>
    <?php echo $this->element('banner', ['title' => 'Upcoming Session']); ?>
<?php $this->end() ?>

<?php $this->start('image_title') ?>
    <?php echo $this->element('Sessions/image_title_coach', ['session' => $session]); ?>
<?php $this->end() ?>

<ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#description" aria-controls="description" role="tab" data-toggle="tab">Details</a></li>
</ul>
<!-- Tab panes -->
<div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="description">
        <div class="ed_course_tabconetent">       
            <table class="vertical-table">
                <?= $this->element('Sessions/general_view', ["session" => $session]);?>
                <tr>
                    <th><?= __('Assist to session: ') ?></th>
                    <td>
                        <?php if ($url):
                            echo $this->Html->link(__('Assist to your class'), $url, ['id' => 'start', 'name'=>$session->id, 'class' => 'btn ed_btn pull-right ed_orange pull-right small']);
                        else:
                            echo __('Your class is not available yet');
                        endif;
                        ?>
                    </td>
                </tr>
            </table>
            <?php if (!$url):
                echo $this->element('Sessions/cancel_session_button', ['session' => $session, 'button' => 'Cancel', 'action' => 'approved','message' => 'Are you sure you want to cancel this session?']);
            endif;?>
        </div>
    </div>
</div>

<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?= __('Cancel Session') ?></h4>
            </div>
            <div class="modal-body">
                <?= $this->Form->create(null, ['url' => ['plugin' => false,'action' => 'cancelSession', $session->id, 'controller' => 'Sessions'], 'novalidate' => true]);?>
                <?= $this->TinyMCE->editor(['theme' => 'modern', 'selector' => 'textarea']);?>
                <fieldset id='fields'>
                    <?= $this->Form->input('observation',['type' => 'textarea', 'label' => __('Why do you want to cancel this session?'), 'required' => true]);?>
                    <?= $this->Form->hidden('id', ["value" => $session->id]);?>
                </fieldset>
            </div>
            <div class="modal-footer">
                <?= $this->Form->unlockField('observation');?>
                <?= $this->Form->unlockField('id');?>
                <?= $this->Form->button(__('Cancel Session'), ['class' => 'ed_btn ed_orange medium btn btn-primary pull-right'])?>
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</div>