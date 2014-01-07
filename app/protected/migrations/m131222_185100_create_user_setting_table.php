<?php

class m131222_185100_create_user_setting_table extends CDbMigration
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
  $this->createTable($this->tableName, array(
             'id' => 'pk',
             'user_id' => 'integer default 0',
             'digestOn' => 'TINYINT DEFAULT 0',
             'digestInterval' => 'TINYINT DEFAULT 0',
             'digestNext' => 'BIGINT DEFAULT 0',
             'digestLast' => 'BIGINT DEFAULT 0',
             'digestUseAutologin'=>'tinyint default 0',
             'digestIncludeCaption'=>'tinyint default 0',
             'pushover_token'=>'VARCHAR(32) NOT NULL',
             'pushover_device'=>'VARCHAR(32) NOT NULL',
             'timezone'=>'string default NULL',
             'view_messages'=>'tinyint default 0',
             'inbox_age' => 'INTEGER DEFAULT 0',
             'use_whitelist' => 'TINYINT DEFAULT 0',
             'created_at' => 'DATETIME NOT NULL DEFAULT 0',
             'modified_at' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
               ), $this->MySqlOptions);
               $this->createIndex('user_setting_user', $this->tableName , 'user_id', true);
               $this->addForeignKey('fk_user_setting_user', $this->tableName, 'user_id', $this->tablePrefix.'users', 'id', 'CASCADE', 'CASCADE');

 	}

 	public function safeDown()
 	{
 	  	$this->before();
 	  	$this->dropForeignKey('fk_user_setting_user', $this->tableName);
      $this->dropIndex('user_setting_user', $this->tableName);

 	    $this->dropTable($this->tableName);
 	}
}