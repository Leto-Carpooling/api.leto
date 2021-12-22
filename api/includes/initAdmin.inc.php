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

    $admin = $user;

    if($user instanceof Admin === false){
        exit(Response::UTE());
    }
    
    $admin = new Admin($user->getId());
    unset($user);

?>