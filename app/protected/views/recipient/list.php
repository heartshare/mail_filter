
<?php
$this->breadcrumbs=array(
	'Recipients'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Recipients','url'=>array('index')),
	array('label'=>'Update Recipient','url'=>array('update', "id"=>$model->id)),
);
?>

<h4>Messages Received at: <?php echo $model->email; ?></h4>
<?php
  if (!empty($messages)) {
  	foreach ($messages as $m) {
  	      echo $m->subject;lb();
  	}
  } else {
    echo 'No messages';lb();
  }
?>
