<?php

$this->menu=array(
	array('label'=>'List Accounts','url'=>array('index')),
	array('label'=>'Create Account','url'=>array('create')),
);
?>

<h1>List Remote Folders</h1>
<?php
if (!empty($remote_list)) {
  echo "<ul>";
  foreach ($remote_list as $folder) {
      echo '<li>' . imap_utf7_decode($folder) . '</li>';
  }
  echo "</ul>";  
} else {
  echo 'No folders found in this account';lb();
}

?>
