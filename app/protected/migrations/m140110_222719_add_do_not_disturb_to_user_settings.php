<?php

class m140110_222719_add_do_not_disturb_to_user_settings extends CDbMigration
{
	protected $MySqlOptions = 'ENGINE=InnoDB CHARSET=utf8 COLLATE=utf8_unicode_ci';
    public $tablePrefix;
    public $tableName;

    public function before() {
      $this->tablePrefix = Yii::app()->getDb()->tablePrefix;
      if ($this->tablePrefix <> '')
        $this->tableName = $this->tablePrefix.'user_setting';
    }

  	public function safeUp()
  	{
  	  $this->before();   	  
     $this->addColumn($this->tableName,'do_not_disturb','TINYINT DEFAULT 0');
  	}

  	public function safeDown()
  	{
  	  	$this->before();
       $this->dropColumn($this->tableName,'do_not_disturb');        
  	}
}