<?php

    /**
     * This class contains the route groups from the database.
     * It is different from the zippedRoute in that it has no access to the real routes
     * meta data such as distance. It only contains route ids and group id.
     */

    class RideGroup{
        private $id,
                $routeIds,
                $driverId,
                $createdOn,
                $updatedOn;

        const GRP_TABLE = "ride_group",
              GRP_TABLE_ID = "`ride_group`.`id`",
              GROUP = "group";

        /**
         * All parameters are optional
         * A group is created with now id, you must call the 
         * RideGroup#loadFromRide($routeId) or RideGroup#loadFromGroup($groupId) to load 
         * group from the database.
         * @param int $id - The id from which the group data should be loaded. This could either be a routeId or a groupId.
         * @param string $from - Tells the constructor the type of id that was passed. Could take values RideGroup::GROUP or RideGroup::ROUTE
         * 
         */
        public function __construct($id = 0, $from = RideGroup::GROUP){
            $this->routeIds = [];
            if($id > 0){
                switch($from){
                    case RideGroup::GROUP:
                        {
                            $this->loadFromGroup($id);
                            break;
                        }
                    case Route::ROUTE:
                        {
                            $this->loadFromRide($id);
                            break;
                        }
                }
            }
        }

        /**
         * Loads the group information from a route that is a member of it.
         * @param int $routeId - The route whose group you want to create.
         */
        public function loadFromRide($routeId){
            $dbManager = new DbManager();

            $dbManager->setFetchAll(true);

            $rideInfo = $dbManager->query(Ride::RIDE_TABLE, ["routeId, groupId"], "routeId = ? and groupId = (SELECT groupId from ". Ride::RIDE_TABLE. " where routeId = ?)", [$routeId, $routeId]);
    
            $dbManager->setFetchAll(false);

            if($rideInfo === false || count($rideInfo) < 1){
                return false;
            }

            $groupId = $rideInfo[0]["groupId"];

            $groupInfo = $dbManager->query(RideGroup::GRP_TABLE, ["*"], "groupId = ?", [$groupId]);

            if($groupInfo === false){
                return false;
            }

            $this->setId($groupId);
            $this->populateRids($rideInfo);
            $this->setCreatedOn($groupInfo["created_on"]);
            $this->setUpdatedOn($groupInfo["updated_on"]);
            
            return true;
        }

        /**
         * Loads the group information from a group id in the database.
         * @param int $groupId - The Id of the group that you want to create.
         */
        public function loadFromGroup($groupId){
            $dbManager = new DbManager();

            $groupInfo = $dbManager->query(RideGroup::GRP_TABLE, ["*"], "groupId = ?", [$groupId]);

            if($groupInfo === false){
                return false;
            }

            $dbManager->setFetchAll(true);

            $rideInfo = $dbManager->query(Ride::RIDE_TABLE, ["routeId, groupId"], " groupId = ?", [$groupId]);

            $dbManager->setFetchAll(false);

            if($rideInfo === false || count($rideInfo) < 1){
                return false;
            }


            $this->setId($groupId);
            $this->populateRids($rideInfo);
            $this->setCreatedOn($groupInfo["created_on"]);
            $this->setUpdatedOn($groupInfo["updated_on"]);
            
            return true;
        }

        /**
         * populates the routeIds array
         * @param array $arrayOfAssocArray - the array that contains the database result
         * of route ids
         */
        function populateRids($arrayOfAssocArray){
            $this->routeIds = [];

            foreach($arrayOfAssocArray as $rideInfo){
                $this->routeIds[] = $rideInfo["routeId"];
            }
        }

        /**
         * Makes and return it's id
         * @return int
         */
        public static function makeNewGroup(){
            $dbManager = new DbManager();

            $groupId = $dbManager->insert(RideGroup::GRP_TABLE, ["driverId"], [null]);

            if($groupId == -1){
                return -1;
            }

            return $groupId;
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
         * Get the value of routeIds
         */ 
        public function getRouteIds()
        {
                        return $this->routeIds;
        }

        /**
         * Set the value of routeIds
         *
         * @return  self
         */ 
        public function setRouteIds($routeIds)
        {
                        $this->routeIds = $routeIds;

                        return $this;
        }

        /**
         * Get the value of driverId
         */ 
        public function getDriverId()
        {
                        return $this->driverId;
        }

        /**
         * Set the value of driverId
         *
         * @return  self
         */ 
        public function setDriverId($driverId)
        {
                        $this->driverId = $driverId;

                        return $this;
        }

        /**
         * Get the value of createdOn
         */ 
        public function getCreatedOn()
        {
                        return $this->createdOn;
        }

        /**
         * Set the value of createdOn
         *
         * @return  self
         */ 
        public function setCreatedOn($createdOn)
        {
                        $this->createdOn = $createdOn;

                        return $this;
        }

        /**
         * Get the value of updatedOn
         */ 
        public function getUpdatedOn()
        {
                        return $this->updatedOn;
        }

        /**
         * Set the value of updatedOn
         *
         * @return  self
         */ 
        public function setUpdatedOn($updatedOn)
        {
                        $this->updatedOn = $updatedOn;

                        return $this;
        }

        
    }

?>