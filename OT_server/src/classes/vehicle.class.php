<?php


class Vehicle{

    private $id,
            $driverId,
            $manufacturer,
            $model,
            $licenseNumber,
            $capacity,
            $color,
            $updatedOn,
            $insuranceImage = "pending",
            $registrationImage = "pending",
            $inspReportImage = "pending",
            $documentUpdatedOn;

    public function __construct($driver_id = 0){

        if($driver_id == 0){
            return;
        }

        $this->loadVehicle($driver_id);
    }

    public function loadVehicle($driver_id){
        $this->driverId = $driver_id;
        $dbManager = new DbManager();
        $vInfo = $dbManager->query(DbManager::VEHICLE_TABLE, ["*"], "driverId = ?", [$this->driverId]);

        if($vInfo === false){
            return false;
        }

        $this->setId($vInfo[DbManager::VEHICLE_ID]);
        $this->setManufacturer($vInfo["manufacturer"]);
        $this->setModel($vInfo["model"]);
        $this->setCapacity($vInfo["capacity"]);
        $this->setLicenseNumber($vInfo["license_plate"]);
        $this->setColor($vInfo["vehicle_color"]);
        $this->setUpdatedOn($vInfo["updated_on"]);

        $vDoc = $dbManager->query(DbManager::VEHICLE_DOC_TABLE, ["*"], DbManager::DRIVER_DOC_ID. " = ?", [$this->id]);

        if($vDoc === false){
            return false;
        }

        $this->setRegistrationImage($vDoc["v_registration_image"]);
        $this->setInsuranceImage($vDoc["v_insurance_image"]);
        $this->setInspReportImage($vDoc["v_inspection_report_image"]);
        $this->setDocumentUpdatedOn($vDoc["updated_on"]);
        
        return true;
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
     * Get the value of driverId
     */ 
    public function getDriverId()
    {
                return $this->driverId;
    }

    /**
     * Set the value of driverId
     *
     * @return  self
     */ 
    public function setDriverId($driverId)
    {
                $this->driverId = $driverId;

                return $this;
    }

    /**
     * Get the value of manufacturer
     */ 
    public function getManufacturer()
    {
                return $this->manufacturer;
    }

    /**
     * Set the value of manufacturer
     *
     * @return  self
     */ 
    public function setManufacturer($manufacturer)
    {
                $this->manufacturer = $manufacturer;

                return $this;
    }

    /**
     * Get the value of model
     */ 
    public function getModel()
    {
                return $this->model;
    }

    /**
     * Set the value of model
     *
     * @return  self
     */ 
    public function setModel($model)
    {
                $this->model = $model;

                return $this;
    }

    /**
     * Get the value of licenseNumber
     */ 
    public function getLicenseNumber()
    {
                return $this->licenseNumber;
    }

    /**
     * Set the value of licenseNumber
     *
     * @return  self
     */ 
    public function setLicenseNumber($licenseNumber)
    {
                $this->licenseNumber = $licenseNumber;

                return $this;
    }

    /**
     * Get the value of capacity
     */ 
    public function getCapacity()
    {
                return $this->capacity;
    }

    /**
     * Set the value of capacity
     *
     * @return  self
     */ 
    public function setCapacity($capacity)
    {
                $this->capacity = $capacity;

                return $this;
    }

    /**
     * Get the value of color
     */ 
    public function getColor()
    {
                return $this->color;
    }

    /**
     * Set the value of color
     *
     * @return  self
     */ 
    public function setColor($color)
    {
                $this->color = $color;

                return $this;
    }

    /**
     * Get the value of updatedOn
     */ 
    public function getUpdatedOn()
    {
                return $this->updatedOn;
    }

    /**
     * Set the value of updatedOn
     *
     * @return  self
     */ 
    public function setUpdatedOn($updatedOn)
    {
                $this->updatedOn = $updatedOn;

                return $this;
    }

    /**
     * Get the value of insuranceImage
     */ 
    public function getInsuranceImage()
    {
                return $this->insuranceImage;
    }

    /**
     * Set the value of insuranceImage
     *
     * @return  self
     */ 
    public function setInsuranceImage($insuranceImage)
    {
                $this->insuranceImage = $insuranceImage;

                return $this;
    }

    /**
     * Get the value of registrationImage
     */ 
    public function getRegistrationImage()
    {
                return $this->registrationImage;
    }

    /**
     * Set the value of registrationImage
     *
     * @return  self
     */ 
    public function setRegistrationImage($registrationImage)
    {
                $this->registrationImage = $registrationImage;

                return $this;
    }

    /**
     * Get the value of inspReportImage
     */ 
    public function getInspReportImage()
    {
                return $this->inspReportImage;
    }

    /**
     * Set the value of inspReportImage
     *
     * @return  self
     */ 
    public function setInspReportImage($inspReportImage)
    {
                $this->inspReportImage = $inspReportImage;

                return $this;
    }

    /**
     * Get the value of documentUpdatedOn
     */ 
    public function getDocumentUpdatedOn()
    {
                return $this->documentUpdatedOn;
    }

    /**
     * Set the value of documentUpdatedOn
     *
     * @return  self
     */ 
    public function setDocumentUpdatedOn($documentUpdatedOn)
    {
                $this->documentUpdatedOn = $documentUpdatedOn;

                return $this;
    }
}

?>