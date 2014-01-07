<?php /* @var $this Controller */ 
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/main.js'); 
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />
  <link rel="icon" type="image/png" href="/images/favicon.png" />
	<!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->
	<!-- font-awesome -->
  <link href="http://netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.no-icons.min.css" rel="stylesheet">
  <link href="http://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />
	

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>

<div class="container" >


	<!-- mainmenu -->
	<?php //if(isset($this->breadcrumbs))     $this->widget('bootstrap.widgets.TbBreadcrumbs', array('links'=>array('Library'=>'#', 'Data'),)); ?>
	<div class="nav_spacer">&nbsp;</div>
	  
	<?php echo $content; ?>

	<div class="clear"></div>

	<div id="footer">
    <div class="left">Provided by <a href="http://mailgun.com">Mailgun</a></div>
  <div class="right">Written by <?php echo date('Y'); ?> <a href="http://jeffreifman.com/consulting">NewsCloud Consulting</a></div>
	</div><!-- footer -->

</div><!-- page -->
</body>
</html>
