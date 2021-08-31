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
             LONG_INCREMENT = 0.1,
             ROW = "row",
             COLUMN = "column",
             HIGHEST_COST = 1000000000000000000.0;
    
      private $id,
            $startLatitude,
            $startLongitude,
            $endLatitude,
            $endLongitude,
            $factory,
            $gMaps,
            $firebaseDb;

      private $groupIds,
              $groupLatLngs,
              $driverIds,
              $matrix;

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
                  $this->groupIds = $this->driverIds = $this->groupLatLngs = $this->driverLatLngs = $this->matrix = [];
            }

            /**
             * Assigns drivers to riders using the hungarian algorithm
             */
            public function assignDrivers(){
                  $optimalResult = false;
                  $this->populateMatrix();
                  
                  while(!$optimalResult){
                        //row reduction
                        $this->reduce(RideAssigner::ROW);
                        
                        //column reduction
                        $this->reduce(RideAssigner::COLUMN);

                        //check zeros

                        //handle undeleted cells
                  }

            }



            /**
             * Fill in the groups that are in the region of control of this assigner
             */
            public function fillGroupData(DbManager &$dbManager){
                  $dbManager->setFetchAll(true);

                  $groups = $dbManager->query(RideGroup::GRP_TABLE, ["*"], "s_lat >= ? AND s_lat < ? AND s_long >= ? AND s_long < ?", [$this->startLatitude, $this->endLatitude, $this->startLongitude, $this->endLongitude]);

                  if($groups == false or count($groups)){
                        return;
                  }

                  foreach($groups as $groupInfo){
                        $this->groupIds[] = $groupInfo["id"];
                        $this->groupLatLngs[] = [$groupInfo["s_lat"], $groupInfo["s_long"]];
                  }

            }

            /**
             * Populates the driverIds array with it's latitudets and longitudes
             * The drivers are in the region of the RA
             */
            public function fillDriverData(DbManager &$dbManager){
                  $dbManager->setFetchAll(true);
                  $drivers = $dbManager->query(Driver::DRIVER_LOC_TABLE. " inner join ". Driver::DRIVER_TABLE. " on ". Driver::DRIVER_ID. " = ". Driver::DRIVER_LOC_ID, [Driver::DRIVER_LOC_TABLE.".driverId"], "c_lat >= ? and c_lat < ? and c_long >= ? and c_long < ? and online_status > ?", [
                        $this->startLatitude,
                        $this->endLatitude,
                        $this->startLongitude,
                        $this->endLongitude,
                        0
                  ], false);

                  if($drivers === false){
                        return;
                  }

                  foreach($drivers as $driverInfo){
                        $this->driverIds[] = $driverInfo["driverId"];
                  }
            }

            /**
             * Populate the rows of the matrix
             * drivers are the rows and groups are th columns
             */

            function populateMatrix(){
                  $dbManager = new DbManager();
                  $this->fillGroupData($dbManager);
                  $this->fillDriverData($dbManager);

                  $maxLength = max(count($this->groupIds), count($this->driverIds));

                  for($i = 0; $i < $maxLength; $i++){
                        
                        if(!isset($this->driverIds[$i])){
                              $this->driverIds[$i] = 0;
                        }

                        if(!isset($this->groupIds[$i])){
                              $this->groupIds[$i] = 0;
                        }

                        for($j = 0; $j < $maxLength; $j++){
                              if($this->driverIds[$i] == 0 || $this->groupIds[$j] == 0){
                                    $this->matrix[$i][$j] = 0;
                                    continue;
                              }

                              $this->matrix[$i][$j] = $this->getCost($j, $this->driverIds[$i]);
                        }
                  }
            }

            private function reduce($what = RideAssigner::ROW){
                  $length = count($this->matrix);
                  $minimums = [];

                  switch($what){
                        case RideAssigner::ROW:
                              {
                                    for($i = 0; $i < $length; $i++){
                                          $lowest = $this->matrix[$i][0];

                                          for($j = 0; $j < $length; $j++){
                                                if($this->matrix[$i][$j] == 0){
                                                      $lowest = 0;
                                                      break;
                                                }
                                                $lowest = $lowest > $this->matrix[$i][$j]?
                                                          $this->matrix[$i][$j]:$lowest;
                                          }

                                          $minimums[] = $lowest;
                                    }

                                    //subtract the lowest
                                    for($i = 0; $i < $length; $i++){
                                          for($j = 0; $j < $length; $j++){
                                                $this->matrix[$i][$j] -= $minimums[$i];
                                          }
                                    }
                                    break;
                              }
                        case RideAssigner::COLUMN:
                              {

                                    for($j = 0; $j < $length; $j++){
                                          $lowest = $this->matrix[0][$j];

                                          for($i = 0; $i < $length; $i++){
                                                if($this->matrix[$i][$j] == 0){
                                                      $lowest = 0;
                                                      break;
                                                }
                                                $lowest = $lowest > $this->matrix[$i][$j]?
                                                          $this->matrix[$i][$j]:$lowest;
                                          }

                                          $minimums[] = $lowest;
                                    }

                                    //subtract the lowest
                                    for($j = 0; $j < $length; $j++){
                                          for($i = 0; $i < $length; $i++){
                                                $this->matrix[$i][$j] -= $minimums[$j];
                                          }
                                    }
                                    break;
                              }
                  }
            }

            /**
             * Checks and return the number of zeros and cancelled index array
             */
            private function checkZeros()
            {
                  $cancelledRows = [];
                  $cancelledColumns = [];
                  $indexToCancel = -1;
                  $length = count($this->matrix);
                  
                  //check the rows and cancel the columns
                  
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
             * The cost depends on the duration
             */
            private function getCost($groupIdIndex, $driverId){
                  $driverLoc = $this->firebaseDb->getReference("drivers/did-$driverId/cLocation")->getValue();
                  $driverLat = $driverLoc["latitude"];
                  $driverLong = $driverLoc["longitude"];

                  return $this->getDuration($this->groupLatLngs[$groupIdIndex][0], $this->groupLatLngs[$groupIdIndex][1], $driverLat, $driverLong);
            }

            /**
             * Gets the distance from google maps between to points on the map.
             */
            private function getDuration($sLat, $sLong, $eLat, $eLong){
                  $distanceMatrix = $this->gMaps->distanceMatrix("$sLat,$sLong", "$eLat,$eLong");
                  if($distanceMatrix["status"] != "OK" || $distanceMatrix["rows"][0]["elements"][0]["status"] != "OK"){
                        return RideAssigner::HIGHEST_COST;
                  }

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