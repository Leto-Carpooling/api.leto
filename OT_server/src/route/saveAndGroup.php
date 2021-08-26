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
         $routeId = $rideInfo["routeId"];
         $groupId = $rideInfo["groupId"];

         $deleteRoutes[] = $routeId;
         if(!$stmt2->execute([$routeId])){
             continue;
         }

         if($stmt1->execute([$groupId, $groupId]) && $stmt1->rowCount() > 0){
            $deleteGroups[] = $groupId;
         }
     }
 }

 $rideId = RideFactory::makeRide($userId);

 if($rideId == -1){
     exit(Response::SQE());
 }

 $ride = new Ride($rideId);

 $routeId = $ride->getRouteId();
 $routeGrouper = new RouteGroupper();

 $groupId = $routeGrouper->group($rideId, $routePoints->start_latitude, $routePoints->start_longitude, $routePoints->end_latitude, $routePoints->end_longitude);

 $groupExist = true;
 if($groupId === false){
     $groupId = $ride->getGroupId();
     $groupExist = false;
 }

 exit(
   Response::makeResponse(
       "OK",
       json_encode(
           [
               "routeId" => $routeId,
               "groupId" => $groupId,
               "groupExist" => $groupExist,
               "deletedGroups" => $deleteGroups,
               "deletedRoutes" => $deleteRoutes,
               "userId" => $userId,
               "message" => "successfully added the new route"
           ]
       )
   )  
 );


?>