<?php

    /**
     * This class contains the route groups from the database.
     * It is different from the zippedRoute in that it has no access to the real routes
     * meta data such as distance. It only contains route ids and group id.
     */

    class RouteGroup{
        private $id,
                $routeIds,
                $driverId,
                $createdOn,
                $updatedOn;

        const GRP_TABLE = "ride_group",
              GRP_TABLE_ID = "`ride_group`.`id`",
              RIDER_GROUPING = "rider_grouping",
              RIDER_GROUPING_ID = "`rider_grouping`.`id`",
              ROUTE = "route",
              GROUP = "group";

        /**
         * All parameters are optional
         * A group is created with now id, you must call the 
         * RouteGroup#loadFromRoute($routeId) or RouteGroup#loadFromGroup($groupId) to load 
         * group from the database.
         * @param int $id - The id from which the group data should be loaded. This could either be a routeId or a groupId.
         * @param string $from - Tells the constructor the type of id that was passed. Could take values RouteGroup::GROUP or RouteGroup::ROUTE
         * 
         */
        public function __construct($id = 0, $from = RouteGroup::GROUP){
            $this->routeIds = [];
            if($id > 0){
                switch($from){
                    case RouteGroup::GROUP:
                        {
                            $this->loadFromGroup($id);
                            break;
                        }
                    case RouteGroup::ROUTE:
                        {
                            $this->loadFromRoute($id);
                            break;
                        }
                }
            }
        }

        /**
         * Loads the group information from a route that is a member of it.
         * @param int $routeId - The route whose group you want to create.
         */
        public function loadFromRoute($routeId){
            $dbManager = new DbManager();

            $dbManager->setFetchAll(true);

            $routeInfo = $dbManager->query(RouteGroup::RIDER_GROUPING, ["rideId, groupId"], "rideId = ? and groupId = (SELECT groupId from ". RouteGroup::RIDER_GROUPING. " where rideId = ?", [$routeId, $routeId]);
    
            $dbManager->setFetchAll(false);

            if($routeInfo === false || count($routeInfo) < 1){
                return false;
            }

            $groupId = $routeInfo[0]["groupId"];

            $groupInfo = $dbManager->query(RouteGroup::GRP_TABLE, ["*"], "groupId = ?", [$groupId]);

            if($groupInfo === false){
                return false;
            }

            $this->setId($groupId);
            $this->populateRids($routeInfo);
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

            $groupInfo = $dbManager->query(RouteGroup::GRP_TABLE, ["*"], "groupId = ?", [$groupId]);

            if($groupInfo === false){
                return false;
            }

            $dbManager->setFetchAll(true);

            $routeInfo = $dbManager->query(RouteGroup::RIDER_GROUPING, ["rideId, groupId"], " groupId = ?", [$groupId]);

            $dbManager->setFetchAll(false);

            if($routeInfo === false || count($routeInfo) < 1){
                return false;
            }


            $this->setId($groupId);
            $this->populateRids($routeInfo);
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

            foreach($arrayOfAssocArray as $routeInfo){
                $this->routeIds[] = $routeInfo["routeId"];
            }
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