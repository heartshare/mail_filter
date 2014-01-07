
<?php /* @var $this Controller */ 
$baseUrl = Yii::app()->baseUrl; 
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl.'/js/home.js');
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/main.js'); 
if (Yii::app()->params['env']=='live') {  Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/statcounter.js');  
} 

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />
	<link rel="shortcut icon" href="/images/favicon.ico" type="image/x-icon">
	<link rel="icon" href="/images/favicon.ico" type="image/x-icon">
	<!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->

	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/home.css" />

	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>
<div class="container" >
  
	<?php echo $content; 	?>
  <div class="footer" >
        <ul class="inline">
          <li><a href="http://jeffreifman.com/filtered-open-source-imap-mail-filtering-software-for-php/">Overview</a><span class="dot divider"> &middot;</span></li>
        <li><a href="http://jeffreifman.com/consulting">NewsCloud Consulting</a><span class="dot divider"> &middot;</span></li>
        <li><a href="http://jeffreifman.com/contact">Contact</a></li>
      </ul>

<!--       <li ><a href="/privacy">Privacy</a><span class="dot divider"> &middot;</span></li> -->
  </div>
</div><!-- page -->
</body>
</html>
