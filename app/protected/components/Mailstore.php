<?php
// invoke Mailgun sdk
use Mailgun\Mailgun;

class Mailstore extends CComponent
{
  private $mg;
  private $mgValidate;
  
   function __construct() {  
     // initialize mailgun connection
     $this->mg = new Mailgun(Yii::app()->params['mailgun']['api_key']);
  }
  
  function fetchEvents($since_tstamp) {
    // find messages that have been stored
    //$domain = 'geogram.co'; 
    $domain = Yii::app()->params['mail_domain'];
    $queryString = array('begin'        => $since_tstamp, // 'Tue, 19 Nov 2013 00:00:00 -0000',
                         'ascending'    => 'yes',
                         'limit'        =>  100,
                         'event'=>  'stored',
                         'pretty'       => 'no');
    # Make the call to the client.
    $result = $this->mg->get("$domain/events", $queryString);
    return $result;
  }
  
  function retrieveMessages() {
    // find pending messages to be retrieved and download them
    // get parsed and store
    // get mime version
  }
    
   public function verifyWebHook($timestamp='', $token='', $signature='') {
     // Concatenate timestamp and token values
     $combined=$timestamp.$token;
     // Encode the resulting string with the HMAC algorithm
     // (using your API Key as a key and SHA256 digest mode)
     $result= hash_hmac('SHA256', $combined, Yii::app()->params['mailgun']['api_key']);
     if ($result == $signature)
       return true;
      else
      return false;    
   }
   
}

?>