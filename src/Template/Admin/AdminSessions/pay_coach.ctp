<nav class="large-3 medium-4 columns" id="actions-sidebar">
<div class="users">
<?= $this->Form->create(null, ['id' => 'payment-form']);?>
    <h3><?= __('Coaches') ?></h3>
    <table cellpadding="0" cellspacing="0" class="table table-striped">
        <thead>
            <tr>
                <th><?=  __('Topic') ?></th>
                <th><?= __('Date and time of session') ?></th>
                <th><?= __('Debt')?></th>
                <th><?= __('Select')?></th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 0?>
            <?php foreach ($sessions as $session): ?>
            <tr>
                <?php $price = $session->topic->price - $session->topic->price * $session->pending_liability->commission?>
                <td><?= $session->subject ?></td>
                <td><?= $session->schedule ?></td>
                <td><?= $this->Number->currency($price, 'USD')?></td>
                <td><?= $this->Form->checkbox('Sessions.' . $i . '.id', ['value' => $session->id, 'price' => $price]);?></td>
                <?php $i +=1; ?>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <button type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target="#myModal" id="pay-button" disabled>Submit</button>
    <?= $this->Html->link(__('Cancel'), ['action' => 'unpaidCoaches', 'controller' => 'AdminSessions', 'plugin' => false], ['class' => 'btn btn-default pull-right']) ?>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
        </ul>
        <p><?= $this->Paginator->counter() ?></p>
    </div>
</div>

<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><?= __('Pay Coach') ?></h4>
      </div>
      <div class="modal-body">
            <h3 id="price-text"></h3>
            <?= $this->TinyMCE->editor(['theme' => 'modern', 'selector' => 'textarea']);?>
            <fieldset id='fields'>
                <?= $this->Form->input('date',[
                    'error'=> false,
                    'required' => true,
                    'id' => 'birthdate',
                    'class' => 'form-control',
                    'type'=>'text',
                    'placeholder'=>'YYYY-MM-DD',
                    'label' => false,
                    'templates' => [
                        'inputContainer' => '<div class="input text required"><div class="input-group date" id="payment-date" name ="date">{{content}}<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span></div></div>'
                    ],
                ]);?>
                <?= $this->Form->input('observation',['class' => 'form-control', 'type' => 'textarea']);?>
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