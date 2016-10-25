<div class="topics view large-9 medium-8 columns content">
    <h3><?= h($topic->name) ?></h3>
    <br>
        <td><?php echo $this->Img->displayImage($topic['topic_image'], 'large');?></td>
    <br>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Name') ?></th>
            <td><?= h($topic->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Duration') ?></th>
            <td><?= h($topic->duration) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Active') ?></th>
            <td><?= $topic->active ? __('Yes') : __('No'); ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('Description') ?></h4>
        <?= $this->Text->autoParagraph(h($topic->description)); ?>
    </div>
    <br>
    <?= $this->AuthLink->link(__d('Topics', 'Edit topic'), ['plugin' => false,'controller' => 'Topics', 'action' => 'edit',$topic->id, 'prefix' => false]);?>
    <?= $this->AuthLink->link(__d('Session', 'Request Session about this topic'), [ 'action' => 'add',$topic->coach->id,$topic->coach->full_name,$topic->id,$topic->name,'plugin' => false,'controller' => 'Sessions', 'prefix' => false]);?>
</div>
