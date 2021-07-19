<?php
    require("master.inc.php");

    if(!$isLoggedIn){
       exit(Response::NLIE());
    }
    
    $code = isset($_POST["code"])?$_POST["code"]:null;
    
    $user = new User();
    if($code !== null){
        exit($user->confirmEmail($code));
    }
    exit(Response::UEO());

?>