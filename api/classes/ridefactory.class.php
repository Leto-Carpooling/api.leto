<?php

    class RideFactory{
        private function __construct(){

        }

        /**
         * Makes a ride and returns it's id
         */
        public static function makeRide($riderId, $groupId){
            $dbManager = new DbManager();

            $routeId = RouteFactory::getNewId();

            if($routeId == -1){
                return -1;
            }

            $rideId = $dbManager->insert(Ride::RIDE_TABLE, ["routeId", "groupId", "riderId"], [$routeId, $groupId, $riderId]);

            return $rideId;
        }
    }


?>