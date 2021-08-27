<?php
require("../master.inc.php");
require_once(__DIR__."/../includes/passwords.inc.php");
if(!$isLoggedIn){
    exit(Response::NLIE());
}

$rider = UserFactory::createUser($userId);

if(!$rider->canRide()){
    exit(Response::PNCE());
}

use yidas\googleMaps\Client;
$gMaps = new Client(['key' => G_MAP_API_KEY]);

?>