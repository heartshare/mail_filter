<?php
$this->breadcrumbs=array(
	'Requests'=>array('index'),
	$model->id,
);

 $this->beginWidget('bootstrap.widgets.TbHeroUnit', array(
	'heading'=>'Thank you!',
)); ?>
 
<p>We've recorded your request for the email address: 
<?php
  echo $model->email;
?>
. We will contact you soon regarding your invitation request.</p>
 
<?php $this->endWidget(); ?>