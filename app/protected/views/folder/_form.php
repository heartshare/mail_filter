<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'folder-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="help-block">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<?php echo $form->textFieldRow($model,'name',array('class'=>'span5','maxlength'=>255)); ?>

  <?php
  echo CHtml::activeLabel($model,'train',array('label'=>'Include this folder in training?')); 
   echo $form->dropDownList($model,'train', array('0' => 'No','1' => 'Yes')); ?>
  <?php
  echo CHtml::activeLabel($model,'digest',array('label'=>'Include this folder in digest summaries?')); 
  
   echo $form->dropDownList($model,'digest', array('0' => 'No','1' => 'Yes')); ?>


	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Create' : 'Save',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
