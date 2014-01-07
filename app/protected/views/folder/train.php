<?php
$this->breadcrumbs=array(
	'Folders',
);

$this->menu=array(
	array('label'=>'List Folders','url'=>array('index')),
	array('label'=>'Create Folder','url'=>array('create')),
);
?>

<h1>Results of Training Folder: <?php echo $model->name; ?></h1>
<p>The following senders were found in this folder and set to route here in the future:<br /> <em>(currently we look one year or 250 messages back in the folder but you can customize this in Remote->trainFolder)</em></p>
<?php
  if (!empty($train)) {
    foreach ($train as $t) {
      echo '<p>'.$t.'</p>';
    }    
  } else {
    echo 'Sorry, no senders were found in this folder.';lb();
  }

?>

