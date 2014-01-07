<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'account-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="help-block">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<?php echo $form->textFieldRow($model,'name',array('class'=>'span5','maxlength'=>255,'labelOptions' => array('label' => 'Friendly Name'))); ?>

	<?php echo $form->textFieldRow($model,'username',array('class'=>'span5','maxlength'=>255,'labelOptions' => array('label' => 'Account Login / Username'))); ?>
	
	<?php echo $form->textFieldRow($model,'password',array('class'=>'span5','maxlength'=>255)); ?>

  <?php echo $form->labelEx($model,'Mail Provider'); ?>
  <?php echo $form->dropDownList($model,'provider', $model->getTypeOptions()); ?>
	<?php echo $form->textFieldRow($model,'address',array('placeholder'=>'e.g. imap.gmail.com:993/imap/ssl','class'=>'span5','maxlength'=>255,'labelOptions' => array('label' => 'IMAP Adress'))); ?>
  
  <p><strong>Common IMAP Addresses</strong><ul>
    <li>Gmail: imap.gmail.com:993/imap/ssl</li>
    <li><a href="http://www.fastmail.fm/?STKI=10990518">Fastmail</a>: mail.messagingengine.com:993/imap/ssl</li>
    <li>Yahoo: imap.mail.yahoo.com:993/imap/ssl (not yet tested in this codebase)</li>
    </ul></p>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Create' : 'Save',
		)); ?>
	</div>
	
<?php $this->endWidget(); ?>
