<?php
$this->breadcrumbs=array(
	'Senders'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Senders','url'=>array('index')),
);
?>

<?php 
  if(Yii::app()->user->hasFlash('updated')) {
  $this->widget('bootstrap.widgets.TbAlert', array(
      'alerts'=>array( // configurations per alert type
  	    'updated'=>array('block'=>true, 'fade'=>true, 'closeText'=>'×'), 
      ),
  ));
}
?>

<h1>Update Sender</h1>

<?php echo $this->renderPartial('_form',array('model'=>$model,'message_count'=>$message_count)); ?>