<?= $this->extend('/Element/Sessions/session_layout');?>
<?php $this->start('banner') ?>
    <?php echo $this->element('banner', ['title' => 'Pending Session']); ?>
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
            </table>
            <?php if (!$url):
                echo $this->element('Sessions/accept_decline_buttons', ['session' => $session]);
            endif;?>
        </div>
    </div>
</div>