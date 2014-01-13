<?php

/**
 * This is the model class for table "{{quiet_hour}}".
 *
 * The followings are the available columns in table '{{quiet_hour}}':
 * @property integer $id
 * @property integer $user_id
 * @property integer $days
 * @property integer $start_interval
 * @property integer $end_interval
 * @property string $created_at
 * @property string $modified_at
 *
 * The followings are the available model relations:
 * @property Users $user
 */
class QuietHour extends CActiveRecord
{
  const DAYS_EVERYDAY = 0;
  const DAYS_WEEKDAYS = 10;
  const DAYS_WEEKENDS = 20;
  const DAYS_MONDAY = 30;
  const DAYS_TUESDAY = 40;
  const DAYS_WEDNESDAY = 50;
  const DAYS_THURSDAY = 60;
  const DAYS_FRIDAY = 70;
  const DAYS_SATURDAY = 80;
  const DAYS_SUNDAY = 90;
  
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return QuietHour the static model class
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
		return '{{quiet_hour}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('modified_at', 'required'),
			array('user_id, days, start_interval, end_interval', 'numerical', 'integerOnly'=>true),
			array('created_at', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, user_id, days, start_interval, end_interval, created_at, modified_at', 'safe', 'on'=>'search'),
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
			'days' => 'Days',
			'start_interval' => 'Start Interval',
			'end_interval' => 'End Interval',
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
		$criteria->compare('days',$this->days);
		$criteria->compare('start_interval',$this->start_interval);
		$criteria->compare('end_interval',$this->end_interval);
		$criteria->compare('created_at',$this->created_at,true);
		$criteria->compare('modified_at',$this->modified_at,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function getDaysOptions()
  {
    return array(self::DAYS_EVERYDAY => 'Everyday',self::DAYS_WEEKDAYS => 'Monday through Friday',self::DAYS_WEEKENDS => 'Saturday and Sunday',self::DAYS_MONDAY => 'Monday',self::DAYS_TUESDAY => 'Tuesday',self::DAYS_WEDNESDAY => 'Wednesday',self::DAYS_THURSDAY => 'Thursday',self::DAYS_FRIDAY => 'Friday',self::DAYS_SATURDAY => 'Saturday',self::DAYS_SUNDAY => 'Sunday');
   }		
   
   public function getStartOptions()
   {
      return $this->buildTimeSelectArray();
 	 } 

   public function getEndOptions()
   {
     return $this->buildTimeSelectArray();    
 	 } 
 	 
 	 public function buildTimeSelectArray() {
 	   $ts = array();
 	   $ts[0] = '12:00 am';
 	   $ts[30] = '12:30 am';
 	   $ts[100] = '1:00 am';
 	   $ts[130] = '1:30 am';
 	   $ts[200] = '2:00 am';
 	   $ts[230] = '2:30 am';
 	   $ts[300] = '3:00 am';
 	   $ts[330] = '3:30 am';
 	   $ts[400] = '4:00 am';
 	   $ts[430] = '4:30 am';
 	   $ts[500] = '5:00 am';
 	   $ts[530] = '5:30 am';
 	   $ts[600] = '6:00 am';
 	   $ts[630] = '6:30 am';
 	   $ts[700] = '7:00 am';
 	   $ts[730] = '7:30 am';
 	   $ts[800] = '8:00 am';
 	   $ts[830] = '8:30 am';
 	   $ts[900] = '9:00 am';
 	   $ts[930] = '9:30 am';
 	   $ts[1000] = '10:00 am';
 	   $ts[1030] = '10:30 am';
 	   $ts[1100] = '11:00 am';
 	   $ts[1130] = '11:30 am';
 	   $ts[1200] = '12:00 pm';
 	   $ts[1230] = '12:30 pm';
 	   $ts[1300] = '1:00 pm';
 	   $ts[1330] = '1:30 pm';
 	   $ts[1400] = '2:00 pm';
 	   $ts[1430] = '2:30 pm';
 	   $ts[1500] = '3:00 pm';
 	   $ts[1530] = '3:30 pm';
 	   $ts[1600] = '4:00 pm';
 	   $ts[1630] = '4:30 pm';
 	   $ts[1700] = '5:00 pm';
 	   $ts[1730] = '5:30 pm';
 	   $ts[1800] = '6:00 pm';
 	   $ts[1830] = '6:30 pm';
 	   $ts[1900] = '7:00 pm';
 	   $ts[1930] = '7:30 pm';
 	   $ts[2000] = '8:00 pm';
 	   $ts[2030] = '8:30 pm';
 	   $ts[2100] = '9:00 pm';
 	   $ts[2130] = '9:30 pm';
 	   $ts[2200] = '10:00 pm';
 	   $ts[2230] = '10:30 pm';
 	   $ts[2300] = '11:00 pm';
 	   $ts[2330] = '11:30 pm';
 	   return $ts;
 	 }
 	 
 	 public function debug() {
  		$ui = UserSetting::model()->loadByUser(1);
  		if (!empty($ui) and $ui->timezone<>'')
 	      date_default_timezone_set($ui->timezone); 	   
     $current_day_week = date('w'); // 0 Sunday - 6 Saturday
     $current_time = date('Hi'); // 0000 - 2359
     echo 'Current dow: '.date('w');lb();
     echo 'Current time: '.date('Hi');lb();
 	  $qh = QuietHour::model()->findAll();
 	  foreach ($qh as $q) {
        echo 'Day'.$q->days;lb();
        echo 'Start'.$q->start_interval;lb();
        echo 'End'.$q->end_interval;lb();
        lb();
 	  }
     
 	   
 	 }

}