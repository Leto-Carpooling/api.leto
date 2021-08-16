<?php

    /**
     * This class zips multiple routes into one route with atleast n start and end points 
     * where n is the number of route to be zipped.
     * It finds similar routes
     */

     class RouteZipper{
         public function __construct(){

         }

         /**
          * Zips the routes and return the zipped route object
          * Complexity O(n2)
          */
         public function zipRoute(Route $route, array $routes){
            $zippedRoute = new ZippedRoute();

            //put the first route in the array
            $zippedRoute->startLocations[] = $route->getStepAt(0);

            //if a route more than 200 meters away from the start point
         }
     }

?>