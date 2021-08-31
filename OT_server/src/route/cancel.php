<?php

 /**
  * This script cancels a user ride
  */

    require("master.inc.php");

    $routeInfo = $_POST["route-info"];

    if(!$routeInfo = json_decode($routeInfo)){
        exit(Response::UEO());
    }

    $group = new RideGroup($routeInfo->groupId);

    if($group->removeRide($routeInfo->routeId)){
        $fbManager = new FirebaseManager();
        $routeId = $routeInfo->routeId;

        //remove route
        $fbManager->remove("routes/steps/rid-$routeId");
        $fbManager->remove("routes/legs/rid-$routeId");
        $fbManager->remove("routes/gwp/rid-$routeId");
        $fbManager->remove("routes/rid-$routeId");

        //remove from group
        $groupUrl = "groups/gid-{$group->getId()}";
        $fbManager->remove("$groupUrl/usersIndex/uid-$userId");
        $fbManager->remove("$groupUrl/fares/uid-$userId");
        $fbManager->remove("$groupUrl/locations/uid-$userId");
        $fbManager->remove("$groupUrl/onlineStatus/uid-$userId");
        $fbManager->remove("$groupUrl/arrivals/uid-$userId");

        exit(
            Response::OK()
        );
    }

    exit(Response::UEO());
?>