<?php

    class Ride{
        private $id,
                $groupId,
                $riderId,
                $routeId,
                $completed,
                $completedOn,
                $createdOn;

        const RIDE_TABLE = "ride",
              RIDE_TABLE_ID = "`ride`.`id`",
              RIDE = "ride";

        public function __construct($rideId){
            $dbManager = new DbManager();

            $rideInfo = $dbManager->query(Ride::RIDE_TABLE, ["*"], Ride::RIDE_TABLE_ID . " = ?", [$rideId]);

            if($rideInfo == false){
                return;
            }

            $this->setId($rideId);
            $this->setGroupId($rideInfo["groupId"]);
            $this->setRouteId($rideInfo["routeId"]);
            $this->setCompleted($rideInfo["completed"] > 0);
            $this->setCompletedOn($rideInfo["completed_on"]);
            $this->setCreatedOn($rideInfo["created_on"]);
            
        }


        /**
         * Get the value of id
         */ 
        public function getId()
        {
                return $this->id;
        }

        /**
         * Set the value of id
         *
         * @return  self
         */ 
        public function setId($id)
        {
                $this->id = $id;

                return $this;
        }

        /**
         * Get the value of groupId
         */ 
        public function getGroupId()
        {
                        return $this->groupId;
        }

        /**
         * Set the value of groupId
         *
         * @return  self
         */ 
        public function setGroupId($groupId)
        {
                        $this->groupId = $groupId;

                        return $this;
        }

        /**
         * Get the value of riderId
         */ 
        public function getRiderId()
        {
                        return $this->riderId;
        }

        /**
         * Set the value of riderId
         *
         * @return  self
         */ 
        public function setRiderId($riderId)
        {
                        $this->riderId = $riderId;

                        return $this;
        }

        /**
         * Get the value of routeId
         */ 
        public function getRouteId()
        {
                        return $this->routeId;
        }

        /**
         * Set the value of routeId
         *
         * @return  self
         */ 
        public function setRouteId($routeId)
        {
                        $this->routeId = $routeId;

                        return $this;
        }

        /**
         * Get the value of completed
         */ 
        public function getCompleted()
        {
                        return $this->completed;
        }

        /**
         * Set the value of completed
         *
         * @return  self
         */ 
        public function setCompleted($completed)
        {
                        $this->completed = $completed;

                        return $this;
        }

        /**
         * Get the value of completedOn
         */ 
        public function getCompletedOn()
        {
                        return $this->completedOn;
        }

        /**
         * Set the value of completedOn
         *
         * @return  self
         */ 
        public function setCompletedOn($completedOn)
        {
                        $this->completedOn = $completedOn;

                        return $this;
        }

        /**
         * Get the value of createdOn
         */ 
        public function getCreatedOn()
        {
                        return $this->createdOn;
        }

        /**
         * Set the value of createdOn
         *
         * @return  self
         */ 
        public function setCreatedOn($createdOn)
        {
                        $this->createdOn = $createdOn;

                        return $this;
        }
    }

?>