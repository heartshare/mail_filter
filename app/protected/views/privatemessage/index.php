<?php
$this->breadcrumbs=array(
	'Secure Messages',
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('privatemessage-grid', {
		data: $(this).serialize()
	});
	return false;
});
");

$this->menu=array(
	array('label'=>'Initialize Folders','url'=>array('initialize')),
);
?>

<h1>Secure Messages</h1>

<?php
  if (Yii::app()->params['version']=='basic') {
      Yii::app()->user->setFlash('upgrade', '<p><em>This feature requires the advanced module to encrypt messages. <a href="/site/page?view=upgrade">Learn more</a>.</em></p>');
      $this->widget('bootstrap.widgets.TbAlert', array(
          'alerts'=>array( // configurations per alert type
      	    'upgrade'=>array('block'=>true, 'fade'=>true, 'closeText'=>'×'), 
          ),
      ));
    }
?>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'privatemessage-grid',
	'type' => 'striped bordered',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
	      array(            
                    'name'=>'email',
                    'header'=>'From',
                    'value'=>'$data->email',
                ),
	      array(            
                    'name'=>'email',
                    'header'=>'Subject',
                    'value'=>'decryptStr($data->subject)',
                ),
    array(
        'name'=>'udate',
            'header' => 'Received',
             'value' => 'message_udate($data->udate)',
        ),
          	array(
              'htmlOptions'=>array('width'=>'100px'),  		
            	'class'=>'bootstrap.widgets.TbButtonColumn',
            	'header'=>'Options',
              'template'=>'{view} {delete}',
                  'buttons'=>array
                  (
                      'delete' => array
                      (
                      'options'=>array('title'=>'trash'),
                        'label'=>'<i class="icon-trash icon-large" style="margin:5px;"></i>',
                        'url'=>'Yii::app()->createUrl("privatemessage/delete", array("id"=>$data->id))',
                      ),
                  ),			
            ),
	),
)); ?>

