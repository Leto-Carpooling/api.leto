<?php

/**
 * this script calculates the fare for a given user given that they are part of a group
 */

require("master.inc.php");

 $groupId = isset($_POST["group-id"])?(int)$_POST["group-id"]:0;

 if($groupId == 0){
     exit(Response::NIE());
 }

 $fareCalculator = new FareCalculator($groupId);

 $actualFare = $fareCalculator->calculateFare();
 
 if($actualFare === false){
     exit(Response::UEO());
 }


 //increase or decrease based on the number of people in the group.

 $group = new RideGroup($groupId);

 $distributedFare = $group->distributeFare($actualFare);
 $fbManager = new FirebaseManager();
 $fareUrl = "groups/gid-$groupId/fares";
 $faresRef = $fbManager->ref($fareUrl);

 $fares = $faresRef->getValue();

 foreach($fares as $uid => $fare){
    $fbManager->set("$fareUrl/$uid", $distributedFare);
 }
 
 exit(
     Response::makeResponse(
         "OK",
         json_encode(
             ["groupId" => $groupId,
             "sharedFare" => $distributedFare,
             "actualFare" => $actualFare,
             "currency" => "KES"]
         )
     )
 );

?>