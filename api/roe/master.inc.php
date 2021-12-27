<?php
require("../master.inc.php");
require_once(__DIR__."/../includes/passwords.inc.php");
if(!$isLoggedIn){
    exit(Response::NLIE());
}

?>