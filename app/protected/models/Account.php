<?php

/**
 * This is the model class for table "{{account}}".
 *
 * The followings are the available columns in table '{{account}}':
 * @property integer $id
 * @property integer $user_id
 * @property string $name
 * @property string $address
 * @property string $cred
 * @property string $salt
 * @property integer $provider
 * @property string $last_checked
 * @property string $created_at
 * @property string $modified_at
 *
 * The followings are the available model relations:
 * @property Users $user
 * @property Folder[] $folders
 * @property Message[] $messages
 * @property Sender[] $senders
 */
class Account extends CActiveRecord
{
  const PROVIDER_GMAIL = 0;
  const PROVIDER_FASTMAIL = 10;
  const PROVIDER_DOVECOT = 20;
  const PROVIDER_OTHER = 100;
  public $username;
  public $password;
  
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Account the static model class
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
		return '{{account}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, address, modified_at', 'required'),
			array('user_id, provider', 'numerical', 'integerOnly'=>true),
			array('name, address, cred, salt', 'length', 'max'=>255),
			array('last_checked, created_at', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, user_id, name, address, cred, salt, provider, last_checked, created_at, modified_at', 'safe', 'on'=>'search'),
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
			'user' => array(self::BELONGS_TO, 'Users', 'user_id'),
			'folders' => array(self::HAS_MANY, 'Folder', 'account_id'),
			'messages' => array(self::HAS_MANY, 'Message', 'account_id'),
			'senders' => array(self::HAS_MANY, 'Sender', 'account_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'user_id' => 'User',
			'name' => 'Name',
			'address' => 'Address',
			'cred' => 'Cred',
			'salt' => 'Salt',
			'provider' => 'Provider',
			'last_checked' => 'Last Checked',
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
		$criteria->compare('id',$this->id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('cred',$this->cred,true);
		$criteria->compare('salt',$this->salt,true);
		$criteria->compare('provider',$this->provider);
		$criteria->compare('last_checked',$this->last_checked,true);
		$criteria->compare('created_at',$this->created_at,true);
		$criteria->compare('modified_at',$this->modified_at,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
  public function owned_by($user_id = 0)
  {
    $this->getDbCriteria()->mergeWith( array(
      'condition'=>'user_id='.$user_id,
    ));
      return $this;
  }
  
  public function getTypeOptions()
  {
    return array(
      self::PROVIDER_GMAIL=>'Gmail',
      self::PROVIDER_FASTMAIL=>'FastMail',
      self::PROVIDER_DOVECOT=>'Dovecot',
      self::PROVIDER_OTHER=>'Other',
       );
   }		

   public function listRemoteFolders($id) {
     $r = new Remote();
     return $r->listFolders($id);
   }
   
    public function getAccountList()
    {
      $accountsArray = CHtml::listData(Account::model()->findAll(), 'id', 'name');
      return $accountsArray;
   }	
  
	 public function touch($account_id) {
	   $last_checked =new CDbExpression('NOW()'); 
     $update_lc = Yii::app()->db->createCommand()->update(Yii::app()->getDb()->tablePrefix.'account',array('last_checked'=>$last_checked),'id=:id', array(':id'=>$account_id));
   }

   public function createCredentials($username,$password) {
     $salt = $this->random_string(5,3).'::'.$this->random_string(5,3);
     $salts = explode('::',$salt);
     $str = $salts[0].'::'.$username.'::'.$password.'::'.$salts[1];
     $td = mcrypt_module_open('tripledes', '', 'ecb', '');
     $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
     $account_salt = Yii::app()->params['account_salt'];     
     mcrypt_generic_init($td, $account_salt, $iv);
     $encrypted_data = mcrypt_generic($td, $str);
     mcrypt_generic_deinit($td);
     mcrypt_module_close($td);
     $result = array($salt,$encrypted_data);
     return $result;
   }
      
   public function getCredentials($cred) {     
     $account_salt = Yii::app()->params['account_salt'];     
     $cred = base64_decode($cred);
     $td = mcrypt_module_open('tripledes', '', 'ecb', '');
     $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
     mcrypt_generic_init($td, $account_salt, $iv);
     $result = mdecrypt_generic($td,$cred);
     mcrypt_generic_deinit($td);
     mcrypt_module_close($td);
     $cred = explode('::',$result);
     array_shift($cred);
     array_pop($cred);   
     return $cred;
   }

   public function random_string($num_characters=5,$num_digits=3)
   {
     // via http://salman-w.blogspot.com/2009/06/generate-random-strings-using-php.html
       $character_set_array = array();
       $character_set_array[] = array('count' => $num_characters, 'characters' => 'abcdefghijklmnopqrstuvwxyz');
       $character_set_array[] = array('count' => $num_digits, 'characters' => '0123456789');
       $temp_array = array();
       foreach ($character_set_array as $character_set) {
           for ($i = 0; $i < $character_set['count']; $i++) {
               $temp_array[] = $character_set['characters'][rand(0, strlen($character_set['characters']) - 1)];
           }
       }
       shuffle($temp_array);
       return implode('', $temp_array);
   }	
 
 public function purge($account_id) {
  Sender::model()->deleteAll('account_id = :account_id',array(':account_id'=>$account_id));
  Message::model()->deleteAll('account_id = :account_id',array(':account_id'=>$account_id));
  Folder::model()->deleteAll('account_id = :account_id',array(':account_id'=>$account_id));
 }  
  
}