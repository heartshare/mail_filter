<?php
$this->breadcrumbs=array(
	'Folders',
);

$this->menu=array(
	array('label'=>'Create Account','url'=>array('create')),
);
?>

<h1>Results of Cleaning Review Folder</h1>
<p><strong>The following messages were skipped as there is no training for these senders yet:</strong></p>
<ul>
<?php
  if (!empty($results['skip'])) {
    foreach ($results['skip'] as $i) {
      echo '<li>'.$i['from'].' '.$i['subject'].'</li>';
    }    
  } else {
    echo '<li>None were skipped</li>';
  }
?>
</ul>
<p><strong>The following messages were found and moved as described below:</strong></p>
<ul>
<?php
  if (!empty($results['move'])) {
    foreach ($results['move'] as $i) {
      echo '<li>'.$i['from'].' '.$i['subject'].$i['result'].' to '.$i['folder'].'</li>';
    }    
  }  else {
    echo '<li>None were moved</li>';
    }
?>
</ul>
