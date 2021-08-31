<?php

 /**
  * Ride Assignment Manager Class
  */

  class RAManager{
      const RA_QUEUE_TABLE = "ride_assigner_queue",
            RA_QUEUE_ID = "`ride_assigner_queue`.`id`",
            RA_TOKEN_TABLE = "ride_manager_token",
            RA_TOKEN_ID = "`ride_manager_token`.`id`",
            RA_OUTPUT_FILE = "ra_out.txt";

      private $token,
              $rAQueue;


      public function __construct(){
        $this->updateToken();
        $trails = 0;

        /**
         * Try to load the queue
         */
        while(!$this->loadQueue()){
          $trails++;
          if($trails > 1000){
            return;
          }
        }
      }

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

      /**
       * Load the RA Queue  
       */
      private function loadQueue(){
        $dbManager = new DbManager();
        $dbManager->setFetchAll(true);
        $rAQueue = $dbManager->query(RAManager::RA_QUEUE_TABLE, ["*"], "1 order by created_on DESC", []);
        if($rAQueue === false){
          return false;
        }
        $this->rAQueue = $rAQueue;
        return true;
      }

      /**
       * Disburse RAs
       */
      public function disburseRAs(){
        foreach($this->rAQueue as $rAInfo){

        }
      }

      private function startRA($rAInfo){
        $sLat = $rAInfo["s_lat"];
        $sLong = $rAInfo["s_long"];
        $eLat = $rAInfo["e_lat"];
        $eLong = $rAInfo["s_long"];
        
        $cmd = "php assignDrivers.php $sLat $sLong $eLat $eLong";
      }

      /**
       * Get the value of token
       */ 
      public function getToken()
      {
            return $this->token;
      }

      /**
       * Set the value of token
       *
       * @return  self
       */ 
      public function setToken($token)
      {
            $this->token = $token;

            return $this;
      }
  }

?>