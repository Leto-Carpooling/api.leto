<?php
    /**
     * This class groups routes from the database
     */

    class RouteGroupper{        
                
        public function __construct(){

        }

        /**
         * Finds the group that a given coordinate belongs to
         */
        public function findGroup($sLat, $sLong, $eLat, $eLong){
            $dbManager = new DbManager();

            $dbManager->setFetchAll(true);
            $groups = $dbManager->query("
            (SELECT ". RideGroup::GRP_TABLE .".* , COUNT(". Ride::RIDE_TABLE_ID. ") as num_of_ride, (SQRT(POWER(($sLong - s_long), 2) + POWER( $sLat - s_lat ,2)) * ". Utility::LAT_TO_METER .")  as start_distance, (SQRT(POWER(($eLong - e_long), 2) + POWER( $eLat - e_lat ,2)) * ". Utility::LAT_TO_METER .") as end_distance FROM `". RideGroup::GRP_TABLE ."` INNER JOIN ride on ride.groupId = ". RideGroup::GRP_TABLE_ID." group by ". RideGroup::GRP_TABLE_ID .") as ride_group_distance ", 
            [ 
                "*",
            ], 
            "start_distance <= ? AND end_distance <= ? AND num_riders != ? AND num_of_ride <= ? group by ride_group_distance.id order by start_distance, end_distance ASC", [400, 400, 1, Vehicle::getMaxCapacity()], false);

            // $file = fopen("sql.txt", "a");
            // fwrite($file, $dbManager->getLastQuery());
            // fclose($file);

         //   echo "SQL: ". $dbManager->getLastQuery();

            if($groups === false){
                return false;
            }
            
            return $groups;
        }

    }


?> 