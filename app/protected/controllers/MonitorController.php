
<?php

class MonitorController extends Controller
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
				'actions'=>array('index','checkdisk','lastcron','checkdb'),
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
	
	public function actionIndex()
	{
    echo 'Beginning monitor'.lb();

    $monitor = new Monitor;
    $errors = false;
    //    $monitor->notify('Starting up','just green light me');      
    
    // Check that inbox messages aren't stale
    if (Inbox::model()->isStale() && !$errors) {
      $monitor->notify('Inbox msgs are stale','they are not ok');      
      $errors=true;
    }

/*
  // Disable heartbeat for now
  if (!$errors) {
    // only notify me with heartbeat every four hours
    if ((date('G')%4) == 0 and date('i')<10) {
      $monitor->notify('Monitor heart beat is okay at'.(date('G')%4).'-'.date('i'),'Relax, chill - everything is fine');            
    }
  }
*/

  echo 'Monitor complete'.lb();
	}	

  public function actionCheckdb() {
    // code to test the mysql db
    $result = Inbox::model()->count();
    if ($result>0)
      echo 'ok';
    else
      echo 'error';
    yexit();
  }
  
	public function actionLastcron() {
	  // echo timestamp of last cron execution
	  $file = file_get_contents('./protected/runtime/cronstamp.txt',FILE_USE_INCLUDE_PATH);
	  echo $file;
    yexit();	  
	}
	
	public function actionCheckdisk() {
	  // echo free disk space
	  $df = disk_free_space("/");
    echo $df; 
    yexit();	  
	}
}
