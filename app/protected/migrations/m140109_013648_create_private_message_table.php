<?php

class m140109_013648_create_private_message_table extends CDbMigration
{
   protected $MySqlOptions = 'ENGINE=InnoDB CHARSET=utf8 COLLATE=utf8_unicode_ci';
   public $tablePrefix;
   public $tableName;

   public function before() {
     $this->tablePrefix = Yii::app()->getDb()->tablePrefix;
     if ($this->tablePrefix <> '')
       $this->tableName = $this->tablePrefix.'private_message';
   }

 	public function safeUp()
 	{
 	  $this->before();
  $this->createTable($this->tableName, array(
             'id' => 'pk',
             'user_id'=> 'INTEGER DEFAULT 0',
             'account_id'=> 'INTEGER DEFAULT 0',
             'folder_id'=> 'INTEGER DEFAULT 0',
             'sender_id'=> 'INTEGER DEFAULT 0',
             'message_id'=>'string not null',
             'subject' => 'BLOB',
             'body_text' => 'BLOB',
             'body_html' => 'BLOB',
             'udate'=>'INTEGER DEFAULT 0',
             'status'=> 'TINYINT NOT NULL DEFAULT 0',
             'created_at' => 'DATETIME NOT NULL DEFAULT 0',
             'modified_at' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
               ), $this->MySqlOptions);
              $this->addForeignKey('fk_private_message_user', $this->tableName, 'user_id', $this->tablePrefix.'users', 'id', 'CASCADE', 'CASCADE');
              $this->addForeignKey('fk_private_message_account', $this->tableName, 'account_id', $this->tablePrefix.'account', 'id', 'CASCADE', 'CASCADE');
              $this->addForeignKey('fk_private_message_sender', $this->tableName, 'sender_id', $this->tablePrefix.'sender', 'id', 'CASCADE', 'CASCADE');
              $this->addForeignKey('fk_private_message_folder', $this->tableName, 'folder_id', $this->tablePrefix.'folder', 'id', 'CASCADE', 'CASCADE');
 	}

 	public function safeDown()
 	{
 	  	$this->before();
 	  	$this->dropForeignKey('fk_private_message_user', $this->tableName);
 	  	$this->dropForeignKey('fk_private_message_account', $this->tableName);
 	  	$this->dropForeignKey('fk_private_message_sender', $this->tableName);
 	  	$this->dropForeignKey('fk_private_message_folder', $this->tableName);
 	    $this->dropTable($this->tableName);
 	}

}