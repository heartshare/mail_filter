<?php

class m131222_191609_create_sender_table extends CDbMigration
{
     protected $MySqlOptions = 'ENGINE=InnoDB CHARSET=utf8 COLLATE=utf8_unicode_ci';
     public $tablePrefix;
     public $tableName;

     public function before() {
       $this->tablePrefix = Yii::app()->getDb()->tablePrefix;
       if ($this->tablePrefix <> '')
         $this->tableName = $this->tablePrefix.'sender';
     }

   	public function safeUp()
   	{
   	  $this->before();
    $this->createTable($this->tableName, array(
               'id' => 'pk',
               'account_id' => 'integer default 0',
               'user_id' => 'integer default 0',
               'personal' => 'string NOT NULL',
                 'email' => 'string NOT NULL',
                 'mailbox' => 'string NOT NULL',
                 'host' => 'string NOT NULL', 
                 'is_verified'=> 'TINYINT DEFAULT 0',
                 'last_folder_id'=> 'INTEGER DEFAULT 0',
                 'folder_id'=> 'INTEGER DEFAULT 0',
                 'last_trained'=>'INTEGER DEFAULT 0',
                 'last_trained_processed'=>'TINYINT DEFAULT 0',
                 'alert'=>'TINYINT DEFAULT 0',
                 'alert_sent'=>'TINYINT DEFAULT 0',
                 'expire'=>'TINYINT DEFAULT 0',
                 'last_expired'=>'INTEGER DEFAULT 0',
                 'exclude_quiet_hours'=>'TINYINT DEFAULT 0',
                 'created_at' => 'DATETIME NOT NULL DEFAULT 0',
                 'modified_at' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',

                 ), $this->MySqlOptions);
                 $this->createIndex('sender_email', $this->tableName , 'account_id,email', true);
                 $this->addForeignKey('fk_sender_user', $this->tableName, 'user_id', $this->tablePrefix.'users', 'id', 'CASCADE', 'CASCADE');
                 $this->addForeignKey('fk_sender_account', $this->tableName, 'account_id', $this->tablePrefix.'account', 'id', 'CASCADE', 'CASCADE');
   	}

   	public function safeDown()
   	{
   	  	$this->before();
   	  	$this->dropForeignKey('fk_sender_user', $this->tableName);
   	  	$this->dropForeignKey('fk_sender_account', $this->tableName);
        $this->dropIndex('sender_email', $this->tableName);
   	    $this->dropTable($this->tableName);
   	}
}