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

    const   INSURANCE_PATH = "driver/vehicle/insurances",
            REGISTRATION_PATH = "driver/vehicle/registrations",
            IREPORT_PATH = "driver/vehicle/inspection_reports";

    const   VEHICLE_TABLE = "vehicle",
            VEHICLE_ID = "`vehicle`.`vehicle_id`",
            VEHICLE_DOC_TABLE = "vehicle_document",
            VEHICLE_DOC_ID = "`vehicle_document`.`vehicleId`";


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

        $this->setId($vInfo["vehicle_id"]);
        $this->setManufacturer($vInfo["manufacturer"]);
        $this->setModel($vInfo["model"]);
        $this->setCapacity($vInfo["capacity"]);
        $this->setLicenseNumber($vInfo["license_plate"]);
        $this->setColor($vInfo["vehicle_color"]);
        $this->setUpdatedOn($vInfo["updated_on"]);

        $vDoc = $dbManager->query(DbManager::VEHICLE_DOC_TABLE, ["*"], "vehicleId = ?", [$this->id]);

        if($vDoc === false){
            return false;
        }

        $this->setRegistrationImage($vDoc["v_registration_image"]);
        $this->setInsuranceImage($vDoc["v_insurance_image"]);
        $this->setInspReportImage($vDoc["v_inspection_report_image"]);
        $this->setDocumentUpdatedOn($vDoc["updated_on"]);
        
        return true;
    }

    public function save(){
        if(empty($this->manufacturer)){
            return Response::NVMAE();
        }

        if(empty($this->model)){
            return Response::NVME();
        }

        if(empty($this->capacity) || !is_int($this->capacity) || $this->capacity < 2){
            return Response::UVCE();
        }

        if(empty($this->licenseNumber)){
            return Response::NVLE();
        }

        if(empty($this->color)){
            return Response::NVCE();
        }

        $updateStr = "manufacturer = ?, model = ?, license_plate = ?, vehicle_color = ?, capacity = ?";
        if((empty($this->id) && !$this->addVehicle()) ||
            !$this->updateVehicle($updateStr, [$this->manufacturer, $this->model, $this->licenseNumber, $this->color, $this->capacity])){
            return Response::SQE();
        }

        return Response::OK();
    }

    private function addVehicle(){

        $dbManager = new DbManager();
        $vehicleId = $dbManager->insert(DbManager::VEHICLE_TABLE, ["driverId", "manufacturer", "model", "capacity", "license_plate", "vehicle_color"], [$this->driverId, $this->manufacturer, $this->model, $this->capacity, $this->licenseNumber, $this->color]);
        
        if($vehicleId == -1){
            return false;
        }

        $this->id = $vehicleId;

        $vDocId = $dbManager->insert(DbManager::VEHICLE_DOC_TABLE, [DbManager::VEHICLE_DOC_ID], [$this->id]);

        if($vDocId == -1){
            return false;
        }

        return true;
    }

    /**
     * @param string $updateSqlStr - The sql string
     * @param array $values - The values array
     */
    public function updateVehicle($updateSqlStr, array $values){
        $dbManager = new DbManager();
        return $dbManager
                ->update(DbManager::VEHICLE_TABLE, $updateSqlStr, $values, DbManager::VEHICLE_ID . " = ?", [$this->id]);
    }

    /**
     * Can this vehicle be approved?
     * @return bool
     */
    public function isApprovable(){
        if(empty($this->id) ||
           empty($this->registrationImage) ||
           empty($this->inspReportImage) ||
           empty($this->insuranceImage)){
               return false;
           }
        
        return true;
    }


    /**
     * Loads new vehicle information from the database.
     */
    public function refresh(){
        $this->loadVehicle($this->id);
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
                $this->capacity = (int)$capacity;

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