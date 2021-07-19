<?php

require("master.inc.php");
require("includes/phpmailer.inc.php");
session_readonly();

if(!isset($_SESSION["userId"])){
    exit("NLIE");//Not Logged In Error;
}

$oldPassword = isset($_POST["old-password"])?$_POST["old-password"]:"";
$newPassword = isset($_POST["new-password"])?$_POST["new-password"]:"";

$user = new User();
if($user->loadUser($_SESSION['userId'])){
    exit($user->changePassword($oldPassword, $newPassword));
}
exit("UEO"); //Unknown Error Occurred

?>