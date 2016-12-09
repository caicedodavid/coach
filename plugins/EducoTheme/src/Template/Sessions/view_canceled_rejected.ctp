<?php use App\Model\Entity\Session; ?>
<?= $this->extend('/Element/Sessions/session_layout');
?>
<?php $this->start('banner') ?>
    <?php echo $this->element('banner', ['title' => $statusArray[$session->status] . 'Session']); ?>
<?php $this->end() ?>

<?php $this->start('image_title');
    if ($isCoach){
        echo $this->element('Sessions/image_title_coach', ['session' => $session]); 
    }
    else {
        echo $this->element('Sessions/image_title_user', ['session' => $session]); 
    }
$this->end() ?>

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
            <br>
            <br>
            <?= __('This session was ');?>
            <b><?= $statusArray[$session->status]?></b>
            <br>
            <br>
            <?php if($session->status === Session::STATUS_CANCELED):?>
                <b><?= __('Coach comments : ')?></b>
                <?= $session->coach_comments?>
            <?php endif;?>
        </div>
    </div>
</div>