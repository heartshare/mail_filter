<?php
$this->breadcrumbs=array(
	'Messages'=>array('index'),
	$model->id,
);

/* $this->menu=array(
	array('label'=>'List Message','url'=>array('index')),
	array('label'=>'Create Message','url'=>array('create')),
	array('label'=>'Update Message','url'=>array('update','id'=>$model->id)),
	array('label'=>'Delete Message','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Message','url'=>array('admin')),
);
*/
?>
<div class="row">
  <div class="span11">
<div class="btn-toolbar">    <div class="row">
  <div class="span7"> 
<div class="left"><?php
 $this->widget('bootstrap.widgets.TbButton',array(
	'label' => 'back to Inbox',
	'size' => 'medium',
	'url' => Yii::app()->createUrl('message/index'),
));
// echo Yii::app()->createUrl('message/index'); 
?>
<?php  
if (!$isMobile) {
  $this->widget('bootstrap.widgets.TbButtonGroup', array(
    'buttons'=>array(
	    array('label'=>'Reflect', 'url'=>Yii::app()->createUrl('message/reflect',array('id'=>$model->id))),
    ),
));
$this->widget('bootstrap.widgets.TbButtonGroup', array(
    'buttons'=>array(
      
      ($model->in_trash==0 ? array('label'=>'Delete', 'url'=>Yii::app()->createUrl('message/trash',array('id'=>$model->id))) : array('label'=>'Undelete', 'url'=>Yii::app()->createUrl('message/untrash',array('id'=>$model->id)))
      ),
    	  
    	        
($model->is_blocked==0 ?
array('label'=>'Block Sender', 'url'=>Yii::app()->createUrl('message/block',array('id'=>$model->id))) : 
    array('label'=>'Unblock Sender', 'url'=>Yii::app()->createUrl('message/unblock',array('id'=>$model->id)))
    ),
    )
));

}

?>

  </div> <!-- end left -->
</div> <!-- end span 8 -->
  <div class="span4">
  <div class="right">
<?php  
$this->widget('bootstrap.widgets.TbButtonGroup', array(
    'buttons'=>array(
	    array('label'=>'Newer', 'url'=>Yii::app()->createUrl('message/view',array('id'=>$newerId)).$source,'htmlOptions'=>array('class'=>(is_null($newerId) ? 'disabled' : ''))),
	    array('label'=>'Older', 'url'=>Yii::app()->createUrl('message/view',array('id'=>$olderId)).$source,'htmlOptions'=>array('class'=>(is_null($olderId) ? 'disabled' : ''))),
    ),
));
?>
</div>
</div>
</div>
  </div> <!-- end span12 -->
</div> <!-- end row -->
</div>
<br />
<div class="row">  
  <div class="span11 post"> 
<table class="message items table table-striped">
<tbody>
  <tr class="odd">
  <td class="subject" colspan="2"><?php echo $model->subject; ?></td>
  </tr>
  <tr class="even">
  <td><strong><?php echo getSenderName($model->sender_id); ?></strong><br />
    to <?php echo getUserFullName($model->recipient_id); ?></td><td class="right"><?php echo inbox_date($model->created_at); ?></td>
  </tr>
<tr class="odd">
<td colspan="2" class="message_body"><div>
<?php //echo hyperlink($model->body); 
if (is_null($model->body_html)) {
  ?><pre><?php
  echo $model->body;
  ?></pre><?php
}
else
  echo $model->body_html;
?>  
</div>
</td>
</tr>
</tbody>
</table>

