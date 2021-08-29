<?php
/**
 * This script saves the route in the database and returns the route id
 */

 require_once("master.inc.php");

 $routePoints = $_POST["route-points"];

 if(!$routePoints = json_decode($routePoints)){
     exit(Response::UEO());
 }

 $dbManager = new DbManager();

 //delete any active route with this rider id

 $dbManager->setFetchAll(true);

 $deleteRouteIds = $dbManager->query(Ride::RIDE_TABLE, ["*"], "riderId = ? and completed = 0", [$userId]);

 $deleteGroups = [];
 $deleteRoutes = [];

 if($deleteRouteIds !== false && count($deleteRouteIds) > 0){
     $pdo = $dbManager->getDbConnection();

     $stmt1 =  $pdo->prepare("DELETE from ". RideGroup::GRP_TABLE . " where ". RideGroup::GRP_TABLE_ID. " = ? 
     and NOT EXISTS (SELECT ". Ride::RIDE_TABLE_ID. " from " . Ride::RIDE_TABLE . " where groupId = ?)");
     $stmt2 = $pdo->prepare("DELETE from ". Route::ROUTE_TABLE. " where ". Route:: ROUTE_TABLE_ID. " = ?");

     foreach($deleteRouteIds as $rideInfo){
         $_routeId = $rideInfo["routeId"];
         $_groupId = $rideInfo["groupId"];

         $deleteRoutes[] = $_routeId;
         if(!$stmt2->execute([$_routeId])){
             continue;
         }

         if($stmt1->execute([$_groupId, $_groupId]) && $stmt1->rowCount() > 0){
            $deleteGroups[] = $_groupId;
         }
     }
 }

 $groupExists = true;
 $groups = false;
 $addedToGroup = false;

 //try until we add the ride to a group
 while(!$addedToGroup){

    if($routePoints->rideType != 1){
        $routeGrouper = new RouteGroupper();
        $groups = $routeGrouper->findGroup($routePoints->start_latitude, $routePoints->start_longitude, $routePoints->end_latitude, $routePoints->end_longitude);
     }
    
     
     if($groups === false || count($groups) < 1){
         $groupExists = false;
         $groupId = RideGroup::makeNewGroup($routePoints->start_latitude, $routePoints->start_longitude, $routePoints->end_latitude, $routePoints->end_longitude, $routePoints->rideType);
         $rideId = RideGroup::makeAndGroupRide($groupId, $userId);

     }
     else{
        //cycle the array twice to add the ride to a group
        $index = 0;
        while(true){
            $currentGroup = $groups[$index % count($groups)];
    
            $group = new RideGroup($currentGroup["id"]);
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
                break;
            }
    
            $index++;
        }
     }
    
     if($rideId !== false){
        $addedToGroup = true;
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
     
 }
 

 
?>