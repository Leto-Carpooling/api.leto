<?php

    /**
     * This calculates the fare for a given route.
     */

     use Kreait\Firebase\Factory;
     use yidas\googleMaps\Client;


    class FareCalculator{
        
        const BASE_FARE = 80,
              PER_KM = 25,
              PER_MIN = 4;

        private $firebaseDb,
                $factory,
                $groupId,
                $gMaps;
        
        public function __construct($groupId)
        {
            $this->factory = (new Factory())->withServiceAccount(__DIR__."/../includes/". LETO_FB_JSON);
            $this->factory = $this->factory->withDatabaseUri(LETO_NOSQL_URI);

            $this->firebaseDb = $this->factory->createDatabase();
            $this->groupId = $groupId;

            $this->gMaps = new Client(["key" => G_MAP_API_KEY]);
        }
        
        /**
         * @param int $groupId - The group id for which the route should be fetched.
         * 
         */

        public  function calculateFare(){
            $dbManager = new DbManager();
            $firstRoute = $dbManager->query(Ride::RIDE_TABLE, ["riderId, routeId"], "groupId = ? ORDER BY updated_on ASC", [$this->groupId]);

            if($firstRoute === false){
                return false;
            }

            $routeId = $firstRoute["routeId"];

            $routeMeta = $this->firebaseDb->getReference("routes/rid-$routeId");
            $routeMeta = $routeMeta->getValue();

            $distanceMatrix = $this->gMaps->distanceMatrix("place_id:".$routeMeta['startPlaceId'], "place_id:". $routeMeta['endPlaceId']);

            $distance = $distanceMatrix['rows'][0]['elements'][0]['distance'];
            $duration = $distanceMatrix['rows'][0]['elements'][0]['duration'];

            $pricePerKm = $distance['value']/1000 * FareCalculator::PER_KM;
            $pricePerMin = $duration['value']/60 * FareCalculator::PER_MIN;

            $totalPrice = round($pricePerKm) + round($pricePerMin) + FareCalculator::BASE_FARE;

            return $totalPrice;
            
        }
    }

?>