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

         /**
          * The route around which to find similar routes
          * @property Route $mainRoute
          */
         public $mainRoute;

         public function __construct(){

         }

         /**
          * Zips the routes and return the zipped route object
          * 
          */
         public function zipRoute(array $routes){

            if($this->mainRoute == null){
                return [];
            }

            $tree = new BinaryTree();
            //push the main route into the tree
            $tree->push($this->mainRoute, 0);


            foreach($routes as $route){
                $x1 = $route->getStartLng();
                $y1 = $route->getStartLat();


                $x2 = $this->mainRoute->getStartLng();
                $y2 = $this->mainRoute->getStartLat();


                $theta = acos(
                    (
                        $x1 * $x2 + $y1 * $y2
                    )
                    /
                    (
                        Utility::magnitude([0, 0], [$x1, $y1]) * Utility::magnitude([0, 0], [$y2, $x2])
                    )
                );

                $tree->push($route, $theta);
            }

           //get the route node and follow the closer to path
           $zippedRoute = new ZippedRoute();
           $zippedRoute->routeIds[] = $this->mainRoute->id;
           
           $current = $tree->root;

           while(true){
               if(!$current->closerTo){
                    break;
               }
               $current = $current->closerTo;
               $curRoute = $current->object;

               //start distance
               $sDistance = Utility::magnitude(
                   [
                       $this->mainRoute->getStartLng() * RouteZipper::LNG_TO_METER,
                       $this->mainRoute->getStartLat() * RouteZipper::LAT_TO_METER,
                   ],
                   [
                       $curRoute->getStartLng() * RouteZipper::LNG_TO_METER,
                       $curRoute->getStartLat() * RouteZipper::LAT_TO_METER
                   ]
                   );

                //endDistance
                $eDistance = Utility::magnitude(
                    [
                        $this->mainRoute->getEndLng() * RouteZipper::LNG_TO_METER,
                        $this->mainRoute->getEndLat() * RouteZipper::LAT_TO_METER,
                    ],
                    [
                        $curRoute->getEndLng() * RouteZipper::LNG_TO_METER,
                        $curRoute->getEndLat() * RouteZipper::LAT_TO_METER
                    ]
                    );


                if($sDistance <= Route::MAX_RADIUS && $eDistance <= Route::MAX_RADIUS){
                    $zippedRoute->routeIds[] = $curRoute->id;
                    if($zippedRoute->maxStartDist < $sDistance){
                        $zippedRoute->maxStartDist = $sDistance;
                    }

                    if($zippedRoute->maxEndDist < $eDistance){
                        $zippedRoute->maxEndDist = $eDistance;
                    }
                }

           }

           return $zippedRoute;
         }

         

     }

?>