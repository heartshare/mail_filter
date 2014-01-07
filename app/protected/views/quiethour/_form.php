<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'quiet-hour-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="help-block">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

  <?php
    echo CHtml::activeLabel($model,'days',array('label'=>'Which day')); 
  ?>
  <?php echo $form->dropDownList($model,'days', $model->getDaysOptions()); ?>

  <?php
    echo CHtml::activeLabel($model,'start_interval',array('label'=>'Start')); 
  ?>
  <?php echo $form->dropDownList($model,'start_interval', $model->getStartOptions()); ?>

  <?php
    echo CHtml::activeLabel($model,'end_interval',array('label'=>'End')); 
  ?>
  <?php echo $form->dropDownList($model,'end_interval', $model->getEndOptions()); ?>

  <p><em>Quiet hours require the advanced module. <a href="/site/page?view=upgrade">Learn more</a>.</em></p>


	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Create' : 'Save',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
