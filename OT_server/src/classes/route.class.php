<?php

    /**
     * Route class to represent the routes a client is traversing
     */
    class Route{
        const MAX_RADIUS = 200; //200 meters near each other

        /**
         * Points to the current step in the route
         */
        public $pointer = 0;
        /**
         * Id of the route
         */
        public $id;
        /**
         * The route object returned by the directions API
         */
        public $routeObject;

        public function __construct($json_route){
            $this->routeObject = json_decode($json_route);
        }

        /**
         * Checks if this route intersects another route
         */
        public function isCloserTo(Route $route){

        }

        /**
         * Returns the total distance from the origin to the destination in meters
         * @return int
         */
        public function getTotalDistance(){
            return $this->getLegs()->distance->value;
        }

        /**
         * Returns the total duration of a route
         */
        public function getTotalDuration(){
            return $this->getLegs()->duration->value;
        }

        /**
         * Returns the legs of the route from the directions api
         * @return object
         */
        public function getLegs(){
            return $this->routeObject->routes[0]->legs[0];
        }

        /**
         * Returns the steps array in a route
         * @return array
         */
        public function getSteps(){
            return $this->getLegs()->steps;
        }
        /**
         * Returns the start latitude of the route
         * @return double
         */
        public function getStartLat(){
            return $this->getLegs()->start_location->lat;
        }

        /**
         * Get the start longitude of the route
         * @return double
         */
        public function getStartLng(){
            return $this->getLegs()->start_location->lng;
        }

        /**
         * Returns the end latitude of the route
         * @return double
         */
        public function getEndLat(){
            return $this->getLegs()->end_location->lat;
        }

        /**
         * Get the end longitude of the route
         * @return double
         */
        public function getEndLng(){
            return $this->getLegs()->end_location->lng;
        }

        /**
         * @param int $index
         * The leg point at 
         * @return object
         */
        public function getStepAt(int $index){
            return $this->getSteps()[$index];
        }

        public function __toString(){
            return json_encode($this);
        }
        
    }

?>