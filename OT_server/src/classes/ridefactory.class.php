<?php

    class RideFactory{
        private function __construct(){

        }

        /**
         * Makes a ride and returns it's id
         */
        public static function makeRide($riderId, $sLat, $sLong, $eLat, $eLong, $numOfRiders){
            $dbManager = new DbManager();

            $routeId = RouteFactory::getNewId();

            if($routeId == -1){
                return -1;
            }

            $groupId = RideGroup::makeNewGroup($sLat, $sLong, $eLat, $eLong, $numOfRiders);

            if($groupId == -1){
                return -1;
            }

            $rideId = $dbManager->insert(Ride::RIDE_TABLE, ["routeId", "groupId", "riderId"], [$routeId, $groupId, $riderId]);

            return $rideId;
        }
    }


?>