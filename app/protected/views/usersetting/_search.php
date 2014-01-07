<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<?php echo $form->textFieldRow($model,'id',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'user_id',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'digestOn',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'digestInterval',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'digestNext',array('class'=>'span5','maxlength'=>20)); ?>

	<?php echo $form->textFieldRow($model,'digestLast',array('class'=>'span5','maxlength'=>20)); ?>

	<?php echo $form->textFieldRow($model,'digestUseAutologin',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'digestIncludeCaption',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'pushover_token',array('class'=>'span5','maxlength'=>32)); ?>

	<?php echo $form->textFieldRow($model,'pushover_device',array('class'=>'span5','maxlength'=>32)); ?>

	<?php echo $form->textFieldRow($model,'timezone',array('class'=>'span5','maxlength'=>255)); ?>

	<?php echo $form->textFieldRow($model,'view_messages',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'created_at',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'modified_at',array('class'=>'span5')); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType' => 'submit',
			'type'=>'primary',
			'label'=>'Search',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
