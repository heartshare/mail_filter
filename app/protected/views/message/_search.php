<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<?php echo $form->textFieldRow($model,'account_id',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'folder_id',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'sender_id',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'subject',array('class'=>'span5','maxlength'=>255)); ?>


	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType' => 'submit',
			'type'=>'primary',
			'label'=>'Search',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
