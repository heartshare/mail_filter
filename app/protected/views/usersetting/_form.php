<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'user-setting-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="help-block">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

  <div class="row digestOn">
  <h4>Digest Settings</h4>
    <p style="display:inline;">
  <?php echo $form->checkBox($model,'digestOn'); ?> Send me a digest of messages
  <?php echo CHtml::dropDownList('UserSetting[digestInterval]', $model->digestInterval, 
                array(
                  '1' => 'Hourly',
                '2' => 'Every two hours',
                 '4' => 'Every four hours',
                 '6' => 'Every six hours',
                 '8' => 'Every eight hours',
                 '12' => 'Twice daily',
                 '24' => 'Once daily',
                 '48' => 'Every other day',
                 '72' => 'Every three days',
                 '168' => 'Once weekly',                 
                 ));
     ?>
   </p>
   </div>	

<div class="row digestOn" >
  <h3>Advanced Features</h4>
    <?php
      if (Yii::app()->params['version']=='basic') {
          Yii::app()->user->setFlash('upgrade', '<p><em>The following features require the advanced module to operate. <a href="/site/page?view=upgrade">Learn more</a>.</em></p>');
          $this->widget('bootstrap.widgets.TbAlert', array(
              'alerts'=>array( // configurations per alert type
          	    'upgrade'=>array('block'=>true, 'fade'=>true, 'closeText'=>'×'), 
              ),
          ));
        }
    ?>
</div>
<div class="row digestOn" >
<h4>Alert Notifications</h4>
<?php echo $form->textFieldRow($model,'pushover_token',array('class'=>'span5','maxlength'=>32)); ?>
  
<?php echo $form->textFieldRow($model,'pushover_device',array('class'=>'span5','maxlength'=>32)); ?>
</div>
<div class="row digestOn" >
<h4>Other Settings</h4>
<?php
  echo CHtml::activeLabel($model,'inbox_age',array('label'=>'Inbox Cleaning')); 
?>
<?php echo $form->dropDownList($model,'inbox_age', $model->getInboxAgeOptions()); ?>
</div>
<div class="row digestOn" >
<?php
  echo CHtml::activeLabel($model,'inbox_age',array('label'=>'Whitelisting')); 
?>
<p><em>When whitelisting is active, we will send a challenge email for unknown senders to verify themselves. Verified senders will be placed in the inbox instead of the review folder.</em></p>
<?php echo $form->dropDownList($model,'use_whitelist', $model->getWhitelistOptions()); ?>
</div>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>'Save Settings',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
