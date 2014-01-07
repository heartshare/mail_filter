<?php

class Monitor {

  const NOTIFY_SENDER =0;
  const NOTIFY_KEYWORD = 10;
  
  // send notification to admin mobile device via pushover
  public function notify($title='',$message='',$url='',$urlTitle='',$priority=1,$token='',$device='iphone',$debug=false) {
    $po = new Pushover();
    $po->setToken(Yii::app()->params['pushover']['key']);
    $po->setUser($token);
    $po->setDevice($device);
    $po->setTitle($title);
    $po->setMessage($message);
    if ($url<>'') {
      $po->setUrl($url);      
    }
    if ($urlTitle<>'') {
      $po->setUrlTitle($urlTitle);
    }
    $po->setPriority($priority);
    $po->setTimestamp(time());
    $po->setDebug(true);
    $go = $po->send();
    if ($debug) {
      echo '<pre>';
      print_r($go);
      echo '</pre>';      
    }
  }

}

?>