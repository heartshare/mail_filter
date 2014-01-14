<?php
class Remote extends CComponent
{
  const ACTION_MOVE_FILTERED = 0;
  const ACTION_ROUTE_FOLDER = 10;
  const ACTION_ROUTE_FOLDER_BY_RX = 15;
  const ACTION_TRAIN_INBOX = 20;
  const ACTION_MOVE_REVIEW = 30;
  const ACTION_SKIP = 100;
  
  private $username;
  private $pwd;
  public $stream;
  public $hostname;
  public $path_inbox;
  public $path_filtering;
  public $path_review;
  public $path_block;
  public $path_archive;
  public $path_private;
  public $delete_mode;
  public $scan_seconds;
  public $process_timeout;

  function __construct() {
     $this->scan_seconds = Yii::app()->params['scan_seconds']; // window to scan inbox
     $this->process_timeout = Yii::app()->params['process_timeout']; // window to scan inbox
  }
  
  public function open($account_id, $mailbox='',$options=NULL) {
    $account = Account::model()->findByPk($account_id);
    $this->hostname = $account->address;
    if (!stristr($this->hostname,'{'))
      $this->hostname = '{'.$this->hostname.'}';
    $cred = Account::model()->getCredentials($account->cred);
    $this->stream = imap_open($this->hostname.$mailbox,$cred[0],$cred[1],$options,1) or die('Cannot connect to mail server: ' . print_r(imap_errors()));
  }
  
  public function close($errors = false) {
    if (!$errors) {
      @imap_errors();
      @imap_alerts();      
    }
    @imap_close($this->stream);   
  }
  
  public function createMailbox($account_id, $folder) {
    // creates a remote mailbox
    $this->open($account_id,'Inbox');
    $newMailbox = imap_utf7_encode($this->hostname.$folder);
    //lg ('creating: '.$account_id.' '.$newMailbox);
    $result = imap_createmailbox($this->stream,$newMailbox);
    //lg(varDumpToString($result));
    $this->close();
    return $result;
  }

  public function renameMailbox($account_id, $current_folder,$new_folder) {
    // renames a remote mailbox
    $this->open($account_id,'',OP_HALFOPEN);
    $current_folder = imap_utf7_encode($this->hostname.$current_folder);
    $new_folder = imap_utf7_encode($this->hostname.$new_folder);
    $result = imap_renamemailbox($this->stream,$current_folder,$new_folder);
    //lg('imap: RenameMailbox: '.$current_folder.' =>'.$new_folder);
    //lg(varDumpToString($result));
    $this->close();
    return $result;
  }
  
  public function listFolders($account_id) {
    $this->open($account_id);    
   $folders = imap_list($this->stream, $this->hostname, "*");
   $this->close();
   return $folders;
 }
  
 public function processInbox() {
    // move messages to Filtering and add to Message db
    $time_start = time();
    echo 'Time start: '.$time_start;lb();
    $s = new Sender();
    $m = new Message();
    $users = User::model()->findAll();
    foreach ($users as $user) {
      $user_id = $user['id'];
      echo 'User: '.$user['username'];lb();
      $accounts = Account::model()->findAllByAttributes(array('user_id'=>$user_id));
      $system_email = 'no_reply@'.Yii::app()->params['mail_domain'];
      foreach ($accounts as $account) {
        $account_id = $account['id'];  
        echo 'Account: '.$account['name'];lb();
        $this->setDefaultPaths($account_id);
        // process new mail in the inbox since 1 hr ago or possibly do by reverse sequence
        $tstamp = time()-(3*60*60); // 3hrs ago
        // lookup folder_id of this account's INBOX
        $folder_id = Folder::model()->lookup($account_id,$this->path_inbox);
        $this->open($account_id,$this->path_inbox);
        $cnt=0;
        $message_limit= 50; // break after n messages to prevent timeout
        echo 'Sort since: '.date("j F Y",$tstamp);           
         $recent_messages = @imap_search($this->stream, 'SINCE "'.date("j F Y",$tstamp).'"',SE_UID); // 30 November 2013
         if ($recent_messages===false) continue; // to do - continue into next account
         $result = imap_fetch_overview($this->stream, implode(',',array_slice($recent_messages,0,$message_limit)),FT_UID);
         foreach ($result as $item) {         
           if (!$this->checkExecutionTime($time_start)) break;
           // get msg header and stream uid
           $msg = $this->parseHeader($item);            
           $this->printMsg($msg);
           // skip any system messages
           if ($msg['email']==$system_email) continue;
            // if udate is too old, skip msg
            if (time()-$msg['udate']>$this->scan_seconds) continue; // skip msg
            // default action
      	     $action = self::ACTION_MOVE_FILTERED;
      	     $isNew = $s->isNew($account_id,$msg["email"]);
            // look up sender, if new, create them
            $sender_id = $s->add($user_id,$account_id,$msg["personal"], $msg["mailbox"], $msg["host"],0);                       
            $sender = Sender::model()->findByPk($sender_id);
             // create a message in db if needed
             $message_id = $m->add($user_id,$account_id,0,$sender_id,$msg['message_id'],$msg['subject'],$msg['udate']);        
          	  $message = Message::model()->findByPk($message_id);
              if ($isNew) {
                $this->challengeSender($user_id,$account_id,$sender,$message);
              } 
           	  if ($message['status'] == Message::STATUS_FILTERED ||
           	    $message['status'] == Message::STATUS_REVIEW ||
           	    ($message['status'] == Message::STATUS_TRAINED && $message['folder_id'] <> $folder_id) ||
           	    ($message['status'] == Message::STATUS_ROUTED && $message['folder_id'] <> $folder_id))
           	    {
             	  // then it's a training
           	    $action = self::ACTION_TRAIN_INBOX;     	    
           	  } else if (($message['status'] == Message::STATUS_TRAINED || $message['status'] == Message::STATUS_ROUTED) && $message['folder_id'] == $folder_id) {
           	    // if trained already or routed to inbox already, skip it
           	    $action = self::ACTION_SKIP;  
           	    echo 'Trained previously, skip ';lb();   	  
           	    continue;  
           	  }
           if ($action == self::ACTION_MOVE_FILTERED) {
             $cnt+=1;         
             if ($sender->exclude_quiet_hours == Sender::EQH_YES or !$this->isQuietHours($user_id)) {
               // send smartphone notifications based on sender
               if ($sender->alert==Sender::ALERT_YES) {
                 $this->notify($sender,$message,Monitor::NOTIFY_SENDER);
               }
               // send notifications based on keywords
               if (AlertKeyword::model()->scan($msg)) {
                 $this->notify($sender,$message,Monitor::NOTIFY_KEYWORD);
               }               
             }               
             // move imap msg to +Filtering
             echo 'Moving to +Filtering';lb();
             $result = @imap_mail_move($this->stream,$msg['uid'],$this->path_filtering,CP_UID);
             if ($result) {
               echo 'moved<br />';
               $m->setStatus($message_id,Message::STATUS_FILTERED);
             }
           } else if ($action == self::ACTION_TRAIN_INBOX) {
             // set sender folder_id to inbox
             echo 'Train to Inbox';lb();
             $m->setStatus($message_id,Message::STATUS_TRAINED);
             // only train sender when message is newer than last setting
             if ($msg['udate']>=$sender['last_trained']) {
               $s->setFolder($sender_id,$folder_id);
             } 
           }
        }
        @imap_expunge($this->stream);    
        $this->close();    
        // update last_checked
        Account::model()->touch($account_id);          
          // end account loop
          }
          // end user loop
        }
      $xtime = time()-$time_start;
      echo 'End time: '.time();lb();
      $il = new ImapLog();
      $il->add('processInbox','',$xtime);
  }
 
 public function processFiltering() {
   $time_start = time();
   // process messages found via IMAP in +Filtering
    $s = new Sender();
    $r = new Recipient();
    $m = new Message();
    $users = User::model()->findAll();
    foreach ($users as $user) {
      $user_id = $user['id'];
      echo 'User: '.$user['username'];lb();
      $accounts = Account::model()->findAllByAttributes(array('user_id'=>$user_id));
      foreach ($accounts as $account) {
        $account_id = $account['id'];  
        $this->setDefaultPaths($account_id);        
        echo 'Account: '.$account['name'];lb();
         $cnt=0;
         $message_limit= 100; // break after n messages to prevent timeout
         $this->open($account_id,$this->path_filtering);
         // note: filtering process tstamp must account for reopening of quiet hours and held msgs
         $tstamp = time()-(7*24*60*60); // 7 days ago
         echo 'Search since: '.date("j F Y",$tstamp);           
         $recent_messages = @imap_search($this->stream, 'SINCE "'.date("j F Y",$tstamp).'"',SE_UID);
         if ($recent_messages===false) continue; // to do - continue into next account
         $result = imap_fetch_overview($this->stream, implode(',',array_slice($recent_messages,0,$message_limit)),FT_UID);
         foreach ($result as $item) {         
           $cnt+=1;
           if (!$this->checkExecutionTime($time_start)) break;
           // get msg header and stream uid
           $msg = $this->parseHeader($item);            
           $this->printMsg($msg);
            // Set the default action to move to the review folder
            $action = self::ACTION_MOVE_REVIEW;
            $destination_folder =0;
            // look up & create recipient
            $recipient_id = $r->add($user_id,$account_id,$msg['rx_email'],0);
            $routeByRx = $this->routeByRecipient($recipient_id);
            if ($routeByRx!==false) {
             $action = $routeByRx->action;
             $destination_folder = $routeByRx->destination_folder;
            }            
            // look up sender, if new, create them
            $sender_id = $s->add($user_id,$account_id,$msg["personal"], $msg["mailbox"], $msg["host"],0);                       
            $sender = Sender::model()->findByPk($sender_id);
            // if sender destination known, route to folder
            if ($destination_folder ==0 && $sender['folder_id'] > 0) {
              $action = self::ACTION_ROUTE_FOLDER;  
              $destination_folder = $sender['folder_id'];      
            }
            // whitelist verified senders go to inbox
            if ($sender->is_verified==1 && $sender['folder_id'] ==0 && UserSetting::model()->useWhitelisting($user_id)) {
              // place message in inbox
              $action = self::ACTION_ROUTE_FOLDER;  
              $destination_folder = Folder::model()->lookup($account_id,$this->path_inbox);             
            }
             // create a message in db
          	  $message = Message::model()->findByAttributes(array('message_id'=>$msg['message_id']));
             if (!empty($message)) {
               // message exists already, 
           	  $message_id = $message->id;    	  
             } else {
               $message_id = $m->add($user_id,$account_id,0,$sender_id,$msg['message_id'],$msg['subject'],$msg['udate']);         
             }
             if ($recipient_id!==false) $m->setRecipient($message_id,$recipient_id);
             if ($action == self::ACTION_MOVE_REVIEW) {
               echo 'Moving to +Filtering/Review';lb();
               $result = @imap_mail_move($this->stream,$msg['uid'],$this->path_review,CP_UID);
               if ($result) {
                 echo 'moved<br />';
                 $m->setStatus($message_id,Message::STATUS_REVIEW);
               }      
            } else if ($action == self::ACTION_ROUTE_FOLDER || $action == self::ACTION_ROUTE_FOLDER_BY_RX) {
             // lookup folder name by folder_id
             $folder = Folder::model()->findByPk($destination_folder);       
             // if inbox & quiet hours, don't route right now
             if (strtolower($folder['name'])=='inbox' and $sender->exclude_quiet_hours == Sender::EQH_NO and $this->isQuietHours($user_id)) continue;
             echo 'Moving to '.$folder['name'];lb();
             $result = @imap_mail_move($this->stream,$msg['uid'],$folder['name'],CP_UID);
             if ($result) {
               echo 'moved<br />';
               $m->setStatus($message_id,Message::STATUS_ROUTED);         
               $m->setFolder($message_id,$destination_folder);
             }
           }
        }
        @imap_expunge($this->stream);    
        $this->close();        
      // end account loop
      }
      // end user loop
    }    
    $time_end = time();
    $il = new ImapLog();
    $il->add('processFiltering','',$time_end-$time_start);    
 } 

  public function processReviewFolders($account_id) {
    // process messages in review folder according to trainings
    $time_start = time();
    $cleans =array();
     $s = new Sender();
     $m = new Message();
     $cnt = 0;
       $account = Account::model()->findByPk($account_id);
         $account_id = $account['id'];  
         $user_id = $account['user_id'];
         $this->setDefaultPaths($account_id);        
          $message_limit= 200; // break after n messages to prevent timeout
          $this->open($account_id,$this->path_review);
          $tstamp = time()-(365*24*60*60); // a year ago
          $recent_messages = @imap_search($this->stream, 'SINCE "'.date("j F Y",$tstamp).'"',SE_UID);
          if ($recent_messages!==false) {
            $result = imap_fetch_overview($this->stream, implode(',',array_slice($recent_messages,0,$message_limit)),FT_UID);
            foreach ($result as $item) {         
              if (!$this->checkExecutionTime($time_start)) break;
              $msg = $this->parseHeader($item);            
              // look up sender, if new, create them
              $sender_id = $s->add($user_id,$account_id,$msg["personal"], $msg["mailbox"], $msg["host"],0);
              $sender = Sender::model()->findByPk($sender_id);
              $folder_id = $sender->folder_id;
              if ($folder_id ==0) {
                $cleans['skip'][$cnt]['from']=$msg['personal'].' '.$msg['email'];
                $cleans['skip'][$cnt]['subject']=$msg['subject'];
              } else {
                 $f = Folder::model()->findByPk($folder_id);
                 // create a message in db
                 $message_id = $m->add($user_id,$account_id,$folder_id,$sender_id,$msg['message_id'],$msg['subject'],$msg['udate']);          
                 $message = Message::model()->findByPk($message_id);
                 $cleans['move'][$cnt]['from']=$msg['personal'].' '.$msg['email'];
                 $cleans['move'][$cnt]['subject']=$msg['subject'];
                 $cleans['move'][$cnt]['folder']=$f['name'];
                 $cleans['move'][$cnt]['result']='Error - not moved ';
                 $imap_result = @imap_mail_move($this->stream,$msg['uid'],$f['name'],CP_UID);
                 if ($imap_result) {
                   $cleans['move'][$cnt]['result']='Moved ';
                   $m->setStatus($message_id,Message::STATUS_ROUTED);         
                   $m->setFolder($message_id,$folder_id);               
                }                            
              }
             $cnt+=1;
          } // end loop
        }
        @imap_expunge($this->stream);    
        $this->close();        
    $time_end = time();
    $il = new ImapLog();
    $il->add('processReviewFolders','',$time_end-$time_start);    
    return $cleans;            
  }
  
  public function processRecentTrainings() {
    $time_start = time();
    // moves messages of recently trained senders
    $s = new Sender();
    $m = new Message();
    $senders = Sender::model()->recently_trained()->findAll();
    foreach ($senders as $t) {
       $this->scanForSender($t,true);
    } // end sender training loop        
    $time_end = time();
    $il = new ImapLog();
    $il->add('processRecentTrainings','',$time_end-$time_start);    
  }
  
 public function scanForSender($sender,$output = true) {
   // called from processRecentTrainings or from web UI when sender is trained
   // scans last folder (if present) and review folder for msgs to move to newly trained folder 
   if ($sender->folder_id ==0 ) return false;
   // move messages after sender is trained
   $dest_folder = Folder::model()->findByPk($sender->folder_id);
   // if last folder existed and isn't the same, scan it
   if ($sender->last_folder_id>0 and $sender->last_folder_id<>$sender->folder_id) {
    $src_folder = Folder::model()->findByPk($sender->last_folder_id); 
    if ($output) {
      echo 'Opening Last Folder for Sender:'.$sender->account_id.' => '.$src_folder['name'];lb();
    }
$this->moveMessagesBySender($sender->user_id,$sender->account_id,$sender->id,$sender->email,$src_folder['name'],$sender->folder_id,$dest_folder['name'],$output);
   }
   // scan review folder for this account
   $this->setDefaultPaths($sender->account_id); 
   if ($output) {
     echo 'Opening Review folder for account:'.$sender->account_id.' => '.$this->path_review;lb();     
   }
   // open account_id, folder_name, scan for sender email, move to destination folder_name
 $this->moveMessagesBySender($sender->user_id,$sender->account_id,$sender->id,$sender->email,$this->path_review,$sender->folder_id,$dest_folder['name'],$output);
  // mark training as processed
  $sender->touchLastTrainingProcessed($sender->id);   
 }
 
 public function moveMessagesBySender($user_id,$account_id,$sender_id,$sender_email,$src_folder,$dest_folder_id,$dest_folder,$output = false) {
   $time_start = time();
   $message_limit= 50;
   $m=new Message();
   $this->open($account_id, $src_folder);
   if ($output) echo 'Looking for messages from: '.$sender_email;lb();
   $route_messages = @imap_search($this->stream, 'FROM "'.$sender_email.'"',SE_UID); 
    if ($route_messages===false) return false;
    $result = imap_fetch_overview($this->stream, implode(',',array_slice($route_messages,0,$message_limit)),FT_UID);
    foreach ($result as $item) {         
      if (!$this->checkExecutionTime($time_start)) break;
      // get msg header and stream uid
      $msg = $this->parseHeader($item);            
      if ($output) $this->printMsg($msg);
       if (strcasecmp($msg['email'],$sender_email)==0) {
         if ($output) echo 'Moving to '.$dest_folder.'... ';
         $result = @imap_mail_move($this->stream,$msg['uid'],$dest_folder,CP_UID);
         if ($result) {
           if ($output) echo 'moved';
           $message_id = $m->add($user_id,$account_id,$dest_folder_id,$sender_id,$msg['message_id'],$msg['subject'],$msg['udate']);
           $m->setStatus($message_id,Message::STATUS_ROUTED);         
           $m->setFolder($message_id,$dest_folder_id);         
          }
        }
        if ($output) lb();
   }   
   @imap_expunge($this->stream);    
   $this->close();      
 }
  
 public function processDetectTraining() {
   $time_start = time();
   // search trainable folders for manual drag and drop training and set sender routing
   $message_limit=150;
   $s = new Sender();
   $m = new Message();
   $users = User::model()->findAll();
   foreach ($users as $user) {
     $user_id = $user['id'];
     echo 'User: '.$user['username'];lb();
     $accounts = Account::model()->findAllByAttributes(array('user_id'=>$user_id));
     foreach ($accounts as $account) {
       $account_id = $account['id'];  
       $this->setDefaultPaths($account_id);        
       echo 'Account: '.$account['name'];lb();
       // Find all training folders
      $folders = Folder::model()->findAllByAttributes(array('account_id'=>$account_id,'user_id'=>$user_id,'train'=>1));
      foreach ($folders as $f) {
        if (strtolower($f['name'])=='inbox') continue; // skip inbox
        $folder_id = $f['id'];
        $mailbox = $f['name'];
        echo 'Opening '.$account_id.' '.$mailbox;lb();
        $this->open($account_id,$mailbox);
        $tstamp = time() - 3600*24*3; // currently 3 days ago
        echo 'Checking dates since: '.date("j F Y",$tstamp);
        $recent_messages = @imap_search($this->stream, 'SINCE "'.date("j F Y",$tstamp).'"',SE_UID);
        if ($recent_messages===false) continue; // to do - continue into next folder
        $result = imap_fetch_overview($this->stream, implode(',',array_slice($recent_messages,0,$message_limit)),FT_UID);
        foreach ($result as $item) {         
          if (!$this->checkExecutionTime($time_start)) break;
          // get msg header and stream uid
          $msg = $this->parseHeader($item);            
          $this->printMsg($msg);
         // look up sender, if new, create them
         $sender_id = $s->add($user_id,$account_id,$msg["personal"], $msg["mailbox"], $msg["host"],$folder_id);                       
         $sender = Sender::model()->findByPk($sender_id);
          // create a message in db
          $message_id = $m->add($user_id,$account_id,$folder_id,$sender_id,$msg['message_id'],$msg['subject'],$msg['udate']);          
          $message = Message::model()->findByPk($message_id);
          // check if message was already routed here - folder id and status_routed
          if (
          ($message['status']==Message::STATUS_ROUTED && $message['folder_id'] <> $folder_id) ||
          ($message['status']==Message::STATUS_TRAINED && $message['folder_id'] <> $folder_id) ||
          ($message['status']<>Message::STATUS_ROUTED AND $message['status']<>Message::STATUS_TRAINED)
) {
            echo 'Train to '.$f['name'];lb();
            $m->setFolder($message_id,$folder_id); 
            // only train sender when message is newer than last setting
            if ($message['udate']>=$sender['last_trained']) {
              $s->setFolder($sender_id,$folder_id);
              $m->setStatus($message_id,Message::STATUS_TRAINED);
            }
          } else {
            echo 'Previously routed here';lb();
          }
         }
         $this->close();        
       } // end folder loop        
     } // end account loop        
   } // end user loop
   $time_end = time();
   $il = new ImapLog();
   $il->add('processDetectTraining','',$time_end-$time_start);        
 }
  
  public function trainFolder($folder_id) {  
    // looks at messages in folder since tstamp and trains all senders found
    $time_start = time();
    $trainings = array();
    $trainings_ids = array();
    $s = new Sender();
    $m = new Message();
    $r = new Recipient();
    $f = Folder::model()->findByPk($folder_id);
    $account = Account::model()->findByPk($f->account_id);
    $user_id = $account['user_id'];
    $account_id = $account['id'];  
    $message_limit = 250;
    $this->setDefaultPaths($account_id);        
    $mailbox = $f['name'];
    $this->open($account_id,$mailbox);
    $tstamp = time() - 3600*24*365; // currently a year ago
    $recent_messages = @imap_search($this->stream, 'SINCE "'.date("j F Y",$tstamp).'"',SE_UID); 
    if ($recent_messages!==false) {
      $result = imap_fetch_overview($this->stream, implode(',',array_slice($recent_messages,0,$message_limit)),FT_UID);
      foreach ($result as $item) {         
        if (!$this->checkExecutionTime($time_start)) break;
        // get msg header and stream uid
        $msg = $this->parseHeader($item);            
       // look up sender, if new, create them
       $sender_id = $s->add($user_id,$account_id,$msg["personal"], $msg["mailbox"], $msg["host"],$folder_id);                       
       $sender = Sender::model()->findByPk($sender_id);
       // create a message in db
       $message_id = $m->add($user_id,$account_id,$folder_id,$sender_id,$msg['message_id'],$msg['subject'],$msg['udate']);          
       $message = Message::model()->findByPk($message_id);
       // look up & create recipient
       $recipient_id = $r->add($user_id,$account_id,$msg['rx_email'],0);
       if ($recipient_id!==false) $m->setRecipient($message_id,$recipient_id);       
       $m->setFolder($message_id,$folder_id); 
       $m->setStatus($message_id,Message::STATUS_TRAINED);         
       if (!in_array($sender_id,$trainings_ids)) {
         $trainings_ids[] = $sender_id;
         $trainings[]=$sender['personal'].' '.$sender['email'];
         $s->setFolder($sender_id,$folder_id);
        }
        } // end message loop
      }      
     $this->close();    
     $time_end = time();
     $il = new ImapLog();
     $il->add('trainFolder','Folder: '.$f['name'],$time_end-$time_start);        
     if (!empty($trainings))
       sort($trainings);
     return $trainings;
  }

  public function purgeMessages() {   
    // delete messages in the zap block folder
    $time_start = time();
    echo 'Purge Time start: '.$time_start;lb();
     $m = new Message();
     $users = User::model()->findAll();
     foreach ($users as $user) {
       $user_id = $user['id'];
       echo 'User: '.$user['username'];lb();
       $accounts = Account::model()->findAllByAttributes(array('user_id'=>$user_id));
       foreach ($accounts as $account) {
         $account_id = $account['id'];  
         $this->setDefaultPaths($account_id);        
         echo 'Account: '.$account['name'];lb();
          $message_limit=100;
          $this->open($account_id,$this->path_block);
           $recent_messages = @imap_sort($this->stream,SORTARRIVAL,1,SE_UID);
           if ($recent_messages===false) continue; // to do - continue into next account
           $result = imap_fetch_overview($this->stream, implode(',',array_slice($recent_messages,0,$message_limit)),FT_UID);
           foreach ($result as $item) {         
             if (!$this->checkExecutionTime($time_start)) break;
             // get msg header and stream uid
             $msg = $this->parseHeader($item);            
             $this->printMsg($msg);
             $this->deleteMessage($m,$msg,$msg['uid']);
          }
          @imap_expunge($this->stream);    
          $this->close();  
          // delete older digests from inbox
          $tstamp = time()-12*60*60; // 12 hrs ago
           $this->open($account_id,$this->path_inbox);
            $recent_messages = @imap_search($this->stream, 'SUBJECT "Message Digest for" BEFORE "'.date("j F Y",$tstamp).'"',SE_UID);
            if ($recent_messages===false) continue; // to do - continue into next account
            $result = imap_fetch_overview($this->stream, implode(',',array_slice($recent_messages,0,$message_limit)),FT_UID);
            foreach ($result as $item) {         
              if (!$this->checkExecutionTime($time_start)) break;
              // get msg header and stream uid
              $msg = $this->parseHeader($item);            
              $this->printMsg($msg);
              $this->deleteMessage($m,$msg,$msg['uid']);                
            }
            @imap_expunge($this->stream);    
            $this->close();                        
      // end account loop
      }
      // end user loop
    }          
    $xtime = time()-$time_start;
    echo 'End time: '.time();lb();
    $il = new ImapLog();
    $il->add('purgeMessages','',$xtime);    
  }
  
  public function deleteMessage($m,$msg,$uid) {
    echo 'Message id:'.$msg['message_id'];lb();
  	  $message = Message::model()->findByAttributes(array('message_id'=>$msg['message_id']));
     echo 'Moving to Trash';lb();
     // handle gmail delete differently
     if ($this->delete_mode == 'gmail') 
       $result = @imap_mail_move($this->stream,$uid,"[Gmail]/Trash",CP_UID);
     else
       $result = @imap_delete($this->stream,$uid,FT_UID);
     if ($result) {
       echo 'deleted<br />';
       if (!empty($message)) {
         $m->setStatus($message->id,Message::STATUS_DELETED);
       }
     }    
  }

  public function scanPrivate() {
    if (Yii::app()->params['version']=='basic') return false;
    // scan private folder to encrypt and delete
    $time_start = time();
    // search trainable folders for manual drag and drop training and set sender routing
    $message_limit=150;
    $s = new Sender();
    $m = new Message();
    $f = new Folder();
    $users = User::model()->findAll();
    foreach ($users as $user) {
      $user_id = $user['id'];
      echo 'User: '.$user['username'];lb();
      $accounts = Account::model()->findAllByAttributes(array('user_id'=>$user_id));
      foreach ($accounts as $account) {
        $account_id = $account['id'];  
        $this->setDefaultPaths($account_id);        
        echo 'Account: '.$account['name'];lb();
        $this->open($account_id,$this->path_private);
        // look up folder_id of priate folder
        $folder_id = $f->lookup($account_id,$this->path_private); 
          $tstamp = time()-(30*24*60*60); // a month ago
          $recent_messages = @imap_search($this->stream, 'SINCE "'.date("j F Y",$tstamp).'"',SE_UID);
          if ($recent_messages!==false) {
            $result = imap_fetch_overview($this->stream, implode(',',array_slice($recent_messages,0,$message_limit)),FT_UID);
            foreach ($result as $item) {         
              if (!$this->checkExecutionTime($time_start)) break;
              $msg = $this->parseHeader($item);            
              // look up sender, if new, create them
              $sender_id = $s->add($user_id,$account_id,$msg["personal"], $msg["mailbox"], $msg["host"],$folder_id);
              $sender = Sender::model()->findByPk($sender_id);
             // create a message in db
             $message_id = $m->add($user_id,$account_id,$folder_id,$sender_id,$msg['message_id'],$msg['subject'],$msg['udate']);          
             $message = Message::model()->findByPk($message_id);
             // add to private messages
             $this->addPrivateMessage($message,$msg['uid']);
             if ($this->delete_mode == 'gmail') 
               $result = @imap_mail_move($this->stream,$msg['uid'],"[Gmail]/Trash",CP_UID);
             else
               $result = @imap_delete($this->stream,$msg['uid'],FT_UID);
               $m->setStatus($message_id,Message::STATUS_DELETED);         
               $m->setFolder($message_id,$folder_id);
          } // end loop
        }
        @imap_expunge($this->stream);    
        $this->close();        
      } // end account loop        
    } // end user loop
    $time_end = time();
    $il = new ImapLog();
    $il->add('scanPrivate','',$time_end-$time_start);        
  }
    
  public function parseHeader($header) {
    // parses header object returned from imap_fetch_overview    
    if (!isset($header->from)) {
      return false;
    } else {
      $from_arr = imap_rfc822_parse_adrlist($header->from,'gmail.com');
      $fi = $from_arr[0];
      $msg = array(
        "uid" => (isset($header->uid))
              ? $header->uid : 0,
         "personal" => (isset($fi->personal))
            ? @imap_utf8($fi->personal) : "",
          "email" => (isset($fi->mailbox) && isset($fi->host))
              ? $fi->mailbox . "@" . $fi->host : "",
          "mailbox" => (isset($fi->mailbox))
            ? $fi->mailbox : "",
          "host" => (isset($fi->host))
            ? $fi->host : "",
          "subject" => (isset($header->subject))
              ? @imap_utf8($header->subject) : "",
          "message_id" => (isset($header->message_id))
                ? $header->message_id : "",
          "udate" => (isset($header->udate))
              ? $header->udate : 0,
          "date_str" => (isset($header->date))
              ? $header->date : ""
      );    
      // handles fetch with uid and rfc header parsing
      if ($msg['udate']==0 && isset($header->date)) {
          $msg['udate']=strtotime($header->date);
      }
      $msg['rx_email']='';        
      if (isset($header->to)) {
        $to_arr = imap_rfc822_parse_adrlist($header->to,'gmail.com');
        $to_info = $to_arr[0];
        if (isset($to_info->mailbox) && isset($to_info->host))
          $msg['rx_email']=$to_info->mailbox.'@'.$to_info->host;
      }
      return $msg;
    }
  }

  public function printMsg($msg) {
    echo "<ul><li>";
    echo "From: " . $msg["personal"];
    echo " " . $msg["email"] . " ";
    echo " " . $msg["subject"] . " ";
    echo " " . $msg['date_str']. "</li>";
    echo "<li>".htmlentities($msg['message_id'])."</li>";
    echo "</ul>";    
  }   
  
  function getSeparator($provider='gmail') {
    switch ($provider) {
      case 'fastmail':
        $sep ='.';
      break;
      default:
        $sep ='/';
      break;
    }
    return $sep;
  }

  public function setDefaultPaths($account_id) {
    $a = Account::model()->findByPk($account_id);
    $provider = $a['provider'];
    if ($provider == Account::PROVIDER_GMAIL) {
      $this->path_inbox = 'Inbox';
      $this->path_filtering = '+Filtering';
      $this->path_review = '+Filtering/Review';
      $this->path_block = '+Filtering/Zap';
      $this->path_private = '+Filtering/Secure';
      $this->path_archive = '[Gmail]/All Mail';
      $this->delete_mode = 'gmail';      
    } else if ($provider == Account::PROVIDER_FASTMAIL) {
      $this->path_inbox = 'Inbox';
      $this->path_filtering = 'Inbox.+Filtering';
      $this->path_review = 'Inbox.+Filtering.Review';
      $this->path_private = 'Inbox.+Filtering.Secure';
      $this->path_block = 'Inbox.+Filtering.Zap';
      $this->path_archive = 'Inbox.Archive';
      $this->delete_mode = 'delete';      
    }    else if ($provider == Account::PROVIDER_DOVECOT) {
      $this->path_inbox = 'Inbox';
      $this->path_filtering = 'Inbox/+Filtering';
      $this->path_review = 'Inbox/+Filtering/Review';
      $this->path_private = 'Inbox/+Filtering/Secure';
      $this->path_block = 'Inbox/+Filtering/Zap';
      $this->path_archive = 'Inbox/Archive';
      $this->delete_mode = 'delete';
    } else {
      // other provider
      $this->path_inbox = 'Inbox';
      $this->path_filtering = '+Filtering';
      $this->path_review = '+Filtering/Review';
      $this->path_private = '+Filtering/Secure';
      $this->path_block = '+Filtering/Zap';
      $this->path_archive = 'Inbox/Archive';
      $this->delete_mode = 'delete';            
    }
  }
  
    public function checkExecutionTime($time_start) {
      if ((time()-$time_start)>($this->process_timeout-1))
       return false; // execution limit exceeded
      else
       return true;
    }

    // read message parts

    function getPlainText($uid) {
      $body = $this->get_part($uid, "TEXT/PLAIN");
      return $body;
    }

    function getHtml($uid) {
      $body = $this->get_part($uid, "TEXT/HTML");
      return $body;
    }

    // excerpted from http://www.sitepoint.com/exploring-phps-imap-library-1/
    function get_part($uid, $mimetype, $structure = false, $partNumber = false) {
        if (!$structure) {
               $structure = imap_fetchstructure($this->stream, $uid, FT_UID);
        }
        if ($structure) {
            if ($mimetype == $this->get_mime_type($structure)) {
                if (!$partNumber) {
                    $partNumber = 1;
                }
                $text = imap_fetchbody($this->stream, $uid, $partNumber, FT_UID | FT_PEEK);
                switch ($structure->encoding) {
                    case 3: return imap_base64($text);
                    case 4: return imap_qprint($text);
                    default: return $text;
               }
           }

            // multipart 
            if ($structure->type == 1) {
                foreach ($structure->parts as $index => $subStruct) {
                    $prefix = "";
                    if ($partNumber) {
                        $prefix = $partNumber . ".";
                    }
                    $data = $this->get_part($uid, $mimetype, $subStruct, $prefix . ($index + 1));
                    if ($data) {
                        return $data;
                    }
                }
            }
        }
        return false;
    }

    function get_mime_type($structure) {
        $primaryMimetype = array("TEXT", "MULTIPART", "MESSAGE", "APPLICATION", "AUDIO", "IMAGE", "VIDEO", "OTHER");

        if ($structure->subtype) {
           return $primaryMimetype[(int)$structure->type] . "/" . $structure->subtype;
        }
        return "TEXT/PLAIN";
    }    
  
    // advanced module functionality

    public function notify($sender,$message,$notify_type) { 
      if (Yii::app()->params['version']<>'basic') {
        $a = new Advanced();
        $a->notify($sender,$message,$notify_type); 
      }            
    }
        
    public function isQuietHours($user_id) {
      if (Yii::app()->params['version']<>'basic') {
        $a = new Advanced();
        return $a->isQuietHours($user_id); 
      } else {
        return false; // basic mode - never quiet hours
      }
    }
    
    public function expireSenders() {
      if (Yii::app()->params['version']<>'basic') {
        $a = new Advanced();
        $a->expireSenders(); 
      }      
    }

    public function freshenInbox() {
      if (Yii::app()->params['version']<>'basic') {
        $a = new Advanced();
        $a->freshenInbox();
      }   
    }
    
    public function routeByRecipient($recipient_id) {
      if (Yii::app()->params['version']<>'basic') {
        $a = new Advanced();
        $result = $a->routeByRecipient($recipient_id);
        return $result;
      } else 
        return false;
    }
  
  public function challengeSender($user_id,$account_id,$sender,$message) {
    if (Yii::app()->params['version']<>'basic') {
      if (UserSetting::model()->useWhitelisting($user_id)) {
        $a = new Advanced();
        $result = $a->challengeSender($user_id,$account_id,$sender,$message);
      }
    }
  }
  
  public function addPrivateMessage($message,$uid) {
    if (Yii::app()->params['version']<>'basic') {
      $a = new Advanced();
      $a->addPrivateMessage($this,$message,$uid);
    }   
  }
    
}
?>