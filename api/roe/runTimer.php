<?php

require("../master.inc.php");
require_once(__DIR__."/../includes/passwords.inc.php");

 $groupId = $argv[1];

 $fbManager = new FirebaseManager();
 $groupUrl = "groups/gid-$groupId/timer";

 do{
    $currentTime = $fbManager->ref($groupUrl)->getValue();
    $currentTime -= 1;
    if($currentTime < 0){
        $currentTime = 0;
    }
    $fbManager->set($groupUrl, $currentTime);
    sleep(1);
 }while($currentTime > 0);
 

?>