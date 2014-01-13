<?php

class DaemonController extends Controller
{

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array (
			array('allow',  // allow all users to perform 'receive' action
				'actions'=>array('index','inbox','daily','weekly','hourly'),
				'users'=>array('*'),
			),		
			array('allow', // allow admin user to perform 'admin' actions
				'actions'=>array('admin'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	
	public function actionInbox() {
	  // moves inbox messages to @filtering
	  // runs frequently e.g. every 1 - 3 minutes
    $r = new Remote();
    $r->processInbox();	  
	}
	
	public function actionIndex()
	{
	  // processes messages in @Filtering to appropriate folders
	  // runs every 5 - 10 minutes
    $r = new Remote();
    $r->processFiltering();
	  // Record timestamp of cronjob for monitoring
	  $file = file_put_contents('./protected/runtime/cronstamp.txt',time(),FILE_USE_INCLUDE_PATH);	  
	}

	public function actionHourly() {
	  $current_hour = date('G');
	  // send digests
    $digest = Message::model()->processDigests();
    $r = new Remote();
    $r->processRecentTrainings();
    $r->scanPrivate();
    if ($current_hour%2) {
      // every other hour
      $r->processDetectTraining();
    }
    if ($current_hour%6) {
      // every six hours
      $r->freshenInbox();
    }
	}
	
	public function actionDaily() {
	  echo 'Daily tasks';
    $r = new Remote();
    $r->purgeMessages();
    $r->expireSenders();      
	}
	
	public function actionWeekly() {
    // to do: purge Message table
	}
}
