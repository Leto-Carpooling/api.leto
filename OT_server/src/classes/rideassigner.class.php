<?php
 /**
  * RideAssigners assign rides to driver using the 
  */

use yidas\googleMaps\Client;


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
            $gMaps;

      private $groupIds,
              $groupLatLngs,
              $driverIds,
              $matrix,
              $groupSizes;

            /**
             * Pass in the latitude and longitude of the point to which a driver is to be assigned.
             */
            public function __construct($latitude, $longitude)
            {
                  $this->startLatitude = floor($latitude/RideAssigner::LAT_INCREMENT) * RideAssigner::LAT_INCREMENT;
                  $this->startLongitude = floor($longitude/RideAssigner::LONG_INCREMENT) * RideAssigner::LONG_INCREMENT;
                  $this->endLatitude =  $this->startLatitude + RideAssigner::LAT_INCREMENT;
                  $this->endLongitude = $this->startLongitude + RideAssigner::LONG_INCREMENT;
                  $this->gMaps = new Client(["key" => G_MAP_API_KEY]);
                  $this->groupIds = $this->driverIds = $this->groupLatLngs = $this->driverLatLngs = $this->matrix = [];

                  //get the maximum groupsize under this
                  $dbManager = new DbManager();
                  $groupTable = RideGroup::GRP_TABLE;
                  $rideTable = Ride::RIDE_TABLE;
                  $groupTableId = RideGroup::GRP_TABLE_ID;
                  $rideTableId = Ride::RIDE_TABLE_ID;
                  $this->groupSizes = [];

                  $dbManager->setFetchAll(true);
                  $sizes = $dbManager->query("`$groupTable` inner join `$rideTable` on $groupTableId = groupId", ["DISTINCT COUNT($rideTableId) as num_rides", "groupId"], "s_lat >= ? AND s_lat < ? AND s_long >= ? AND s_long < ? group by (groupId) order by num_rides DESC", [$this->startLatitude, $this->endLatitude, $this->startLongitude, $this->endLongitude], false);

                  if($sizes !== false){
                        foreach($sizes as $size){
                              $this->groupSizes[] = $size["num_rides"];
                        }
                  }
            }

            /**
             * Assigns drivers to riders using the hungarian algorithm
             */
            public function assign(){

                  //assign based of the group sizes
                  while (count($this->groupSizes) > 0) {
                      $trials = 0;

                      $optimalResult = false;
                      $this->populateMatrix();

                      while (!$optimalResult) {
                          if($trials > 500){
                                break;
                          }

                          $this->reduce(RideAssigner::ROW);
                          $this->reduce(RideAssigner::COLUMN);

                          //check zeros
                          $zeroData = $this->checkZeros();

                          if (count($zeroData["zerosPositions"]) < count($this->matrix)) {
                              //handle undeleted cells
                              $this->handleUndeletedCells($zeroData);
                              $trials++;
                              continue;
                          }

                          $optimalResult = true;
                          //assign the drivers
                          //driver i assigned j group j
                          $assignments = $zeroData["zerosPositions"];
                          $fbManager = new FirebaseManager();

                          foreach ($assignments as $assignment) {
                              $groupId = $this->groupIds[$assignment[1]];
                              $driverId = $this->driverIds[$assignment[0]];
                              if ($groupId == 0 || $driverId == 0) {
                                  continue;
                              }

                              $group = new RideGroup($this->groupIds[$assignment[1]]);
                              if ($group->assignDriver($driverId)) {
                                  $groupUrl = "groups/gid-$groupId/driver";
                                  $fbManager->set($groupUrl, $driverId);

                                  $driverUrl = "drivers/did-$driverId";
                                  $fbManager->set("$driverUrl/assignedGroup", $groupId);
                                  $fbManager->set("$driverUrl/arrived", false);
                              }
                          }
                          break;
                      }

                      array_shift($this->groupSizes);
                  }

            }



            /**
             * Fill in the groups that are in the region of control of this assigner
             */
            public function fillGroupData(DbManager &$dbManager){
                  $dbManager->setFetchAll(true);
                  $groupTable = RideGroup::GRP_TABLE;
                  $rideTable = Ride::RIDE_TABLE;
                  $groupTableId = RideGroup::GRP_TABLE_ID;
                  $rideTableId = Ride::RIDE_TABLE_ID;

                  $groups = $dbManager->query("(SELECT $groupTable.*, count($rideTableId) as num_rides from $groupTable inner join $rideTable on $groupTableId = groupId) as counted_rides_group", ["*"], "s_lat >= ? AND s_lat < ? AND s_long >= ? AND s_long < ? AND num_rides >= ?", [$this->startLatitude, $this->endLatitude, $this->startLongitude, $this->endLongitude, $this->groupSizes[0]]);

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
                  $driverTable = Driver::DRIVER_TABLE;
                  $driverLocTable = Driver::DRIVER_LOC_TABLE;
                  $driverTableId = Driver::DRIVER_ID;
                  $driverLocTableId = Driver::DRIVER_LOC_ID;
                  $vehicleTable = Vehicle::VEHICLE_TABLE;

                  $drivers = $dbManager->query(
                        "driverId from (SELECT driverId from `$vehicleTable` inner join `$driverTable` on $driverTableId = $vehicleTable.driverId where capacity >= ? and approval_status = ?) as vehicle_driver inner join $driverLocTable on $driverLocTableId = driverId", 
                        ["driverId"], "c_lat >= ? and c_lat < ? and c_long >= ? and c_long < ? and online_status > ?", [
                        $this->groupSizes[0],
                        "approved",
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
                                                $lowest = ($lowest > $this->matrix[$i][$j])?
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

                  $length = count($this->matrix);
                  $zeros = [];
                  $currentZero = [];

                  //check the rows and cancel the columns
                  for($i = 0; $i < $length; $i++){
                        
                        for($j = 0; $j < $length; $j++){

                              if(isset($cancelledColumns[$j]))
                              {
                                    continue;
                              }

                              if($this->matrix[$i][$j] == 0){
                                    $currentZero[] = [$i, $j];
                              }

                              if(count($currentZero) > 1){
                                    break;
                              }
                        }

                        if(count($currentZero) == 1){
                              $cancelledColumns[$currentZero[0][1]] = $currentZero[0][1];
                              $zeros[] = $currentZero[0];
                        }

                        $currentZero = [];
                  }

                  //check the columns and cancel the rows
                  for($i = 0; $i < $length; $i++){

                        if(isset($cancelledColumns[$i]))
                        {
                              continue;
                        }
                        
                        for($j = 0; $j < $length; $j++){

                              if(isset($cancelledRows[$j]))
                              {
                                    continue;
                              }

                              if($this->matrix[$j][$i] == 0){
                                    $currentZero[] = [$j, $i];
                              }

                              if(count($currentZero) > 1){
                                    break;
                              }

                        }

                        if(count($currentZero) == 1){
                              $cancelledRows[$currentZero[0][0]] = $currentZero[0][0];
                              $zeros[] = $currentZero[0]; 
                        }
                        $currentZero = [];
                  }
                   
                  return [
                        "cancelledRows" => $cancelledRows,
                        "cancelledColumns" => $cancelledColumns,
                        "zerosPositions" => $zeros
                  ];
            }

            /**
             * Handle all undeleted cells
             */

            private function handleUndeletedCells($zeroData){
                  
                  $minimum = $this->matrix[0][0];
                  $length = count($this->matrix);

                  $cancelledColumns = $zeroData["cancelledColumns"];
                  $cancelledRows = $zeroData["cancelledRows"];
                  
                  for($i = 0; $i < $length; $i++){
                        if(isset($cancelledRows[$i])){
                              continue;
                        }

                        for($j = 0; $j < $length; $j++){
                              if(isset($cancelledColumns[$j])){
                                    continue;
                              }
                              $minimum = ($this->matrix[$i][$j] < $minimum)? $this->matrix[$i][$j]: $minimum;
                        }
                  }

                  //subtract the minimum and add to intersection points
                  for($i = 0; $i < $length; $i++){
                        for($j = 0; $j < $length; $j++){
                              
                              if(isset($cancelledColumns[$j]) && isset($cancelledRows[$i])){
                                    $this->matrix[$i][$j] += $minimum;
                                    continue;
                              }

                              if(!(isset($cancelledColumns[$j]) || isset($cancelledRows[$i]))){
                                    $this->matrix[$i][$j] -= $minimum;
                              }
                             
                        }
                  }

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

            public function addToQueue(){
                  $dbManager = new DbManager();
                  $trials = 0;
                  do{
                        $id = $dbManager->insert(RAManager::RA_QUEUE_TABLE, ["s_lat", "s_long", "e_lat", "e_long"], [$this->startLatitude, $this->startLongitude, $this->endLatitude, $this->endLongitude]);
                        $trials++;
                        if($trials > 100){
                              return false;
                        }
                  }while($id == -1);

                  return true;
            }

            /**
             * The cost depends on the duration
             */
            private function getCost($groupIdIndex, $driverId){
                  $fbManager = new FirebaseManager();
                  $driverLoc = $fbManager->ref("drivers/did-$driverId/cLocation")->getValue();
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

            /**
             * Get the value of groupSize
             */ 
            public function getGroupSizes()
            {
                        return $this->groupSizes;
            }

            /**
             * Set the value of groupSize
             *
             * @return  self
             */ 
            public function setGroupSizes($groupSizes)
            {
                        $this->groupSizes = $groupSizes;

                        return $this;
            }
 }

?>