<?php

class Advanced extends CComponent
{
   /*
   If you would like to learn more about the features included in the free, open source basic module and the separate advanced module, visit:
 http://jeffreifman.com/filtered-open-source-imap-mail-filtering-software-for-php/feature-summary/
   */

      public function notify($x=null,$y=null,$z=null) {
        return false;
      }

      public function expireSenders() {
      return false;
      } 

      public function freshenInbox() {
        return false;
       }

      public function isQuietHours($x=0) {
        return false;
    }

    public function routeByRecipient($x=0) { 
      return false;
    }
   }
   
}
