<?php
/**
 * This script saves the route in the database and returns the route id
 */

 require("master.inc.php");
 
 # get route points
 $routePoints = $_POST["route-points"];
 
 # get group privacy
 $isPrivate = boolval($_POST["is-private"]);

 

 if(!$routePoints = json_decode($routePoints)){
     exit(Response::UEO());
 }


 $dbManager = new DbManager();
 $fbManager = new FirebaseManager();

 //delete any active route with this rider id

 $deleteRouteIds = $dbManager->query(Ride::RIDE_TABLE, ["*"], "riderId = ? and completed = 0", [$userId], true, true);

 $deleteGroups = [];
 $deleteRoutes = [];

 if($deleteRouteIds !== false && count($deleteRouteIds) > 0){
     $pdo = $dbManager->getDbConnection();

    //  $stmt1 =  $pdo->prepare("DELETE from ". RideGroup::GRP_TABLE . " where ". RideGroup::GRP_TABLE_ID. " = ? 
    //  and NOT EXISTS (SELECT ". Ride::RIDE_TABLE_ID. " from " . Ride::RIDE_TABLE . " where groupId = ?)");
     $stmt2 = $pdo->prepare("DELETE from ". Route::ROUTE_TABLE. " where ". Route:: ROUTE_TABLE_ID. " = ?");

     foreach($deleteRouteIds as $rideInfo){
         $_routeId = $rideInfo["routeId"];
         $_groupId = $rideInfo["groupId"];
         $_riderId = $rideInfo["riderId"];

         $deleteRoutes[] = $_routeId;
        
         if(!$stmt2->execute([$_routeId])){
             continue;
         }

         $fbManager->remove("routes/steps/rid-$_routeId");
         $fbManager->remove("routes/legs/rid-$_routeId");
         $fbManager->remove("routes/gwp/rid-$_routeId");
         $fbManager->remove("routes/rid-$_routeId");
         $fbManager->remove("groups/gid-$_groupId/fares/uid-$_riderId");
         $fbManager->remove("groups/gid-$_groupId/usersIndex/uid-$_riderId");
         $fbManager->remove("groups/gid-$_groupId/locations/uid-$_riderId");
         $fbManager->remove("groups/gid-$_groupId/onlineStatus/uid-$_riderId");
         $fbManager->remove("groups/gid-$_groupId/arrivals/uid-$_riderId");
     }
 }

 $dbManager->delete(Ride::RIDE_TABLE, "riderId = ? and completed = 0", [$userId]);

 $groupExists = true;
 $groups = false;
 $addedToGroup = false;
 $trials = 0;

 //try until we add the ride to a group
 while(!$addedToGroup){

    if($routePoints->rideType != 1){
        $routeGrouper = new RouteGroupper();
        $groups = $routeGrouper->findGroup($routePoints->start_latitude, $routePoints->start_longitude, $routePoints->end_latitude, $routePoints->end_longitude);
     }
    
     //if no matching group was found
     if($groups === false || count($groups) < 1){
         $groupExists = false;
         $groupId = RideGroup::makeNewGroup($routePoints->start_latitude, $routePoints->start_longitude, $routePoints->end_latitude, $routePoints->end_longitude, $routePoints->rideType);

         $fbManager->set("groups/gid-$groupId", [
            "startPlaceId" => $routePoints->startPlaceId,
            "endPlaceId" => $routePoints->endPlaceId,
            "usersIndex" => [],
            "pickUpPointId" => $routePoints->startPlaceId,
            "sLat"=>$routePoints->start_latitude,
            "sLong" => $routePoints->start_longitude,
            "eLat" => $routePoints->end_latitude,
            "eLong" => $routePoints->end_longitude,
            "fares" => [],
            "locations" => [],
            "timer" => ($routePoints->rideType == 0)? $routePoints->groupTimer: 0,
            "onlineStatus" => [],
            "driver"=> 0
         ]);

         $rideId = RideGroup::makeAndGroupRide($groupId, $userId, true);
     }
     else{
        //cycle the array twice to add the ride to a group
        $index = 0;
        while(true){
            $currentGroup = $groups[$index % count($groups)];
            $group = new RideGroup($currentGroup["id"]);

            //check if the group is full, if so, remove it from the suggested group list
            //@todo - the max capacity of the vehicle nearby will be used instead of the 
            //overall vehicle's max capacity
            if($group->getNumberOfRides() >= Vehicle::getMaxCapacity()){
                unset($groups[$index]);
                $groups = array_values($groups);
                continue;
            }
    
            if(count($groups) < 1){
                break;
            }
    
            $rideId = RideGroup::makeAndGroupRide($group->getId(), $userId);
    
            if($rideId !== false){
                $groupId = $group->getId();
                break;
            }
    
            $index++;
        }
     }
    
     if($rideId !== false){
        $addedToGroup = true;
        $fbManager->set("groups/gid-$groupId/usersIndex/uid-$userId", true);
        $fbManager->set("groups/gid-$groupId/arrivals/uid-$userId", -1);
     }
    
     if($addedToGroup){
        $ride = new Ride($rideId);
    
        $routeId = $ride->getRouteId();
        $groupId = $ride->getGroupId();
       
        exit(
           Response::makeResponse(
               "OK",
               json_encode(
                   [
                       "routeId" => $routeId,
                       "groupId" => $groupId,
                       "groupExists" => $groupExists,
                       "deletedGroups" => $deleteGroups,
                       "deletedRoutes" => $deleteRoutes,
                       "userId" => $userId,
                       "message" => "successfully added the new route"
                   ]
               )
           )  
         );
     }
     
     $trials++;
     if($trials >= 1000){
         exit(
            Response::makeResponse("UAGRE", "Unable to group ride. please try again")
         );
     }
 }
 

 
?>