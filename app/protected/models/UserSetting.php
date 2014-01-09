<?php

/**
 * This is the model class for table "{{user_setting}}".
 *
 * The followings are the available columns in table '{{user_setting}}':
 * @property integer $id
 * @property integer $user_id
 * @property integer $digestOn
 * @property integer $digestInterval
 * @property string $digestNext
 * @property string $digestLast
 * @property string $created_at
 * @property string $modified_at
 * @property integer $digestIncludeCaption
 * @property integer $digestUseAutologin
 * @property string $pushover_token
 * @property string $pushover_device
 * @property string $timezone
 *
 * The followings are the available model relations:
 * @property Users $user
 */
class UserSetting extends CActiveRecord
{
  const INBOX_AGE_NONE = 0;
  const INBOX_AGE_DAY = 10;
  const INBOX_AGE_WEEK = 20;
  const INBOX_AGE_MONTH = 30;
  const INBOX_AGE_QUARTER = 50;
  const INBOX_AGE_YEAR = 100;
  const WHITELIST_NO = 0;
  const WHITELIST_YES = 10;
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return UserSetting the static model class
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
		return '{{user_setting}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, digestOn, digestInterval, digestIncludeCaption, digestUseAutologin,inbox_age,use_whitelist', 'numerical', 'integerOnly'=>true),
			array('digestNext, digestLast', 'length', 'max'=>20),
			array('pushover_token, pushover_device', 'length', 'max'=>32),
			array('timezone', 'length', 'max'=>255),
			array('created_at', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, user_id, digestOn, digestInterval, digestNext, digestLast, created_at, modified_at, digestIncludeCaption, digestUseAutologin, pushover_token, pushover_device, timezone', 'safe', 'on'=>'search'),
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
			'digestOn' => 'Digest On',
			'digestInterval' => 'Digest Interval',
			'digestNext' => 'Digest Next',
			'digestLast' => 'Digest Last',
			'created_at' => 'Created At',
			'modified_at' => 'Modified At',
			'digestIncludeCaption' => 'Digest Include Caption',
			'digestUseAutologin' => 'Digest Use Autologin',
			'pushover_token' => 'Pushover Token',
			'pushover_device' => 'Pushover Device',
			'timezone' => 'Timezone',
			'inbox_age' => 'Freshen Inbox',
			'use_whitelist' => 'Enable Whitelisting',
			'view_messages' => 'View Messages',
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
		$criteria->compare('digestOn',$this->digestOn);
		$criteria->compare('digestInterval',$this->digestInterval);
		$criteria->compare('digestNext',$this->digestNext,true);
		$criteria->compare('digestLast',$this->digestLast,true);
		$criteria->compare('created_at',$this->created_at,true);
		$criteria->compare('modified_at',$this->modified_at,true);
		$criteria->compare('digestIncludeCaption',$this->digestIncludeCaption);
		$criteria->compare('digestUseAutologin',$this->digestUseAutologin);
		$criteria->compare('pushover_token',$this->pushover_token,true);
		$criteria->compare('pushover_device',$this->pushover_device,true);
		$criteria->compare('timezone',$this->timezone,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function useWhitelisting($user_id) {
	  $us=UserSetting::model()->findByAttributes(array('user_id'=>$user_id));
	  if ($us['use_whitelist'] == self::WHITELIST_YES)
	    return true;
	  else 
	    return false;
	}
	
  public function initialize($user_id) {
    // sets up User Setting row for this user_id
    // called from activation
    if (UserSetting::model()->countByAttributes(array('user_id'=>$user_id)) == 0) {
        $us = new UserSetting();
        $us->user_id = $user_id;
        $us->digestOn = 1;
        $us->digestNext =0;
        $us->digestLast =0;
        $us->digestUseAutologin = 1;
        $us->digestIncludeCaption = 0;
        $us->digestInterval = 12;
        $us->inbox_age =0;
        $us->use_whitelist = 0;
        $us->timezone = 'America/Los_Angeles';
        $us->created_at =new CDbExpression('NOW()');
        $us->modified_at =new CDbExpression('NOW()');                  
        $us->save();
      }
  }	  

  public function getInboxAgeOptions()
  {
    return array(self::INBOX_AGE_NONE => 'Do not freshen inbox',self::INBOX_AGE_DAY => 'Archive messages after a day', self::INBOX_AGE_WEEK => 'Archive messages after a week', self::INBOX_AGE_MONTH => 'Archive messages after a month', self::INBOX_AGE_QUARTER => 'Archive messages after three months', self::INBOX_AGE_YEAR => 'Archive messages after a year');
   }

   public function getWhitelistOptions()
   {
     return array(self::WHITELIST_YES => 'Yes, use whitelisting',self::WHITELIST_NO => 'No, do not use whitelisting');
    }


  public function scopes()
      {
          return array(
              'digests_due'=>array(
                  'condition'=>'digestOn=1 and digestNext<='.time(),
              ),
          );
      }
	
}