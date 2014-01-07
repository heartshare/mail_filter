<?php
// invoke Mailgun sdk
use Mailgun\Mailgun;

class Yiigun extends CComponent
{
  private $mg;
  private $mgValidate;
  
   function __construct() {  
     // initialize mailgun connection
     $this->mg = new Mailgun(Yii::app()->params['mailgun']['api_key']);
  }
    
  public function send_simple_message($from='',$to='',$subject='',$body='') {
    if ($from == '') 
      $from = Yii::app()->params['supportEmail'];
    $domain = Yii::app()->params['mail_domain'];
    // use only if supportEmail and from email are in mailgun account
  //  $domain = substr(strrchr($from, "@"), 1);      
    $result = $this->mg->sendMessage($domain,array('from' => $from,
                                               'to' => $to,
                                               'subject' => $subject,
                                               'text' => $body,
                                               ));
    return $result->http_response_body;    
  }	
  
  public function send_html_message($from='',$to='',$subject='',$bodyHtml='') {
    # instantiate a Message Builder object from the SDK.
    $messageBldr = $this->mg->MessageBuilder();
    # Define the from address.
    $messageBldr->setFromAddress($from);
    # Define a to recipient.
    $messageBldr->addToRecipient($to);
    # Define the subject. 
    $messageBldr->setSubject($subject);
    # Define the body of the message.
//    $messageBldr->setTextBody($message->body);
    $messageBldr->setHtmlBody($bodyHtml);
    # Finally, send the message.
    $result = $this->mg->post(Yii::app()->params['mail_domain'].'/messages', $messageBldr->getMessage());
    
  }

  public function reflect_message($fromFriendly='',$fromEmail,$reply_to,$to='',$message) {
    # instantiate a Message Builder object from the SDK.
    $messageBldr = $this->mg->MessageBuilder();
    # Define the from address.
    $messageBldr->setFromAddress($fromEmail, array("first"=> $fromFriendly));
    # Define a to recipient.
    $messageBldr->addToRecipient($to);
    # Define the subject. 
    $messageBldr->setSubject($message->subject);
    # Define the body of the message.
    $messageBldr->setTextBody($message->body);
    $messageBldr->setHtmlBody($message->stripped_html);
    $messageBldr->setReplyToAddress($reply_to);  
    # Finally, send the message.
    $result = $this->mg->post(Yii::app()->params['mail_domain'].'/messages', $messageBldr->getMessage());
  } 

  public function fetchLists() {
    $result = $this->mg->get("lists");
    return $result->http_response_body;    
  }

  public function fetchListMembers($address,$skip=0,$limit =100) {
    $result = $this->mg->get("lists/".$address.'/members',array(
//      'subscribed'=>'yes',
        'limit'=>$limit,
        'skip'=>$skip
    ));
    return $result->http_response_body;    
  }

  public function listCreate($newlist) {
    $result = $this->mg->post("lists",array('address'=>$newlist->address,'name'=>$newlist->name,'description' => $newlist->description,'access_level' => $newlist->access_level));
    return $result->http_response_body;    
  }
  
  public function listDelete($address='') {
    $result = $this->mg->delete("lists/".$address);
    return $result->http_response_body;    
  }
  
  public function listUpdate($existing_address,$model) {
    $result = $this->mg->put("lists/".$existing_address,array(
      'address'=>$model->address,
      'name' => $model->name,
      'description' => $model->description,
      'access_level' => $model->access_level
      ));
    return $result->http_response_body;    
   }  

   public function wrapJsonStr($str='') {
     // remove trailing comma
     $str=trim($str,',');     
     $str = '['.$str.']';
     return $str;
   }  

   public function createJsonMember($name,$address) {
     // construct json string for a member for a bulk add operation
     $str='{';
     $str.='"name": "'.$name.'", ';                  
     $str.='"address": "'.$address.'"';                  
     $str.='},';
    return $str; 
    }
   
   public function memberBulkAdd($list='',$json_str='') {
     // limit of 1000 members at a time
     $result = $this->mg->post("lists/".$list.'/members.json',array(
    'members' => $json_str,
     'subscribed' => true,
     'upsert' => 'yes'
     ));  
     return $result->http_response_body;    
   }
  
  public function memberAdd($list='',$email='',$name='') {
    $result = $this->mg->post("lists/".$list.'/members',array('address'=>$email,'name'=>$name,'subscribed' => true,'upsert' => 'yes'));
    return $result->http_response_body;    
  }

  public function memberDelete($list='',$email='') {
    $result = $this->mg->delete("lists/".$list.'/members/'.$email);
    return $result->http_response_body;    
  }
    
  public function memberUpdate($list='',$email='',$propList) {
    $result = $this->mg->put("lists/".$list.'/members',$propList);
    return $result->http_response_body;    
   }
   
   public function memberUnsubscribe($list='',$email='') {
     $propList = array('subscribed'=>false);
     $result=$this->memberUpdate($list,$email,$propList);
   }

   public function generateVerifyHash($model,$mglist) {
     // generate secure hash for verifying subscription requests
     $verify_secret = Yii::app()->params['verify_secret'];
     $optInHandler = $this->mg->OptInHandler();
     $generatedHash = $optInHandler->generateHash($mglist->address, $verify_secret, $model->address);
     // remove encodings - fixes yii routing issue
     $generatedHash = str_ireplace('%','',$generatedHash);
     return $generatedHash;
   }

   public function sendVerificationRequest($model,$mglist) {
     // send an email with the verification link 
		  $body="Please verify your subscription by clicking on the link below:\r\n".Yii::app()->getBaseUrl(true)."/request/verify/".$model->id."/".$model->hash;
		  $this->send_simple_message(Yii::app()->params['support_email'],$model->address,'Please verify your subscription to '.$mglist->name,$body);
   }
   
   function validate($email='') {
     $this->mgValidate = new Mailgun(Yii::app()->params['mailgun']['public_key']);
     $result = $this->mgValidate->get('address/validate', array('address' => $email));
    return $result->http_response_body;
   }   

   public function verifyWebHook($timestamp='', $token='', $signature='') {
     // Concatenate timestamp and token values
     $combined=$timestamp.$token;
    //lg('Combined:'.$combined);
     // Encode the resulting string with the HMAC algorithm
     // (using your API Key as a key and SHA256 digest mode)
     $result= hash_hmac('SHA256', $combined, Yii::app()->params['mailgun']['api_key']);
     //lg ('Result: '.$result);
     //lg ('Signature: '.$signature);
     if ($result == $signature)
       return true;
      else
      return false;    
   }

   public function fetchMessage($url) {
    $result = $this->mg->get($url);
    return $result->http_response_body;    
   }
   
   public function fetchMimeMessage($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($ch, CURLOPT_USERPWD, 'api:'.Yii::app()->params['mailgun']['api_key']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Accept: message/rfc2822',
    ));        
    curl_setopt($ch, CURLOPT_URL,$url); 
    $result = curl_exec($ch);
    curl_close($ch);     
    return $result;       
   }

   public function send_mime_message($from='',$to='',$mimeStr='') {
     if ($from == '') 
       $from = Yii::app()->params['supportEmail'];
     $domain = Yii::app()->params['mail_domain'];
     $result = $this->mg->sendMessage($domain,array('from' => $from,
                                                'to' => $to,
                                                ),$mimeStr);
     return $result->http_response_body;    
   }

}

?>