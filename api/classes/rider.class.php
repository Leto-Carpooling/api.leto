<?php

    class Rider extends User{
        
        public function canRide(){
            if(
                empty($this->firstName) ||
                empty($this->lastName) ||
                empty($this->phone) ||
                empty($this->email) ||
                !$this->emailVerified

            ){
                return false;
            }

            return true;
        }
    }

?>