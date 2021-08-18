<?php
 /**
  * This class represent a zipped route from the route zipper
  */

  class ZippedRoute{
      
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
      

      public function __construct(){
        
      }


  }

?>