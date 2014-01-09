<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'private-message-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="help-block">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<?php echo $form->textFieldRow($model,'user_id',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'account_id',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'folder_id',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'sender_id',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'message_id',array('class'=>'span5','maxlength'=>255)); ?>

	<?php echo $form->textFieldRow($model,'subject',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'body_text',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'body_html',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'udate',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'status',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'created_at',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'modified_at',array('class'=>'span5')); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Create' : 'Save',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
