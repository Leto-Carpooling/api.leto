<?php
require("../master.inc.php");
if(!$isLoggedIn){
    exit(Response::NLIE());
}

$rider = UserFactory::createUser($userId);

if(!$rider->canRide()){
    exit(Response::PNCE());
}


?>