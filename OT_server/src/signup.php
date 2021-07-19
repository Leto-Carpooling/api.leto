<?php
    require("master.inc.php");
    if($isLoggedIn){
        exit(Response::$ALIE);
    }
    require("includes/phpmailer.inc.php");

    $email = isset($_POST['email'])?$_POST['email']:null;
    $password = isset($_POST['password'])? $_POST['password']:null;

    $newUser = new User();
    $newUser->setEmail($email);
    $newUser->setPassword($password);
    exit($newUser->register());
?>