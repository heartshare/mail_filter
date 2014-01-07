<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'recipient-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="help-block">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<?php echo $form->textFieldRow($model,'email',array('class'=>'span5','maxlength'=>255)); ?>


  <?php 
      echo CHtml::activeLabel($model,'folder_id',array('label'=>'Choose a Destination Folder:')); 
      echo CHtml::activeDropDownList($model,'folder_id',Recipient::model()->getFolderOptions($model->account_id),array('empty'=>'Select a Folder'));
      
  ?>

  <?php 
    Yii::app()->user->setFlash('advanced_feature','<p><em>Routing messages by the recipient require the Advanced module. <a href="/site/page?view=upgrade">Learn more</a>.</em></p>');	    
    $this->widget('bootstrap.widgets.TbAlert', array(
        'alerts'=>array( // configurations per alert type
    	    'advanced_feature'=>array('block'=>true, 'fade'=>true, 'closeText'=>'×'), 
        ),
    ));
  ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Create' : 'Save',
		)); ?>
	</div>
	<?php
	  if (!empty($messages)) {
    	echo '<h4>Recent Messages to this Recipient</h4>';
    	foreach ($messages as $m) {
    	      echo $m->subject;lb();
    	}
	  }
	?>

<?php $this->endWidget(); ?>
