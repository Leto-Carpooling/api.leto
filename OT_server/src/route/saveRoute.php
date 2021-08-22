<?php
/**
 * This script saves the route in the database and returns the route id
 */

 require_once("master.inc.php");

 if(!$isLoggedIn){
     exit(Response::NLIE());
 }

 $dbManager = new DbManager();

 //delete any active route with this rider id
 
 $deletedRouteId = $dbManager->query(Route::A_ROUTE, [Route::A_ROUTE_ID], "riderId = ?", [$userId]);

 if($deletedRouteId !== false){
     $deletedRouteId = $deletedRouteId["id"];
     $dbManager->delete(Route::A_ROUTE, "riderId = ?", [$userId]);
 }
 else{
     $deletedRouteId = 0;
 }

 $routeId = $dbManager->insert(Route::A_ROUTE, ["riderId"], [$userId]);

 if($routeId == -1){
     exit(Response::SQE());
 }

 exit(
   Response::makeResponse(
       "OK",
       json_encode(
           [
               "routeId" => $routeId,
               "deletedRouteId" => $deletedRouteId,
               "userId" => $userId,
               "message" => "successfully added the new route"
           ]
       )
   )  
 );


?>