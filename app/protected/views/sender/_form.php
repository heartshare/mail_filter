<?php 
  if($message_count>0) {
  Yii::app()->user->setFlash('message_count','If you change the destination folder for this Sender, be patient after hitting save. It may take up to a minute to move the messages to the new folder once you click save.');	    
  $this->widget('bootstrap.widgets.TbAlert', array(
      'alerts'=>array( // configurations per alert type
  	    'message_count'=>array('block'=>true, 'fade'=>true, 'closeText'=>'×'), 
      ),
  ));
}
?>

<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'sender-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="help-block">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>
  <?php echo CHtml::activeLabel($model,'account_id',array('label'=>'Account: '.$model->account->name)); ?>

	<?php echo $form->textFieldRow($model,'personal',array('class'=>'span5','maxlength'=>255)); ?>

	<?php echo $form->textFieldRow($model,'email',array('class'=>'span5','maxlength'=>255)); ?>

  <?php 
      echo CHtml::activeLabel($model,'folder_id',array('label'=>'Choose a Destination Folder:')); 
      echo CHtml::activeDropDownList($model,'folder_id',Sender::model()->getFolderOptions($model->account_id),array('empty'=>'Select a Folder'));
      
  ?>

  <h4>Advanced Features</h4>
  <p><em>The settings below require the advanced module. <a href="/site/page?view=upgrade">Learn more</a>.</em></p>

  <?php
    echo CHtml::activeLabel($model,'exclude_quiet_hours',array('label'=>'Quiet Hours')); 
  ?>
  <?php echo $form->dropDownList($model,'exclude_quiet_hours', $model->getExcludeQuietHoursOptions()); ?>

  <?php
    echo CHtml::activeLabel($model,'alert',array('label'=>'Smartphone Alerts')); 
  ?>
  <?php echo $form->dropDownList($model,'alert', $model->getAlertOptions()); ?>

  <?php
    echo CHtml::activeLabel($model,'expire',array('label'=>'Automated Expiration')); 
  ?>
  <?php echo $form->dropDownList($model,'expire', $model->getExpireOptions()); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Create' : 'Save',
		)); ?>
	</div>
	<?php
	  if (count($model->messages)>0) {
    	echo '<h4>Recent Messages from this Sender</h4>';
    	foreach ($model->messages as $m) {
    	    if ($m->account_id == $model->account_id)
    	      echo $m->subject;lb();
    	}
	    
	  }
	
	?>

<?php $this->endWidget(); ?>
