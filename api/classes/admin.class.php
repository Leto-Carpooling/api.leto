<?php

/**
 * An Administrator has all the functionalities of a driver
 */
class Admin extends Driver{
    
    public function __construct($id = 0){
        parent::__construct($id);
    }

    /**
     * Approves a driver whose ID is given only when the driver is approvable.
     * If not this will return false.
     */
    public function approveDriver($driver_id){
        $driver = new Driver($driver_id);
        
        return $driver->isApprovable()?$driver->approve(): false;
    }

    /**
     * changes the driver whose ID is given to pending
     * This is unconditional
     */
    public function pendDriver($driver_id){
        $driver = new Driver($driver_id);
        
        return $driver->pend();
    }

    /**
     * Declines a driver application
     */
    public function declineDriver($driver_id){
        $driver = new Driver($driver_id);
        return $driver->decline();
    }

    /**
     * Resets a  user password
     */
    public function resetPassword($user_id){
        $user = new User($user_id);
        return $user->adminResetPassword();
    }

    /**
     * Disables a user accound
     */
    public function disableAccount($user_id){
        $user = new User($user_id);
        return $user->changeAccountStatus(User::DISABLED);
    }

    /**
     * Enables a user account
     */
    public function enableAccount($user_id){
        $user = new User($user_id);
        return $user->changeAccountStatus(User::ENABLED);
    }

    
}

?>