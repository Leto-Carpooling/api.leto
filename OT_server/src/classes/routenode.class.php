<?php

 /**
  * This class represents the node object that used in the binary tree
  */

  class RouteNode {

       /**
        * The route object represented by this route node
        * @property Route $route
        */
    public $route;
    /**
     * The angle between the startpoint of the route and the x-axis in radians
     * @property double $theta
     */
    public $theta;
    /**
     * The node to whose start point is the shortest distance from this node
     */
    public $closerTo;
    
    /**
     * distance from it's closest node in meters
     * @property double $leastDistance
     */
    public $leastDistance;

    public $left;
    public $right;
    public $level;

    /**
     * Objects are passed by reference beware
     * @param object &$object - passed by reference
     * @param mixed $compareBy - the value or object to sort the objects by
     */
    public function __construct(&$object, $theta) {
           $this->object = $object;
           $this->theta = $theta;

           $this->left = NULL;
           $this->right = NULL;
           $this->level = NULL;
           $this->closerTo = NULL;
           $this->leastDistance = -1;
    }

    /**
     * Checks if this node is closer to another node
     */
    public function isCloser(RouteNode &$node){
       $relAngle = abs($node->theta - $this->theta);

       /**
        * Using the cosine rule
        * c2 = sqrt(a2 + b2 - 2abCosC)
        */
        $a = $node->route->getTotalDistance();
        $b = $this->route->getTotalDistance();

       $distance = sqrt(
              $a * $a + $b * $b - 2 * $a * $b * cos($relAngle)
       );

       if($this->leastDistance < 0){
              $this->leastDistance = $distance;
              $this->closerTo = $node;
              return;
       }

       if($this->leastDistance > $distance){
              $this->leastDistance = $distance;
              $this->closerTo = $node;
       }
    }

    public function __toString() {

           return "$this->route";
    }
 } 
?>