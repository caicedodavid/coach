<?php?>
<?= $this->TinyMCE->editor(['theme' => 'modern', 'selector' => 'textarea']);?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $category->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $category->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Categories'), ['action' => 'index']) ?></li>
    </ul>
</nav>
<div class="categories form large-9 medium-8 columns content">
    <?= $this->Form->create($category) ?>
    <fieldset>
        <legend><?= __('Edit Category') ?></legend>
        <?php
            echo $this->Form->input('name', ['class' =>'form-control']);
            echo $this->Form->input('description');
            if(!$category->topic_count):
                echo $this->Form->input('active');
            else:
                echo $this->Html->tag('div', null, ['class' => 'input checkbox']);
                // Not allowed change - submit value..
                echo $this->Form->hidden('active', ['value' => true]);
                // .. and show user the value being submitted
                echo '<input type="checkbox" disabled checked="checked"> Active';
                $this->Html->tag('/div', null);
                endif;
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
