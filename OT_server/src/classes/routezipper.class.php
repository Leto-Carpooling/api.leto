<?php

    /**
     * This class zips multiple routes into one route with atleast n start and end points 
     * where n is the number of route to be zipped.
     * It finds similar routes
     */

     class RouteZipper{
         const LAT_TO_METER = 111200;
         const LNG_TO_METER = 111000;


         const ROUTE_START = 'start', ROUTE_END = 'end';

         public function __construct(){

         }

         /**
          * Zips the routes and return the zipped route object
          * Complexity O(n2)
          */
         public function zipRoute(Route $route, array $routes){
            $zippedRoute = new ZippedRoute();

            //put the first route in the array
            //$route = $routes[0];
            $zippedRoute->startLocations[] = $route->getStepAt(0);

            //put the route into the binary tree
            //the the start route is the start node

            

         }

         /**
          * {
          *      "distance": {
          *      "text": "10 ft",
          *      "value": 3
          *      },
          *      "duration": {
          *      "text": "1 min",
          *      "value": 0
          *      },
          *      "end_location": {
          *      "lat": 33.8160679,
          *      "lng": -117.9225314
          *      },
          *      "html_instructions": "Head <b>southwest</b>",
          *      "polyline": {
          *      "points": "qukmEvvvnUB@"
          *      },
          *      "start_location": {
          *      "lat": 33.8160897,
          *      "lng": -117.9225226
          *      },
          *      "travel_mode": "DRIVING"
          * }
          */
         private function findDistance($step1, $step2, $startOrEnd){
            $x1 = $x2 = $y1 = $y2 = 0;
            switch($startOrEnd){
                case RouteZipper::ROUTE_END:
                    {
                        $x1 = $step1->end_location->lng;
                        $x2 = $step2->end_location->lng;
            
                        $y1 = $step1->end_location->lat;
                        $y2 = $step2->end_location->lat;
                        break;
                    }
                default:
                    {
                        $x1 = $step1->start_location->lng;
                        $x2 = $step2->start_location->lng;
            
                        $y1 = $step1->start_location->lat;
                        $y2 = $step2->start_location->lat;
                    }
            }
            
            return sqrt(
                ($x2 - $x1)**2 - ($y2 - $y1)**2
            );
            
         }
     }

?>