<?php

    require("master.inc.php");

    $groupId = (int)$_POST["groupId"];

    if($groupId == 0){
        exit(
            Response::NIE()
        );
    }

    $group = new RideGroup($groupId);

    $rideAssigner = new RideAssigner($group->getStartLatitude(), $group->getStartLongitude());

    if(!$rideAssigner->isQueued()){
        if(!$rideAssigner->addToQueue()){
            exit(
                Response::UEO()
            );
        };
    }

    exit(
        Response::OK()
    );

?>