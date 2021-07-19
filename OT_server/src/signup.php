<?php
    session_start();
    if(isset($_SESSION["userId"])){
        exit("ALIE");//Already Logged In Error;
    }
    require("master.inc.php");
    require("includes/phpmailer.inc.php");

    $email = isset($_POST['email'])?$_POST['email']:null;
    $password = isset($_POST['password'])? $_POST['password']:null;

    $newUser = new User();
    $newUser->setEmail($email);
    $newUser->setPassword($password);
    exit($newUser->register());
?>