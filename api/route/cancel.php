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


    if($group->removeRide($routeInfo->routeId, Route::ROUTE)){
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

        $newGroup = new RideGroup($group->getId());

        if(count($newGroup->getRouteIds()) > 0){
            $fareCalculator = new FareCalculator($newGroup->getId());
            $actualFare = $fareCalculator->calculateFare();
            
            if($actualFare === false){
                exit(Response::UEO());
            }
           
            $distributedFare = $newGroup->distributeFare($actualFare);
            $fareUrl = "$groupUrl/fares";
            $faresRef = $fbManager->ref($fareUrl);
            $fares = $faresRef->getValue();
    
            foreach($fares as $uid => $fare){
                $fbManager->set("$fareUrl/$uid", $distributedFare);
            }
        }

        

        exit(
            Response::OK()
        );
    }

    exit(Response::UEO());
?>