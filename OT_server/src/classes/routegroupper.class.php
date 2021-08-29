<?php
    /**
     * This class groups routes from the database
     */

    class RouteGroupper{        
                
        public function __construct(){

        }

        /**
         * Adds a ride to a group and return the group id
         */
        public function findGroup($sLat, $sLong, $eLat, $eLong){
            $dbManager = new DbManager();

            $dbManager->setFetchAll(true);
            $groups = $dbManager->query("
            (SELECT ". RideGroup::GRP_TABLE .".* , COUNT(". Ride::RIDE_TABLE_ID. ") as num_of_ride, (SQRT(POWER(($sLong - s_long), 2) + POWER( $sLat - s_lat ,2)) * ". Utility::LAT_TO_METER .")  as start_distance, (SQRT(POWER(($eLong - e_long), 2) + POWER( $eLat - e_lat ,2)) * ". Utility::LAT_TO_METER .") as end_distance FROM `". RideGroup::GRP_TABLE ."` INNER JOIN ride on ride.groupId = ". RideGroup::GRP_TABLE_ID.") as ride_group_distance ", 
            [ 
                "*",
            ], 
            "start_distance <= ? AND end_distance <= ? AND num_riders != ? AND num_of_ride <= ? group by ride_group_distance.id order by start_distance, end_distance ASC", [400, 400, 1, Vehicle::getMaxCapacity()], false);

         //   echo "SQL: ". $dbManager->getLastQuery();

            if($groups === false){
                return false;
            }
            
            return $groups;
        }

        /**
         * Get the value of newSLat
         */ 
        public function getNewSLat()
        {
                return $this->newSLat;
        }

        /**
         * Set the value of newSLat
         *
         * @return  self
         */ 
        public function setNewSLat($newSLat)
        {
                $this->newSLat = $newSLat;

                return $this;
        }

        /**
         * Get the value of newSLong
         */ 
        public function getNewSLong()
        {
                        return $this->newSLong;
        }

        /**
         * Set the value of newSLong
         *
         * @return  self
         */ 
        public function setNewSLong($newSLong)
        {
                        $this->newSLong = $newSLong;

                        return $this;
        }

        /**
         * Get the value of newELat
         */ 
        public function getNewELat()
        {
                        return $this->newELat;
        }

        /**
         * Set the value of newELat
         *
         * @return  self
         */ 
        public function setNewELat($newELat)
        {
                        $this->newELat = $newELat;

                        return $this;
        }

        /**
         * Get the value of newELong
         */ 
        public function getNewELong()
        {
                        return $this->newELong;
        }

        /**
         * Set the value of newELong
         *
         * @return  self
         */ 
        public function setNewELong($newELong)
        {
                        $this->newELong = $newELong;

                        return $this;
        }
    }


?> 