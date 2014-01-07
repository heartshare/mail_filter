<?php
$this->breadcrumbs=array(
	'Alert Keywords'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Keywords','url'=>array('index')),
);
?>

<h1>Add a Keyword</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>