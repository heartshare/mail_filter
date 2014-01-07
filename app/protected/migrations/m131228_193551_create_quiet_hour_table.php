<?php

class m131228_193551_create_quiet_hour_table extends CDbMigration
{
   protected $MySqlOptions = 'ENGINE=InnoDB CHARSET=utf8 COLLATE=utf8_unicode_ci';
   public $tablePrefix;
   public $tableName;

   public function before() {
     $this->tablePrefix = Yii::app()->getDb()->tablePrefix;
     if ($this->tablePrefix <> '')
       $this->tableName = $this->tablePrefix.'quiet_hour';
   }

 	public function safeUp()
 	{
 	  $this->before();
  $this->createTable($this->tableName, array(
             'id' => 'pk',
             'user_id' => 'integer default 0',
             'days' => 'TINYINT DEFAULT 0',
             'start_interval' => 'INT DEFAULT 0',
             'end_interval' => 'INT DEFAULT 0',
             'created_at' => 'DATETIME NOT NULL DEFAULT 0',
             'modified_at' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
               ), $this->MySqlOptions);
               $this->addForeignKey('fk_quiet_hour_user', $this->tableName, 'user_id', $this->tablePrefix.'users', 'id', 'CASCADE', 'CASCADE');

 	}

 	public function safeDown()
 	{
 	  	$this->before();
 	  	$this->dropForeignKey('fk_quiet_hour_user', $this->tableName);
 	    $this->dropTable($this->tableName);
 	}
}