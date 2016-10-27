<div class="sessions form">
    <?= $this->TinyMCE->editor(['theme' => 'modern', 'selector' => 'textarea']);?>
    <?= $this->Form->create($session) ?>
    <fieldset>
        <legend><?= __('Request Session with {0}',$coach) ?></legend>

        <?php
            echo "<b>Date of session</b>";
            echo $this->Form->input('schedule',[
                'error'=> false,
                'id' => 'schedule',
                'class' => 'form-control',
                'type'=>'text',
                'placeholder'=>'YYYY-MM-DD',
                'label' => false,
                'templates' => [
                    'inputContainer' => '<div class="input text required"><div class="input-group date" id="date1" name ="date">{{content}}<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span></div></div>'
                ],
            ]);
        ?>
        <?php
            echo "<b>Time of session</b><br>";
            echo $this->Form->input('time',  ['class' => 'form-control timepicker','type'=>'text','label'=>false]);
            if ($this->Form->isFieldError('schedule')) {
                echo $this->Form->error('schedule');
            }
            if ($session->subject){
                echo $this->Form->input('subject',  ['readonly'=> 'readonly','class' => 'form-control']); 
            } else {
                echo $this->Form->input('subject',  ['class' => 'form-control']); 
            }
            echo $this->Form->input('comments',['class' => 'form-control']);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit'), ['class' => 'btn btn-primary pull-right']) ?>
    <?= $this->Html->link(__('Cancel'), ['controller' => 'AppUsers', 'action' => 'coaches'],['class' => 'btn btn-default pull-right']) ?>
    <?= $this->Form->end() ?>
</div>