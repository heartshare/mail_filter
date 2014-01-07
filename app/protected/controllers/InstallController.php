<?php

class InstallController extends Controller
{
  public $tablePrefix;
  public $tableName;
  

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
		return array(
			array('allow',  // allow all users to perform actions
				'actions'=>array(''),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 
				'actions'=>array('addusersettings','initializefolders'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 
				'actions'=>array('reserve_mailboxes','configure'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	public function actionAddusersettings() {
	  // add user_settings entries for each user if they don't exist
	  if (UserSetting::model()->count() == 0) {
	    $users = User::model()->findAll();
	    foreach ($users as $u) {
	      UserSetting::model()->initialize($u['id']);
	    }
	  }
	}

	public function actionInitializeFolders() {
	  $f = new Folder();
	  $f->initialize();
	}
	  
}