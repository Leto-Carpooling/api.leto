<?php
 /**
  * This class represent a zipped route from the route zipper
  */

  class ZippedRoute{
      /**
       * Array of start locations and end locations
       */
      public $startLocations = $endLocations = [];
      /**
       * Maximum distance from the intersection of one of the start locations
       */
      public $maxStartDist = 0;

      /**
       * Maximum distance from the end intersection of one of the routes end
       */
      public $maxEndDist = 0;

      /**
       * The ids of the routes included in the zipped route
       */
      
      public $routeIds = [];
      /**
       * zipped route object
       */
      public $routeObject;

      public function __construct(){

      }


  }

?>