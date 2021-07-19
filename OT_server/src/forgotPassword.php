<?php
    require("master.inc.php");
    if($isLoggedIn){
        exit(Response::ALIE());
    }

    require("includes/phpmailer.inc.php");

    $dataObject = json_decode($_POST['data']);
    $user = new User();

    switch($dataObject->action){
        case "sl":
            {
                $email = isset($_POST['email'])?$_POST['email']:null;
                $user->setEmail($email);
                exit($user->forgotPassword());
            }
        case "cp":
            {
                $code = isset($_POST['code'])?$_POST['code']:null;
                if($code == null){
                    exit(Response::makeResponse("NCE", "You entered an invalid code"));
                }
                $newPassword = isset($_POST['new-password'])?$_POST['new-password']:null;
                $user->setPassword($newPassword);
                exit($user->setNewPassword($code));
            }
        default:
        {
            exit(Response::UEO());
        }
    }

    

?>