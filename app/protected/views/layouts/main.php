<?php /* @var $this Controller */ 
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
	<!-- font-awesome -->
  <link href="http://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
  <link href="http://netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.no-icons.min.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />
	

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>

<div class="container" >
		<?php 
  if(isset($_GET['q']))
  {
    $q = $_GET['q'];
	} else {
	  $q='';
	}
		
		$this->widget('bootstrap.widgets.TbNavbar', array(
	'brandUrl'=>Yii::app()->getBaseUrl(true),
	'collapse' => true,
	'items' => array(
	  (!Yii::app()->user->isGuest ? array(	   
			'class' => 'bootstrap.widgets.TbMenu',
			'htmlOptions'=>array('class'=>'pull-left'),
			'items' => array(
				array('label'=>'Messages', 'url'=>array('/message/index'), ),
				array('label'=>'Senders', 'url'=>array('/sender/index'), ),
				array('label'=>'Folders', 'url'=>array('/folder/index'), ),
				array('label'=>'Accounts', 'url'=>array('/account/index'), ),
    		array('label'=>'Advanced', 'items'=> array(
            array('url'=>array('/alertkeyword/index'), 'label'=>'Alert keywords'),
            array('url'=>array('/quiethour/index'), 'label'=>'Quiet hours'),
            array('url'=>array('/recipient/index'), 'label'=>'Recipients'),
            array('url'=>array('/privatemessage/index'), 'label'=>'Secure Messages'),
          )),
			)
  	  ) : array() ),
		array(
			'class' => 'bootstrap.widgets.TbMenu',
			'htmlOptions'=>array('class'=>'pull-right'),
			'items' => array(
        array('label'=>'About', 'items'=> array(      
          array('url'=>'http://jeffreifman.com/filtered-open-source-imap-mail-filtering-software-for-php/introduction/', 'label'=>"Introduction and Overview"),
          array('url'=>'http://jeffreifman.com/contact', 'label'=>'Contact us'),
        array('url'=>'http://jeffreifman.com/consulting', 'label'=>'About NewsCloud'),
				)),
        array('label'=>'Developers', 'items'=> array(      
          array('url'=>'http://jeffreifman.com/filtered-open-source-imap-mail-filtering-software-for-php/feature-summary/', 'label'=>'Purchase Advanced Features', 'visible'=>Yii::app()->params['version']=='basic'),
          array('url'=>'http://filtered.uservoice.com/', 'label'=>'Request Features'),

          array('url'=>'https://groups.google.com/d/forum/filtered', 'label'=>'Support Forum'),
        array('url'=>'https://github.com/newscloud/mail_filter', 'label'=>'Get the Code'),
        array('url'=>'http://jeffreifman.com/filtered-open-source-imap-mail-filtering-software-for-php/release-history/', 'label'=>'Check for Updates'),
				)),
				array('label'=>'Account', 'items'=> array(
          array('label'=>'Hi '.getFirstName(), 'visible'=>!Yii::app()->user->isGuest),
          array('url'=>array('/usersetting/update'), 'label'=>'Your settings', 'visible'=>!Yii::app()->user->isGuest),

array('url'=>Yii::app()->getModule('user')->loginUrl, 'label'=>Yii::app()->getModule('user')->t("Login"), 'visible'=>Yii::app()->user->isGuest),
array('url'=>Yii::app()->getModule('user')->registrationUrl, 'label'=>Yii::app()->getModule('user')->t("Sign up"), 'visible'=>Yii::app()->user->isGuest),
array('url'=>Yii::app()->getModule('user')->logoutUrl, 'label'=>'Sign out', 'visible'=>!Yii::app()->user->isGuest),			

				)),
			),
		),    
	)
));	 ?>
	<!-- mainmenu -->
	<?php //if(isset($this->breadcrumbs))     $this->widget('bootstrap.widgets.TbBreadcrumbs', array('links'=>array('Library'=>'#', 'Data'),)); ?>
	<div class="nav_spacer">&nbsp;</div>
	  
	<?php echo $content; ?>

	<div class="clear"></div>

	<div id="footer">
  <div class="right">
    <ul class="inline">
      <li><a href="https://twitter.com/intent/user?screen_name=reifman">Follow @reifman</a><li>
    <li>&copy; <?php echo date('Y'); ?> <a href="http://jeffreifman.com/consulting">NewsCloud Consulting</a></li></ul> &middot;</span></li>      </ul></div>
  <div class="left"><ul class="inline">
    <li><a href="http://jeffreifman.com/filtered-open-source-imap-mail-filtering-software-for-php/">Overview</a></li>
    <?php
      if (Yii::app()->params['version']=='basic') {
        echo '<li><a href="http://jeffreifman.com/filtered-open-source-imap-mail-filtering-software-for-php/feature-summary/">Upgrade</a></li>';
      }
    ?>
    <li><a href="http://filtered.uservoice.com/">Request Features</a></li>
    <li><a href="https://groups.google.com/d/forum/filtered">Support Forum</a></li>
    <li><a href="http://jeffreifman.com/filtered-open-source-imap-mail-filtering-software-for-php/release-history/">Updates</a></li>
</ul></div>
	</div><!-- footer -->

</div><!-- page -->
</body>
</html>
