<?php

 /**
  * Ride Assignment Manager Class
  */

  class RAManager{
      const RA_QUEUE_TABLE = "ride_assigner_queue",
            RA_QUEUE_ID = "`ride_assigner_queue`.`id`",
            RA_TOKEN_TABLE = "ride_manager_token",
            RA_TOKEN_ID = "`ride_manager_token`.`id`";

      private $token;

      public function generateToken(){
        $dbManager = new DbManager();

        $token = uniqid();
        $tokenId = -1;
        $trials = 0;
        while($trials < 100){
            $tokenId = $dbManager->insert(RAManager::RA_TOKEN_TABLE, ["ra_token"], [$token]);
            if($tokenId != -1){
                break;
            }
            $trials++;
        }
        
      }

      public function updateToken(){
        $dbManager = new DbManager();
        if(empty($this->token)){
            $this->generateToken();
        }

        $newToken = uniqid();
        if($dbManager->update(RAManager::RA_TOKEN_TABLE, "ra_token = ?", [$newToken], "1", [])){
            $this->token = $newToken;
        }

      }
  }

?>