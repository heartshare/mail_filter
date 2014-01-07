<?php

class TestController extends Controller
{
  public $tablePrefix;
  public $tableName;
  
	public $layout='//layouts/main';

  /**
   * @return array action filters
   */
  public function filters()
  {
  	return array(
  		'accessControl', // perform access control for CRUD operations
  		'setup + populate + setEid'
  	);
  }

   public function filterSetup($filterChain)
  {
    $this->tablePrefix = Yii::app()->getDb()->tablePrefix;
         $filterChain->run();
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
				'actions'=>array('parse','test','review','folder'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 
				'actions'=>array(''),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 
				'actions'=>array('populate,setEid,thumb'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	
	public function actionFolder() {
	  $r = new Remote();
	  $r->createMailbox(1, '+Testing');
  	$r->renameMailbox(1, '+Testing','+Rename');
	}
	
	public function actionReview() {
	  $user_id = 1;
	  $qh = new QuietHour();
	  $qh->debug();
	  $a = new Advanced();
    if ($a->isQuietHours($user_id)) {
      echo 'Yes, QUIET HOURS';
    } else
    {
      echo 'No, noisy hours!';
    }    
	}
	
	public function actionTest() {
	  $m = new Message();
    $digest = $m->processDigests();
    yexit();
	  // is it quiet hours
	//  $r = new Remote();
//    $r->processDetectTraining();
//    $r->processRecentTrainings();
    //$r->listRecipients(2);
    // $r->listFolders(2);
    //Yii::import('application.vendor.*');
    //Yii::setPathOfAlias('Zend',Yii::getPathOfAlias('application.vendor.Zend'));
    //$r->listFolders(1);
    //$r->testSocket();
//    $r->testGmail();
    //$r->encrypt_account('jef@newscloud.com','apple_sander_11');
   // $r->testHeaders(2);
    ////$r->legacyTrain("Inbox.+SaneBulk",4);
    //$r->legacyTrain("Inbox.+SaneLater",4);
    //$r->legacyTrain("Inbox.+SaneNews",4);
    //$f = new Folder();
    //$f->initialize(1,2);
    //$r->testHeaders();
    //$r->processReview(225,2);
//    $r->checkFolder("INBOX");
//    $r->trainByFolder("@SaneLater");
//  $r->createMailbox("@Filtering/Spam");
   //  $r->initializeMailboxFolders();
    /*
    $status = imap_status($imap,$imap_url."@Filtering" , SA_ALL);
        if ($status) {
            echo "your new mailbox has the following status:<br />\n";
            echo "Messages:   " . $status->messages    . "<br />\n";
            echo "Recent:     " . $status->recent      . "<br />\n";
            echo "Unseen:     " . $status->unseen      . "<br />\n";
            echo "UIDnext:    " . $status->uidnext     . "<br />\n";
            echo "UIDvalidity:" . $status->uidvalidity . "<br />\n";    
          }*/
	}
	
	public static function actionParse() {
    $tolist='apple@beta.com,
      Michael Ferranti <ferranti.michael@gmail.com>
      

    "Jeff Reifman" <jeff@reifman.org>, <apple@app2.com>,jeff <jeff@smith.com>';
    //Yii::import('ext.Mail_RFC822');
    include('Mail/RFC822.php');

    $parser = new Mail_RFC822();
    // replace the backslash quotes 
    $tolist=str_replace('\\"','"',$tolist); 
    // split the elements by line and by comma 
    $to_email_array = preg_split ("(\r|\n|,)", $tolist, -1, PREG_SPLIT_NO_EMPTY); 
    $num_emails = count ($to_email_array); 
    echo $num_emails;
    for ($count = 0; $count < $num_emails && $count <= 500; $count++) 
    {
    $toAddress=trim($to_email_array[$count]);
    if ($toAddress<>'') {
      $addresses = $parser->parseAddressList('my group:'.$toAddress,'bushsucks.com', false,true); 
      var_dump($addresses);lb();
      
    }
    //$toAddress=$addresses[0]->mailbox.'@'.$addresses[0]->host; 
    //if ($this->utilObj->checkEmail($toAddress)) { 
    // store it or send it or whatever you want to do 
    //}	
    }
	}
	public function actionThumb() {
	  $thumb=Yii::app()->phpThumb->create('images/kez-alien.jpg');
  	$thumb->resize(100,100);
  	$thumb->save('images/thumb.jpg');
	}
	
	public function actionPopulate() {
    $users = array (
      array ('jeff@newscloud.com','JeffNC','Jeff','NewsCloud'),
      array ('jeff@skymail.me','JeffSKY','Sky','Mail'),
      array ('rob@skymail.me','Rob','Rob','Smith'),
      array ('newscloud@gmail.com','NewsCloud','News','Cloud'),
      array ('cara@skymail.me','Cara','Cara','Mail'),
      array ('snowandtrees@gmail.com','Snow','Snow','Mail'),
      array ('jeff@reifman.org','Jeff','Jeff','Reifman'),      
    );
    foreach ($users as $u) {
  	  $this->addUser($u);
    }
    $places = array (
    array ( 'Geogram Fans', 'fans',Place::KIND_SPECIAL),
      array ( 'Greenlake Manor', 'glmanor',Place::KIND_ESTABLISHMENT),
      array ( 'Joe Bar', 'joebar',Place::KIND_ESTABLISHMENT),
      array ( 'Herkimer Coffee', 'herkphin',Place::KIND_ESTABLISHMENT),
  );
  foreach ($places as $i)
	  $this->addPlace($i);

  // add memberships
  $this->addMember('jeff@reifman.org','fans');
  $this->addMember('jeff@newscloud.com','fans');
    $this->addMember('jeff@newscloud.com','glmanor');
    $this->addMember('rob@skymail.me','glmanor');
    $this->addMember('jeff@reifman.org','glmanor');
    $this->addMember('newscloud@gmail.com','glmanor');
    $this->addMember('cara@skymail.me','glmanor');
    $this->addMember('snowandtrees@gmail.com','joebar');
    $this->addMember('cara@skymail.me','joebar');    
    $this->addMember('jeff@reifman.org','joebar');
    $this->addMember('snowandtrees@gmail.com','herkphin');
    $this->addMember('cara@skymail.me','herkphin');    
    $this->addMember('jeff@reifman.org','herkphin');
  echo 'completed';
	}

  private function addPlace($place) {
    $checkDup=Place::model()->find(array(
        'select'=>'slug',
        'condition'=>'slug=:slug',
        'params'=>array(':slug'=>$place[1]))
      );
    if ($checkDup === NULL) {
    $x = Yii::app()->db->createCommand()->insert($this->tablePrefix.'place',array('title'=>$place[0],'slug'=>$place[1],'kind'=>$place[2],'created_at'=>new CDbExpression('NOW()'),'owner_id'=>1,'neighborhood_id'=>1));
    return Yii::app()->db->getLastInsertID();
    } else 
      return false;
  }
  
  private function addUser($user) {
      $checkDup=User::model()->find(array(
          'select'=>'email',
          'condition'=>'email=:email',
          'params'=>array(':email'=>$user[0]))
        );
      if ($checkDup === NULL) {
            $sourcePassword = 'pwd123';
        $activkey =Yii::app()->getModule('user')->encrypting(microtime().$sourcePassword);
            $password=Yii::app()->getModule('user')->encrypting($sourcePassword);

          $x = Yii::app()->db->createCommand()->insert($this->tablePrefix.'users',array('username'=>$user[1],'email'=>$user[0],'status'=>1,'superuser'=>0,'activkey'=>$activkey,'password'=>$password));
          $last_id = Yii::app()->db->getLastInsertID();
          $y = Yii::app()->db->createCommand()  ->insert($this->tablePrefix.'profiles',array('user_id'=>$last_id,'last_name'=>$user[3],'first_name'=>$user[2]));
          return $last_id;
        
      } else
        return false;
    
    }

    private function addMember($email,$slug) {
        $u=User::model()->find(array(
            'select'=>'id',
            'condition'=>'email=:email',
            'params'=>array(':email'=>$email))
          );
          $p=Place::model()->find(array(
              'select'=>'id',
              'condition'=>'slug=:slug',
              'params'=>array(':slug'=>$slug))
            );
          $x=  Place::model()->join($u->id,$p->id);
    }


}