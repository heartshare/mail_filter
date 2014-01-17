<?php

/**
 * This is the model class for table "{{sender}}".
 *
 * The followings are the available columns in table '{{sender}}':
 * @property integer $id
 * @property integer $account_id
 * @property integer $user_id
 * @property string $personal
 * @property string $email
 * @property string $mailbox
 * @property string $host
 * @property integer $is_verified
 * @property integer $folder_id
 * @property integer $last_folder_id
 * @property integer $exclude_quiet_hours
 * @property string $created_at
 * @property string $modified_at
 * @property integer $last_trained
 * @property integer $alert
 * @property integer $alert_sent
 * @property integer $expire
 *
 * The followings are the available model relations:
 * @property Message[] $messages
 * @property Account $account
 * @property Users $user
 */
class Sender extends CActiveRecord
{
  const ALERT_NO = 0;
   const ALERT_YES = 10;

   const EQH_NO = 0;
    const EQH_YES = 10;

   const EXPIRES_NEVER = 0;
   const EXPIRES_DAY = 10;
   const EXPIRES_WEEK = 20;
   const EXPIRES_MONTH = 30;
   const EXPIRES_QUARTER = 40;
   const EXPIRES_YEAR = 100;
   
   public $message_count;
   public $account_name;
   public $folder_name;
   
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Sender the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{sender}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('email, mailbox, host, modified_at', 'required'),
			array('account_id, user_id, is_verified, folder_id, last_folder_id, exclude_quiet_hours, last_trained, alert, alert_sent, expire', 'numerical', 'integerOnly'=>true),
			array('personal, email, mailbox, host', 'length', 'max'=>255),
			array('created_at', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, account_id, user_id, personal, email, mailbox, host, is_verified, folder_id, last_folder_id, created_at, modified_at, last_trained, alert, alert_sent, expire, message_count,account_name,folder_name', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'messages' => array(self::HAS_MANY, 'Message', 'sender_id'),
			'account' => array(self::BELONGS_TO, 'Account', 'account_id'),
			'user' => array(self::BELONGS_TO, 'Users', 'user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'account_id' => 'Account',
			'user_id' => 'User',
			'personal' => 'Personal',
			'email' => 'Email',
			'mailbox' => 'Mailbox',
			'host' => 'Host',
			'is_verified' => 'Is Verified',
			'folder_id' => 'Folder',
			'last_folder_id' => 'Last Folder',
			'exclude_quiet_hours' => 'Exclude from Quiet Hours',
			'created_at' => 'Created At',
			'modified_at' => 'Modified At',
			'last_trained' => 'Last Trained',
			'alert' => 'Alert',
			'alert_sent' => 'Alert Sent',
			'expire' => 'Expire',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search($default_sort='t.email asc')
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;
		$account_table = Account::model()->tableName();
    $account_name_sql = "(select name from $account_table acct where acct.id = t.account_id)";
		$message_table = Message::model()->tableName();
    $message_count_sql = "(select count(*) from $message_table mt where mt.sender_id = t.id)";
		$folder_table = Folder::model()->tableName();
    $folder_name_sql = "(select name from $folder_table ft where ft.id = t.folder_id)";
    $criteria->select = array(
            '*',
            $message_count_sql . " as message_count",
            $account_name_sql . " as account_name",
            $folder_name_sql . " as folder_name",
        );    
		$criteria->compare('id',$this->id);
		$criteria->compare('account_id',$this->account_id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('personal',$this->personal,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('mailbox',$this->mailbox,true);
		$criteria->compare('host',$this->host,true);
		$criteria->compare('is_verified',$this->is_verified);
		$criteria->compare('folder_id',$this->folder_id);
		$criteria->compare('last_folder_id',$this->last_folder_id);
		$criteria->compare('created_at',$this->created_at,true);
		$criteria->compare('modified_at',$this->modified_at,true);
		$criteria->compare('last_trained',$this->last_trained);
		$criteria->compare('alert',$this->alert);
		$criteria->compare('alert_sent',$this->alert_sent);
		$criteria->compare('expire',$this->expire);
    // where
    $criteria->compare($message_count_sql, $this->message_count);		
    $criteria->compare($account_name_sql, $this->account_name);		
    $criteria->compare($folder_name_sql, $this->folder_name);		
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
      'pagination' => array(
                  'pageSize' => Yii::app()->params['postsPerPage'],
             ),
      'sort' => array(
        'defaultOrder' => $default_sort,
                        'attributes' => array(
                            // order by
                            'message_count' => array(
                                'asc' => 'message_count ASC',
                                'desc' => 'message_count DESC',
                            ),
                            'account_name' => array(
                                'asc' => 'account_name ASC',
                                'desc' => 'account_name DESC',
                            ),
                            'folder_name' => array(
                                'asc' => 'folder_name ASC',
                                'desc' => 'folder_name DESC',
                            ),
                            '*',
                        ),
                    ),        
		));
	}

	public function isNew($account_id,$email) {
	  // detects if sender is new in this account
    $s = Sender::model()->findByAttributes(array('account_id'=>$account_id,'email'=>$email));
    if (empty($s))
      return true; // new sender
    else
      return false;
	}
	
	public function isBot($email) {
	  // detects if sender email is likely a bot
	  $bot_str = array('auto-communication','no-reply','donotreply','noreply','mailer-daemon','autoresponder');
	  foreach ($bot_str as $str) {
      if (stristr($email,$str)!==false) return true;	    
	  }
    return false;
	}
	
  public function touchLastEmailed($sender_id) {
    $update_folder = Yii::app()->db->createCommand()->update(Yii::app()->getDb()->tablePrefix.'sender',array('last_emailed'=>time()),'id=:id', array(':id'=>$sender_id));
  }

	
	public function add($user_id,$account_id,$personal, $mailbox, $host,$folder_id=0) {
    $email = $mailbox.'@'.$host;
    $s = Sender::model()->findByAttributes(array('account_id'=>$account_id,'email'=>$email));
    if (empty($s)) {
      $sender = new Sender;
      $sender->user_id = $user_id;
      $sender->account_id = $account_id;
      $sender->personal = $personal;
      $sender->mailbox = $mailbox;
      $sender->host = $host;
      $sender->email = $email;
      $sender->created_at =new CDbExpression('NOW()'); 
      $sender->modified_at =new CDbExpression('NOW()');          
      $sender->folder_id = $folder_id;
      $sender->save();
      $sender_id = $sender->id;
    } else {
      $sender_id = $s->id;
    }
    return $sender_id;
  }

  public function touchLastTrainingProcessed($sender_id) {
    $update_folder = Yii::app()->db->createCommand()->update(Yii::app()->getDb()->tablePrefix.'sender',array('last_trained_processed'=>1),'id=:id', array(':id'=>$sender_id));
  }
  
  public function setFolderCheckAccount($sender_id,$folder_id) {
    $f=Folder::model()->findByPk($folder_id);
    $s=Sender::model()->findByPk($sender_id);
    if ($s->folder_id >0) {
      $s->last_folder_id = $folder_id;
      $s->save();
    }
    // folder must belong to same account as sender
    if ($f->account_id == $s->account_id)
      $this->setFolder($sender_id,$folder_id);
  }
  
  public function setFolder($sender_id,$folder_id) {
    // set training for sender to a folder and update last_trained timestamp
    $update_folder = Yii::app()->db->createCommand()->update(Yii::app()->getDb()->tablePrefix.'sender',array('folder_id'=>$folder_id,'last_trained'=>time(),'last_trained_processed'=>0),'id=:id', array(':id'=>$sender_id));
  }	

  public function touchExpires($sender_id) {
    $update_last_expired = Yii::app()->db->createCommand()->update(Yii::app()->getDb()->tablePrefix.'sender',array('last_expired'=>time()),'id=:id', array(':id'=>$sender_id));
  }	


   public function getFolderOptions($account_id = 0)
   {
     if ($account_id > 0) {
       $foldersArray = CHtml::listData(Folder::model()->findAllByAttributes(array('account_id'=>$account_id)), 'id', 'name');       
     } else {
       // find folders across all accounts
       $foldersArray = CHtml::listData(Folder::model()->findAll(array('order'=>'account_id asc, name asc')), 'id', 'namewithaccount');       
     }
     return $foldersArray;
  }	

  public function owned_by($user_id = 0)
  {
    $this->getDbCriteria()->mergeWith( array(
      'condition'=>'user_id='.$user_id,
    ));
      return $this;
  }

  public function account_of($account_id)
  {
    $this->getDbCriteria()->mergeWith( array(
      'condition'=>'account_id='.$account_id,
    ));
      return $this;
  }
  
    public function getExcludeQuietHoursOptions()
    {
      return array(self::EQH_NO => 'Subject to quiet hours',self::EQH_YES => 'Exclude from quiet hours!');
     }		
  
  public function getExpireOptions()
  {
    return array(self::EXPIRES_NEVER => 'Never Expires',self::EXPIRES_DAY => 'Expires after 24 hours', self::EXPIRES_WEEK => 'Expires after a week', self::EXPIRES_MONTH => 'Expires after a month', self::EXPIRES_QUARTER => 'Expires after three months', self::EXPIRES_YEAR => 'Expires after a year');
   }		

   public function getAlertOptions()
   {
     return array(self::ALERT_NO => 'No alerts',self::ALERT_YES => 'Yes, send alerts!');
    }		
     
  // scopes
  public function scopes()
      {
          return array(            
            'recent'=>array(
                'condition'=>'modified_at > DATE_SUB(NOW(),INTERVAL 3 DAY)',
            ),
            'recently_trained'=>array(
              'condition'=>'folder_id>0 and last_trained_processed=0 and last_trained > '.(time()-24*60*60), // within last day              
            ),
            'recently_emailed'=>array(
              'condition'=>'last_emailed > '.(time()-24*60*60), // within last day              
            ),
              'trained'=>array(
                  'condition'=>'folder_id>0', 
              ),
              'untrained'=>array(
                  'condition'=>'folder_id=0', 
              ),
          );
      }		  
}