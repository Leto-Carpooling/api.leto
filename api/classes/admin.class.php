<?php

class Admin extends Driver{
    
    public function __construct($id = 0){
        parent::__construct($id);
    }

    public function approveDriver($driver_id){
        $driver = new Driver($driver_id);
        
        return $driver->isApprovable()?$driver->approve(): false;
    }

    public function pendDriver($driver_id){
        $driver = new Driver($driver_id);
        
        return $driver->pend();
    }

    public function declineDriver($driver_id){
        $driver = new Driver($driver_id);
        return $driver->decline();
    }

    public function resetPassword($user_id){
        $user = new User($user_id);
        return $user->adminResetPassword();
    }

    public function disableAccount($user_id){
        $user = new User($user_id);
        return $user->changeAccountStatus(User::DISABLED);
    }

    public function enableAccount($user_id){
        $user = new User($user_id);
        return $user->changeAccountStatus(User::ENABLED);
    }

    
}

?>