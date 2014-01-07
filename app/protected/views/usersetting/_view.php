<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id),array('view','id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('user_id')); ?>:</b>
	<?php echo CHtml::encode($data->user_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('digestOn')); ?>:</b>
	<?php echo CHtml::encode($data->digestOn); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('digestInterval')); ?>:</b>
	<?php echo CHtml::encode($data->digestInterval); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('digestNext')); ?>:</b>
	<?php echo CHtml::encode($data->digestNext); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('digestLast')); ?>:</b>
	<?php echo CHtml::encode($data->digestLast); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('digestUseAutologin')); ?>:</b>
	<?php echo CHtml::encode($data->digestUseAutologin); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('digestIncludeCaption')); ?>:</b>
	<?php echo CHtml::encode($data->digestIncludeCaption); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('pushover_token')); ?>:</b>
	<?php echo CHtml::encode($data->pushover_token); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('pushover_device')); ?>:</b>
	<?php echo CHtml::encode($data->pushover_device); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('timezone')); ?>:</b>
	<?php echo CHtml::encode($data->timezone); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('view_messages')); ?>:</b>
	<?php echo CHtml::encode($data->view_messages); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('created_at')); ?>:</b>
	<?php echo CHtml::encode($data->created_at); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('modified_at')); ?>:</b>
	<?php echo CHtml::encode($data->modified_at); ?>
	<br />

	*/ ?>

</div>