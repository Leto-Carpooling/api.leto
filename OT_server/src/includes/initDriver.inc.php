<?php
    if(!isset($userId)){
        exit(Response::PE());
    }

    if(!$isLoggedIn){
        exit(Response::NLIE());
    }

    $user = UserFactory::createUser($userId);

    if($user === false){
        exit(Response::UEO());
    }


    //check that the user meets the minimum requirements to upgrade
    if(!$user->canUpgrade()){
        exit(Response::CUUE());
    }
    $newDriver = $user;
    
    if($user instanceof Driver === false){
        $newDriver = new Driver($user->getId());
    }
    
    unset($user);
?>