<?php

class Socket
{
  private $_socket;

  public function __construct ($options, $mailbox = '')
  {
    $this->_socket = $this->_connect ($options['server'], $options['port'], false);
    $this->_login ($options['login'], $options['password']);
    if (!is_null ($mailbox))
    {
      $this->select_mailbox ($mailbox);
    }
  }

  private function _gets ()
  {
    $result = array ();
    while (substr ($str = fgets ($this->_socket), 0, 1) == '*')
    {
      $result[] = substr ($str, 0, -2);
    }
    $result[] = substr ($str, 0, -2);
    return $result;
  }

  private function _send ($cmd, $uid = '.')
  {
    $query = "$uid $cmd\r\n";
    $count = fwrite ($this->_socket, $query);
    if ($count == strlen ($query))
    {
      return $this->_gets ();
    }
    else
    {
      throw new Exception ("Une erreur est survenue lors de l'exécution de la commande '$cmd'");
    }
  }

  private function _connect ($server, $port, $tls)
  {
    if ($tls)
    {
      $server = 'tls://'. $server;
    }
    $fd = fsockopen ($server, $port, $errno);
    if (!$errno)
    {
      return $fd;
    }
    else
    {
      throw new Exception ('Impossible d\'ouvrir la connexion vers le serveur IMAP');
    }
  }

  private function _login ($login, $password)
  {
    $result = $this->_send ("LOGIN $login $password");
    $result = array_pop ($result);
    //if ($result != ". OK Logged in.")
    //{
    //  throw new Exception ('Login impossible');
    //}
  }

  public function __destruct ()
  {
    fclose ($this->_socket);
  }

  public function select_mailbox ($mailbox)
  {
    $result = $this->_send ("SELECT $mailbox");
    $result = array_pop ($result);
/*
    if ($result != ". OK [READ-WRITE] Select completed.")
    {
      throw new Exception ('Impossible de sélectionner la mailbox');
    }
    */
  }

  public function get_flags($uid)
  {
    $result = $this->_send ("FETCH $uid (FLAGS)");
    preg_match_all ("#\\* \\d+ FETCH \\(FLAGS \\((.*)\\)\\)#", $result[0], $matches);
    if (isset ($matches[1][0]))
    {
      return explode (' ', $matches[1][0]);
    }
    else
    {
      return array ();
    }
  }

  public function get_from() {    
    $result = $this->_send ('UID SEARCH FROM "ps@phillipadsmith.com"');
    array_pop($result);
    //var_dump($result);
    
    $str = $result[0];
    $str = str_ireplace('* SEARCH ','',$str);
    $arr = explode(' ',$str);
    var_dump($arr);
    foreach ($arr as $i) {
      $result = $this->get_google_labels($i);
      lb();
      echo 'returned result:';lb();
      var_dump($result);
      $last_id = $i;
    }
    echo 'trying search';
    $this->testgl($last_id);
    
  }
  
  public function get_google_labels ($uid)
  {
    echo "UID FETCH $uid (X-GM-LABELS)";lb();
    $result = $this->_send ("UID FETCH $uid (X-GM-LABELS)");
    if (stristr($result[0],'* BAD')) array_shift($result);
    array_pop($result);
    var_dump($result);lb();
    $i=0;
    $temp = str_ireplace('(','',$result[0]);
    $temp = str_ireplace(')','',$temp);
    var_dump($this->parseArray('('.$temp.')',$i));lb();
    echo ' call pregmatch on '.$result[0];lb();
    //preg_match_all ("/\* \\d+ FETCH \\(X-GM-LABELS \\((.*)\\) UID \\d+ \\)/", $result[0], $matches);
    //var_dump($matches);
    //preg_match_all("/ \* ([^ ]+) FETCH \(X-GM_LABELS \(([^ ]+)\) UID ([^ ]+)/",$result[0],$matches); 
    //var_dump($matches);
/*    if (isset ($matches[1][0]))
    {
      return explode (' ', $matches[1][0]);
    }
    else
    {
      return array ();
    }
    */
  }
  
  public function parseArray($read,&$i) {
    // via fsource_squirrelmail_imap_functionsimap_messages.php.html#a520
      $i = strpos($read,'(',$i);
      $i_pos = strpos($read,')',$i);
      $s = substr($read,$i+1,$i_pos - $i -1);
      $a = explode(' ',$s);
      if ($i_pos) {
          $i = $i_pos+1;
          return $a;
      } else {
          return false;
      }
  } 
  
  public function testgl($uid) {
    
    echo 'UID SEARCH HEADER Message-ID "<E46CD3FF-7141-430E-8357-518DA3A9EB7D@phillipadsmith.com>"';
    $result = $this->_send('UID SEARCH HEADER Message-ID "<E46CD3FF-7141-430E-8357-518DA3A9EB7D@phillipadsmith.com>"'."\r\n");
    array_pop($result);
    var_dump($result);    
    $str = $result[0];
    $str = str_ireplace('* SEARCH ','',$str);
    $arr = explode(' ',$str);
    var_dump($arr);
    foreach ($arr as $i) {
      $result = $this->get_google_labels($i);
      lb();
      echo 'returned result:';lb();
      var_dump($result);
    }
    
    /*
    echo "STORE 1 -X-GM-LABELS (cats)\r\n";
    $result = $this->_send("STORE 1 -X-GM-LABELS (cats)\r\n");
    var_dump($result);
    
      echo "SEARCH X-GM-LABELS +Filtering/Review\r\n";
      $result = $this->_send("SEARCH X-GM-LABELS +Filtering/Review\r\n");
      var_dump($result);

      echo "STORE 1 -X-GM-LABELS (+Filtering/Review)\r\n";
      $result = $this->_send("STORE 1 -X-GM-LABELS (+Filtering/Review)\r\n");
      var_dump($result);

    $result = $this->_send("FETCH $uid (X-GM-LABELS)\r\n");
    var_dump($result);
    $result = $this->_send("SEARCH X-GM-LABELS cats\r\n");
    var_dump($result);
    lb();
    echo "STORE $uid +X-GM-LABELS (evolution)\r\n";
*/
    //$result = $this->_send('XLIST "" "*"');
    //var_dump($result);
    //$result = $this->_send('SEARCH X-GM-RAW "has:attachment"');
    //var_dump($result);
    
  }

}