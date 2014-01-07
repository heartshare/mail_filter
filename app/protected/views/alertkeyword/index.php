<?php
$this->breadcrumbs=array(
	'Alert Keywords',
);

$this->menu=array(
	array('label'=>'Add a Keyword','url'=>array('create')),
);
?>

<h1>Alert Keywords</h1>
<?php
  if (Yii::app()->params['version']=='basic') {
      Yii::app()->user->setFlash('upgrade', '<p><em>This feature requires the advanced module and the Pushover smartphone application for processing alerts. <a href="/site/page?view=upgrade">Learn more</a>.</em></p>');
      $this->widget('bootstrap.widgets.TbAlert', array(
          'alerts'=>array( // configurations per alert type
      	    'upgrade'=>array('block'=>true, 'fade'=>true, 'closeText'=>'×'), 
          ),
      ));
    }
?>

<p>You will receive smartphone notifications via Pushover when email is received whose subject or email address fields contain any of the following keywords. You can also configure alerts per sender by <a href="/sender/index">updating individual senders</a>.</p><p>To properly receive notifications, configure your <a href="/usersetting/update">Pushover settings</a> and ensure that your Pushover application token is included in Filtered.ini.</p>


<?php
$cntrlr = Yii::app()->controller->action->id; 
      $gridColumns = array(   
        'keyword',
      	array(
          'htmlOptions'=>array('width'=>'100px'),  		
        	'class'=>'bootstrap.widgets.TbButtonColumn',
        	'header'=>'Options',
          'template'=>'{update}{delete}',
              'buttons'=>array
              (
                  'update' => array
                  (
                    'options'=>array('title'=>'Update keyword'),
                    'label'=>'<i class="icon-pencil icon-large" style="margin:5px;"></i>',
                    'url'=>'Yii::app()->createUrl("alertkeyword/update", array("id"=>$data->id))',
                  ),
              
                  'delete' => array
                  (
                  'options'=>array('title'=>'trash'),
                    'label'=>'<i class="icon-trash icon-large" style="margin:5px;"></i>',
                    'url'=>'Yii::app()->createUrl("alertkeyword/delete", array("id"=>$data->id))',
                  ),
              ),			
        ),

      );
    
 $this->widget('bootstrap.widgets.TbGridView', array(
	'type'=>'striped',
		'hideHeader'=>false,
	'dataProvider'=>$dataProvider,
	'template'=>"{items}\n{pager}",
	'columns'=>$gridColumns,
));

?>
