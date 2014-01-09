<?php
$this->breadcrumbs=array(
	'Messages'=>array('index'),
	$model->id,
);

?>
<div class="row">  
  <div class="span11 post"> 
<table class="message items table table-striped">
<tbody>
  <tr class="odd">
    <td><strong>From: <?php  echo getSenderNameForDigest($model->sender_id); ?></strong><br /></td><td class="right"><?php echo message_udate($model->udate); ?></td>
  </tr>
  <tr class="even">
    <td class="subject" colspan="2"><?php echo decryptStr($model->subject); ?></td>
  </tr>
<tr class="odd">
<td colspan="2" class="message_body"><div>
<?php 
  $body_text = decryptStr($model->body_text);
  $body_html = decryptStr($model->body_html);
  if (empty($body_text)) {
  ?><pre><?php
  echo $body_text;
  ?></pre><?php
}
else
  echo $body_html;
?>  
</div>
</td>
</tr>
</tbody>
</table>
