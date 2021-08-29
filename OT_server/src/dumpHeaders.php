<?php
  require("master.inc.php");
  if(!$isLoggedIn){
      exit(Response::NLIE());
  }

  echo json_encode([
    "start_latitude"=> 0,
    "start_longitude" => 0,
    "end_latitude" => 0,
    "end_longitude" => 0,
    "rideType" => 0
  ]);


?>