<?php

    /**
     * This class zips multiple routes into one route with atleast n start and end points 
     * where n is the number of route to be zipped.
     * It finds similar routes
     */

     class RouteZipper{
         const LAT_TO_METER = 111200;
         const LNG_TO_METER = 111000;
         /**
          * The relative axis lenght
          * The relative axis here is the x-axis beginning from the origin
          */
         const REL_AXIS_LENGTH = 10;


         const ROUTE_START = 'start', ROUTE_END = 'end';

         public function __construct(){

         }

         /**
          * Zips the routes and return the zipped route object
          * 
          */
         public function zipRoute(array $routes){

            $tree = new BinaryTree();

            foreach($routes as $route){
                $x1 = $route->getStartLng();
                $y1 = $route->getStartLat();
                $x2 = RouteZipper::REL_AXIS_LENGTH;
                $y2 = 0;

                $theta = acos(
                    (
                        $x1 * $x2 + $y1 * $y2
                    )
                    /
                    (
                        $this->magnitude([0, 0], [$x1, $y1]) * $this->magnitude([0, 0], [$y2, $x2])
                    )
                );

                $tree->push($route, $theta);
            }

            //get the routes out

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
            
            return $this->magnitude([$x1, $y1], [$x2, $y2]);
            
         }

         /**
          * @param array $pair1 - [x1, y1]
          * @param array $pair2 - [x2, y2]
          */
         public function magnitude(array $pair1, array $pair2){
             return sqrt(
                ($pair2[0] - $pair1[0]) ** 2 + ($pair2[1] - $pair1[1])**2
             );
         }
     }

?>