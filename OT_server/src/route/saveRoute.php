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

 $dbManager->delete(Route::A_ROUTE, "riderId = ?", [$userId]);

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
               "userId" => $userId,
               "message" => "successfully added the new route"
           ]
       )
   )  
 );


?>