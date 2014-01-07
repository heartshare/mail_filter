<?php

/**
 * This is the model class for table "{{message}}".
 *
 * The followings are the available columns in table '{{message}}':
 * @property integer $id
 * @property integer $account_id
 * @property integer $folder_id
 * @property integer $user_id
 * @property integer $sender_id
 * @property string $message_id
 * @property string $subject
 * @property string $body_text
 * @property string $body_html
 * @property integer $status
 * @property integer $cached
 * @property integer $udate
 * @property string $created_at
 * @property string $modified_at
 *
 * The followings are the available model relations:
 * @property Sender $sender
 * @property Account $account
 * @property Users $user
 */
class Message extends CActiveRecord
{
  const STATUS_CREATED=0; // found in inbox
  const STATUS_FILTERED=10; // moved to @filtered
  const STATUS_REVIEW = 15; // moved to review folder
  const STATUS_ROUTED = 20; // moved to destination folder
  const STATUS_TRAINED=50; // found in training folder
  const STATUS_DELETED = 90; // moved to destination folder
  
  public $email;
  public $folder_name;

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Message the static model class
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
		return '{{message}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('message_id, subject, modified_at', 'required'),
			array('account_id, folder_id, user_id, sender_id, status, cached, udate', 'numerical', 'integerOnly'=>true),
			array('message_id, subject', 'length', 'max'=>255),
			array('body_text, body_html, created_at', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, account_id, folder_id, user_id, sender_id, message_id, subject, body_text, body_html, status, cached, udate, email, folder_name, created_at, modified_at', 'safe', 'on'=>'search'), // 
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
			'sender' => array(self::BELONGS_TO, 'Sender', 'sender_id'),
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
			'folder_id' => 'Folder',
			'user_id' => 'User',
			'sender_id' => 'Sender',
			'message_id' => 'Message',
			'subject' => 'Subject',
			'body_text' => 'Body Text',
			'body_html' => 'Body Html',
			'status' => 'Status',
			'cached' => 'Cached',
			'udate' => 'Udate',
			'created_at' => 'Created At',
			'modified_at' => 'Modified At',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;
		$sender_table = Sender::model()->tableName();
    $email_sql = "(select CONCAT(personal,' <',email,'>') from $sender_table st where st.id = t.sender_id)";
		$folder_table = Folder::model()->tableName();
    $folder_name_sql = "(select name from $folder_table ft where ft.id = t.folder_id)";
    $criteria->select = array(
            '*',
            $email_sql . " as email",
            $folder_name_sql . " as folder_name",
        );    
		$criteria->compare('id',$this->id);
		$criteria->compare('account_id',$this->account_id);
		$criteria->compare('folder_id',$this->folder_id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('sender_id',$this->sender_id);
		$criteria->compare('message_id',$this->message_id,true);
		$criteria->compare('subject',$this->subject,true);
		$criteria->compare('body_text',$this->body_text,true);
		$criteria->compare('body_html',$this->body_html,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('cached',$this->cached);
		$criteria->compare('udate',$this->udate);
		$criteria->compare('created_at',$this->created_at,true);
		$criteria->compare('modified_at',$this->modified_at,true);
    $criteria->compare($folder_name_sql, $this->folder_name);		  
    $criteria->compare($email_sql, $this->email,true);		    
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
      'pagination' => array(
                  'pageSize' => Yii::app()->params['postsPerPage'],
             ),
      'sort' => array(
        'defaultOrder' => 'created_at desc',
          'attributes' => array(
              // order by
              'created_at' => array(
                  'asc' => 'created_at ASC',
                  'desc' => 'created_at DESC',
              ),
              // order by
              'email' => array(
                  'asc' => 'email ASC',
                  'desc' => 'email DESC',
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

  public function add($user_id,$account_id,$folder_id,$sender_id,$message_id,$subject,$udate) {
    // to do - check for dups
    $m = Message::model()->findByAttributes(array('account_id'=>$account_id,'message_id'=>$message_id));
    if (empty($m)) {
      $message = new Message;
      $message->user_id = $user_id;
      $message->account_id = $account_id;
      $message->folder_id = $folder_id;
      $message->sender_id = $sender_id;
      $message->message_id = $message_id;
      $message->subject = $subject;
      $message->udate = $udate;
      $message->status = self::STATUS_CREATED;
      $message->created_at =new CDbExpression('NOW()'); 
      $message->modified_at =new CDbExpression('NOW()');          
      $message->save();
      $message_id = $message->id;
    } else {
      $message_id = $m->id;
    }
    return $message_id;
  }

  public function setRecipient($message_id,$recipient_id=0) {
    $update_rx = Yii::app()->db->createCommand()->update(Yii::app()->getDb()->tablePrefix.'message',array('recipient_id'=>$recipient_id),'id=:id', array(':id'=>$message_id));
  }

  public function setStatus($message_id,$new_status) {
    $update_status = Yii::app()->db->createCommand()->update(Yii::app()->getDb()->tablePrefix.'message',array('status'=>$new_status),'id=:id', array(':id'=>$message_id));
  }

  public function setFolder($message_id,$folder_id) {
    // record folder msg is moved to
    $update_folder = Yii::app()->db->createCommand()->update(Yii::app()->getDb()->tablePrefix.'message',array('folder_Id'=>$folder_id),'id=:id', array(':id'=>$message_id));
  }
  
  
  public function enter($m,$u,$h) {
    // authenticate secure message url entry from digest
    $message_id = $m;
    $activkey = $u;
    $message_hash = $h;
    $m = Message::model()->findByPk($message_id);
    if (empty($m)) return false;
    // get message and check hash
    if (hash('md5',substr($m['body'],0,100)) <> $message_hash) return false;
    // get recipient_id
    $recipient_id = $m['recipient_id'];    
    // check that activkey matches that of recipient_id of message
    $find = User::model()->notsafe()->findByAttributes(array('id'=>$recipient_id,'activkey'=>$activkey));
		if (isset($find)&&$find->status) {
		  Mailbox::model()->autoLogin($find->username);
		  return true;
		} else {
		  return false;
		}
  }
  
  public function processDigests() {
    $time_start = time();
    echo 'Time start: '.$time_start;lb();
    // find all users with upcoming digests to be sent via user_settings
    $r = new Remote();
    $usersettings = UserSetting::model()->digests_due()->findAll(array('order'=>'digestNext'));
    foreach ($usersettings as $us) {
      $user_id = $us['user_id'];
      if ($r->isQuietHours($user_id)) continue; // skip during quiet hours
      echo 'User:'.$user_id;lb();
      //$u = User::model()->notsafe()->findByPk($user_id);
      // send digest for each of their accounts
      $accounts = Account::model()->findAllByAttributes(array('user_id'=>$user_id));
      foreach ($accounts as $account) {
        $account_id = $account['id'];      
        echo 'Account:'.$account_id;lb();        
        $r->setDefaultPaths($account_id);
        // use mail_domain ini file settting to send digest
        $from = 'Filtered <no_reply@'.Yii::app()->params['mail_domain'].'>';
        $cred = Account::model()->getCredentials($account->cred);
        $to = $cred[0];
        unset($cred);
        $message_count = 0;
        $subject ='Message Digest';
        if ($us['digestLast'] == 0) $us['digestLast'] = time()-(7*24*60*60); // one week back by default
        // build top of digest
        $body ='<p><em>This digest consists of messages you have received since '.date("M j, g:i a",$us['digestLast']).'</em></p>';
      // build digest sections for each folder: review, then each folder set to be included in the digest
        $criteria = new CDbCriteria(array('order'=>'name ASC'));
        $folders = Folder::model()->findAllByAttributes(array('account_id'=>$account_id,'user_id'=>$user_id,'digest'=>1),$criteria);
        // prepend the review folder
        $review['name']=$r->path_review;
        $review['id']=0;
        array_unshift($folders,$review); // prepends review folder first
        foreach ($folders as $f) {
          $section ='';
          echo 'Folder: '.$f['name'];lb();
          if (strtolower($f['name'])=='inbox' or $f['name']==$r->path_block) continue; // skip inbox & blocking folder
          $folder_id = $f['id'];
          $mailbox = $f['name'];
          if ($f['id']==0) {
            // review folder
            // find all messages since $digest->digestLast
            $message_list = Message::model()->account_of($account_id)->in_review()->since($us['digestLast'])->findAll(array('order'=>'id desc'));            
            // build folder heading
            $section.='<p><strong>The following messages in your review folder need your attention:</strong><br /><ul>';
          } else {
            // regular folder
            // find all messages since $digest->digestLast
            $message_list = Message::model()->account_of($account_id)->in_folder($folder_id)->since($us['digestLast'])->findAll(array('order'=>'id desc'));
            // build folder heading
            $section.='<p><strong>Messages from '.$mailbox.'</strong><br /><ul>';
          }
          // build message section of for this folder
          if (!empty($message_list)) {
            foreach ($message_list as $m) {
              $msg_line = getSenderNameForDigest($m['sender_id']).': '.$m['subject'];
              // to do - add subject, body and link to html body
              $section.='<li>'.$msg_line.'</li>';
              $message_count +=1;
            }
          } else {
            $section.='No messages in this folder.';
          }
          $body.=$section.'</ul></p>';
      } // end of folder loop
        // build end of digest
        $digest_footer = '<p>End of your digest.</p><p>';
        if (Yii::app()->params['version']=='basic') {
          $digest_footer.='If you would like direct links to view messages on the web from the digest, <a href="http://jeffreifman.com/filtered-open-source-imap-mail-filtering-software-for-php/feature-summary/">upgrade to the advanced module</a>. ';
        }
        $digest_footer.='<a href="http://jeffreifman.com/filtered-open-source-imap-mail-filtering-software-for-php/">Get more information on Filtered</a>.</p>';
        $body.=$digest_footer;
        if ($message_count>0) {
          // send the digest via mailgun to the recipient
          $yg = new Yiigun();
          $yg->send_html_message($from, $to, $subject,$body);          
        }
      } // end of account loop
      // update digestLast, digestNext
      $dlast = $time_start;
      $dnext = $time_start+(60*60*$us['digestInterval']);
       // update user settings row for this user's digest
      $update_settings = Yii::app()->db->createCommand()->update(Yii::app()->getDb()->tablePrefix.'user_setting',array('digestLast'=>$dlast,'digestNext'=>$dnext),'id=:id', array(':id'=>$us['id']));  // note $us['id'] is id of user setting table, not $user_id
    } // end user loop
    $xtime = time()-$time_start;
    echo 'End time: '.time();lb();
    $il = new ImapLog();
    $il->add('processDigests','',$xtime);    
  }  
  
  // scoping functions
  public function scopes()
      {
          return array(            
              'active'=>array(
                  'condition'=>'status<>'.self::STATUS_DELETED, 
              ),
              'in_review'=>array(
                'condition'=>'status='.self::STATUS_REVIEW,                 
              ),
          );
      }		
  
  public function in_folder($folder_id = 0)
   {
     $this->getDbCriteria()->mergeWith( array(
       'condition'=>'folder_id='.$folder_id,
     ));
       return $this;
   }
       
  public function owned_by($user_id = 0)
  {
    $this->getDbCriteria()->mergeWith( array(
      'condition'=>'user_id='.$user_id,
    ));
      return $this;
  }


  public function account_of($account_id=0)
  {
    $this->getDbCriteria()->mergeWith( array(
      'condition'=>'account_id='.$account_id,
    ));
      return $this;
  }
  
    // scope of messages sent by a specific sender
  public function since($tstamp=0)
  {
    $this->getDbCriteria()->mergeWith( array(
      'condition'=>'(UNIX_TIMESTAMP(created_at)>'.$tstamp.')',
    ));
      return $this;
  }
  
  public function authenticate($message_id,$user_id) {
    // make sure this message is owned by this user
    if (Message::model()->countByAttributes(array('recipient_id'=>$user_id,'id'=>$message_id))>0)
      return true;
    else
      return false;
  }
  
}