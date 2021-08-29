<?php
 /**
  * RideAssigners assign rides to driver using the 
  */

use yidas\googleMaps\Client;
 use Kreait\Firebase\Factory;

 class RideAssigner{
     /**
      * These increments separates the assigners from each other.
      */
      const  LAT_INCREMENT = 0.1,
             LONG_INCREMENT = 0.1;
    
      private $id,
            $startLatitude,
            $startLongitude,
            $endLatitude,
            $endLongitude,
            $factory,
            $gMaps,
            $firebaseDb;

      private $groupIds,
              $driverIds;

            /**
             * Pass in the latitude and longitude of the point to which a driver is to be assigned.
             */
            public function __construct($latitude, $longitude)
            {
                  $this->startLatitude = floor($latitude/RideAssigner::LAT_INCREMENT) * RideAssigner::LAT_INCREMENT;
                  $this->startLongitude = floor($longitude/RideAssigner::LONG_INCREMENT) * RideAssigner::LONG_INCREMENT;
                  $this->endLatitude = $this->startLatitude + RideAssigner::LAT_INCREMENT;
                  $this->endLongitude = $this->startLongitude + RideAssigner::LONG_INCREMENT;
                  
                  $this->factory = (new Factory())->withServiceAccount(__DIR__."/../includes/". LETO_FB_JSON);
                  $this->factory = $this->factory->withDatabaseUri(LETO_NOSQL_URI);
      
                  $this->firebaseDb = $this->factory->createDatabase();
                  $this->gMaps = new Client(["key" => G_MAP_API_KEY]);
            }

            /**
             * Assigns driver to riders
             */
            public function assignDrivers(){

            }

            /**
             * Checks if the ride assigner is already queued
             */
            public function isQueued(){
                  $dbManager = new DbManager();
                  $info = $dbManager->query(RAManager::RA_QUEUE_TABLE, ["id"], "s_lat = ? AND e_lat = ? AND s_long = ? AND e_long = ?", [$this->startLatitude, $this->endLatitude, $this->startLongitude, $this->endLongitude]);

                  if($info !== false){
                        return true;
                  }

                  return false;
            }

            /**
             * To save on RAM usage, the cost is computed based on the ids of the groups.
             */
            private function getCost($groupId, $driverId){
                  $driverLoc = $this->firebaseDb->getReference("drivers/did-$driverId/cLocation")->getValue();
                  $driverLat = $driverLoc["latitude"];
                  $driverLong = $driverLoc["longitude"];

                  $group = new RideGroup($groupId);

                  return $this->getDuration($group->getStartLatitude(), $group->getStartLongitude(), $driverLat, $driverLong);
            }

            /**
             * Gets the distance from google maps between to points on the map.
             */
            private function getDuration($sLat, $sLong, $eLat, $eLong){
                  $distanceMatrix = $this->gMaps->distanceMatrix("$sLat,$sLong", "$eLat,$eLong");
                  $duration = $distanceMatrix['rows'][0]['elements'][0]['duration'];
                  return $duration['value'];
            }

            /**
             * Get the value of id
             */ 
            public function getId()
            {
                  return $this->id;
            }

            /**
             * Set the value of id
             *
             * @return  self
             */ 
            public function setId($id)
            {
                  $this->id = $id;

                  return $this;
            }

            /**
             * Get the value of startLatitude
             */ 
            public function getStartLatitude()
            {
                        return $this->startLatitude;
            }

            /**
             * Set the value of startLatitude
             *
             * @return  self
             */ 
            public function setStartLatitude($startLatitude)
            {
                        $this->startLatitude = $startLatitude;

                        return $this;
            }

            /**
             * Get the value of startLongitude
             */ 
            public function getStartLongitude()
            {
                        return $this->startLongitude;
            }

            /**
             * Set the value of startLongitude
             *
             * @return  self
             */ 
            public function setStartLongitude($startLongitude)
            {
                        $this->startLongitude = $startLongitude;

                        return $this;
            }

            /**
             * Get the value of endLatitude
             */ 
            public function getEndLatitude()
            {
                        return $this->endLatitude;
            }

            /**
             * Set the value of endLatitude
             *
             * @return  self
             */ 
            public function setEndLatitude($endLatitude)
            {
                        $this->endLatitude = $endLatitude;

                        return $this;
            }

            /**
             * Get the value of endLongitude
             */ 
            public function getEndLongitude()
            {
                        return $this->endLongitude;
            }

            /**
             * Set the value of endLongitude
             *
             * @return  self
             */ 
            public function setEndLongitude($endLongitude)
            {
                        $this->endLongitude = $endLongitude;

                        return $this;
            }
 }

?>