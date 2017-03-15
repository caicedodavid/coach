<!-- Cropping modal -->
<div class="modal fade" id="avatar-modal" aria-hidden="true" aria-labelledby="avatar-modal-label" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" id="avatar-modal-label"><?=__('Change Image')?></h4>
                </div>
                <div class="modal-body">
                    <div class="avatar-body">
                        <!-- Upload image and data -->
                        <div class="avatar-upload">                                    
                            <?= $this->Form->hidden('avatar_src', ['class' => 'avatar-src']);?>
                            <?= $this->Form->unlockField('avatar_src');?>                                    
                            <?= $this->Form->hidden('avatar_data', ['class' => 'avatar-data']);?>
                            <?= $this->Form->unlockField('avatar_data');?>
                            <?= $this->Form->file('avatar_file', ['id' => 'avatarInput', 'class' => 'form-control avatar-input', 'required' => false]);?>
                        </div>      
                        <!-- Crop and preview -->
                        <div class="row">
                            <div class="col-md-9">
                                <div class="avatar-wrapper"></div>
                            </div>
                            <div class="col-md-3">
                                <?= $this->Html->tag('div', null, ['class'=>'avatar-preview ' . $previewClass])?>
                                <?= $this->Html->tag('/div')?>
                            </div>
                        </div>
                        <div class="row avatar-btns">
                            <div class="col-md-9">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-primary" data-method="rotate" data-option="-90" title="Rotate -90 degrees">Rotate Left</button>
                                    <button type="button" class="btn btn-primary" data-method="rotate" data-option="-15">-15deg</button>
                                    <button type="button" class="btn btn-primary" data-method="rotate" data-option="-30">-30deg</button>
                                    <button type="button" class="btn btn-primary" data-method="rotate" data-option="-45">-45deg</button>
                                </div>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-primary" data-method="rotate" data-option="90" title="Rotate 90 degrees">Rotate Right</button>
                                    <button type="button" class="btn btn-primary" data-method="rotate" data-option="15">15deg</button>
                                    <button type="button" class="btn btn-primary" data-method="rotate" data-option="30">30deg</button>
                                    <button type="button" class="btn btn-primary" data-method="rotate" data-option="45">45deg</button>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <center><button type="button" class="btn btn-primary avatar-accept" data-dismiss="modal" disabled>Done</button></center>
                            </div>
                        </div>
                    </div>
                </div>
              <!-- <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              </div> -->
        </div>
    </div>
</div><!-- /.modal -->