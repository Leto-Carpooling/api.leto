<?php

  class RouteBinaryGraph{
       
    public $rootRoute,
           $routeArray;
    /**
     * 
     */
	public function __construct() {
        $this->rootRoute = null;
        $this->routeArray = [];
	}

    /**
     * Pushing into the graph the routes
     */
    public function insert(Route $r){
        $node = new Node($r);

        if($this->rootRoute == null){
            $this->rootRoute = $node;
            return;
        }

        $current = $this->rootRoute;

        while(true){
            $distance = $this->distBtwStartLoc($r, $current->object);
            if($current->leftEdge == -1){
                $current->left = $node;
                $current->leftEdge = $distance;
                break;
            }

            if($current->leftEdge > $distance){
                
            }
        }

    }

    /**
     * Finds the angle in radians between the x-axis and a route
     */
    public function findTheta(Route $route){
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
                Utility::magnitude([0, 0], [$x1, $y1]) * Utility::magnitude([0, 0], [$y2, $x2])
            )
        );

        return $theta;
    }

    /**
     * Computes the distance between the starts of two routes
     */
    public function distBtwStartLoc(Route $r1, Route $r2){
        $x1 = $r1->getStartLng();
        $y1 = $r1->getStartLat();
        $x2 = $r2->getStartLng();
        $y2 = $r2->getStartLat();

        //get the relative angle
        $theta1 = $this->findTheta($r1);
        $theta2 = $this->findTheta($r2);

        $relTheta = abs($theta1 - $theta2);


        $a = Utility::magnitude([0, 0], [$x1, $y1]);
        $b = Utility::magnitude([0,0], [$x2, $y2]);

        return sqrt(
                $a * $a + $b * $b - 2 * $a * $b * cos($relTheta)
        );
    }

    
}
    

?>