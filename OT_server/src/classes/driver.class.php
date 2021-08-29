<?php

 class Driver extends Rider{
    protected $nationalId,
            $regLicense,
            $driverState,
            $nationalIdImage = "pending",
            $regLicenseImage = "pending",
            $psvLicenseImage = "pending",
            $goodConductCertImage = "pending",
            $approvalStatus = "pending",
            $updated,
            $updatedDocumentOn,
            $vehicle;

            /**
             * Image paths
             */
    const NATIONALID_PATH = "driver/national_ids/national_id_images",
          DLICENSE_PATH = "driver/licenses/regular",
          PSVLICENSE_PATH = "driver/licenses/psv",
          GOODCONDUCT_PATH = "driver/good_conduct";

    const OPEN = 0,
          ENROUTE = 1,
          ONTRIP = 2;
    
    /**
     * Database tables
     */
    const DRIVER_TABLE = "driver_information",
          DRIVER_ID = "`driver_information`.`driverId`",
          DRIVER_DOC_TABLE = "driver_document",
          DRIVER_DOC_ID = "`driver_document`.`driverId`";

    public function __construct($id = 0){
        parent::__construct($id);

        if($id == 0){
            return;
        }

        $this->loadDriver($id);
    }

    public function loadDriver($id){
        if(!$this->loadUser($id)){
            return false;
        }

        $dbManager = new DbManager();
        $driverInfo = $dbManager->query(DbManager::DRIVER_INFO_TABLE, ["*"], DbManager::DRIVER_INFO_ID." = ?", [$id]);

        if($driverInfo === false){
            return false;
        }

        $this->setNationalId($driverInfo["national_id"]);
        $this->setRegLicense($driverInfo['regular_license']);
        $this->setApprovalStatus($driverInfo["approval_status"]);
        $this->setUpdated($driverInfo["updated_on"]);

        $driverDoc = $dbManager->query(DbManager::DRIVER_DOC_TABLE, ["*"], DbManager::DRIVER_DOC_ID . " = ?", [$id]);

        if($driverDoc === false){
            return false;
        }

        $this->setDriverState($driverDoc["driver_state"]);
        $this->setNationalIdImage($driverDoc["national_id_image"]);
        $this->setRegLicenseImage($driverDoc["regular_license_image"]);
        $this->setPsvLicenseImage($driverDoc["psv_license_image"]);
        $this->setGoodConductCertImage($driverDoc["good_conduct_cert_image"]);
        $this->setUpdatedDocumentOn($driverDoc["updated_on"]);

        $vehicle = new Vehicle();
        if($vehicle->loadVehicle($this->id) === false){
            return false;
        }

        $this->setVehicle($vehicle);
    }

    /**
     * Save or updates a driver national Id and registration license
     */

    public function saveDriver(){
        //this is a new driver
        if(empty($this->updated)){
            return $this->addDriver();
        }

        return $this->updateDriver();
    }

    /**
     * Adds a new driver
     */
    protected function addDriver(){
        $dbManager = new DbManager();
        if($dbManager->insert(DbManager::DRIVER_INFO_TABLE, 
        [DbManager::DRIVER_INFO_ID, "national_id", "regular_license", "approval_status"], 
        [$this->id, $this->nationalId, $this->regLicense, "pending"]) == -1){
            return false;
        }

        if($dbManager->insert(DbManager::DRIVER_DOC_TABLE, 
                             [DbManager::DRIVER_DOC_ID], 
                             [$this->id]) == -1){
            $dbManager->delete(DbManager::DRIVER_INFO_TABLE, DbManager::DRIVER_INFO_ID ." = ?", 
            [$this->id]);
            return false;
        }

        if(!$dbManager->update(DbManager::USER_TABLE, "user_type = ?", ["driver"], DbManager::USER_ID ." = ?", [$this->id])){
            return false;
        }

        return true;
    }

    public function updateDriver(){
        $dbManager = new DbManager();
        return 
            $dbManager->update(
            DbManager::DRIVER_INFO_TABLE, 
            "national_id = ?, regular_license = ?", 
            [$this->nationalId, $this->regLicense],
            DbManager::DRIVER_INFO_ID ." = ?",
            [$this->id]);
    }


    /**
     * Can this driver be approved?
     * @return bool
     */
    public function isApprovable(){
        if(!$this->canUpgrade() ||
           empty($this->nationalIdImage) ||
           empty($this->regLicenseImage) ||
           empty($this->psvLicenseImage) ||
           empty($this->goodConductCertImage) ||
           empty($this->vehicle) ||
           !$this->vehicle->isApprovable() ){
            return false;
        }
        return true;
    }

    public function approve(){
        if(empty($this->id)){
            return false;
        }

        return $this->changeApprovalStatus("approved");
        
    }

    public function pend(){
        if(empty($this->id)){
            return false;
        }

        return $this->changeApprovalStatus("pending");
        
    }

    public function decline(){
        if(empty($this->id)){
            return false;
        }

        return $this->changeApprovalStatus("declined");
    }

    protected function changeApprovalStatus($status){
        if(!in_array($status, ["declined", "pending", "approved"])){
            $status = "pending";
        }
        $this->approvalStatus = $status;
        $dbManager = new DbManager();
        return $dbManager->update(DbManager::DRIVER_INFO_TABLE, "approval_status = ? ", [$status], DbManager::DRIVER_INFO_ID . "= ?", [$this->id]);
    }

    /**
     * loads the updated driver information from the database;
     */
    public function refresh(){
        $this->loadDriver($this->id);
    }

    /**
     * Get the value of nationalId
    */ 
    public function getNationalId()
    {
        return $this->nationalId;
    }

    /**
     * Set the value of nationalId
    *
    * @return  self
    */ 
    public function setNationalId($nationalId)
    {
        $this->nationalId = $nationalId;

        return $this;
    }

    /**
     * Get the value of regLicense
    */ 
    public function getRegLicense()
    {
                return $this->regLicense;
    }

    /**
     * Set the value of regLicense
    *
    * @return  self
    */ 
    public function setRegLicense($regLicense)
    {
                $this->regLicense = $regLicense;

                return $this;
    }

    /**
     * Get the value of psvLicenseImage
    */ 
    public function getPsvLicenseImage()
    {
                return $this->psvLicenseImage;
    }

    /**
     * Set the value of psvLicenseImage
    *
    * @return  self
    */ 
    public function setPsvLicenseImage($psvLicenseImage)
    {
                $this->psvLicenseImage = $psvLicenseImage;

                return $this;
    }

    /**
     * Get the value of goodConductCertImage
    */ 
    public function getGoodConductCertImage()
    {
                return $this->goodConductCertImage;
    }

    /**
     * Set the value of goodConductCertImage
    *
    * @return  self
    */ 
    public function setGoodConductCertImage($goodConductCertImage)
    {
                $this->goodConductCertImage = $goodConductCertImage;

                return $this;
    }

    /**
     * Get the value of regLicenseImage
    */ 
    public function getRegLicenseImage()
    {
                return $this->regLicenseImage;
    }

    /**
     * Set the value of regLicenseImage
    *
    * @return  self
    */ 
    public function setRegLicenseImage($regLicenseImage)
    {
                $this->regLicenseImage = $regLicenseImage;

                return $this;
    }

    /**
     * Get the value of vehicle
     * @return Vehicle
    */ 
    public function getVehicle()
    {
                return $this->vehicle;
    }

    /**
     * Set the value of vehicle
    *
    * @return  self
    */ 
    public function setVehicle($vehicle)
    {
                $this->vehicle = $vehicle;

                return $this;
    }

    /**
     * Get the value of approvalStatus
     */ 
    public function getApprovalStatus()
    {
                return $this->approvalStatus;
    }

    /**
     * Set the value of approvalStatus
     *
     * @return  self
     */ 
    public function setApprovalStatus($approvalStatus)
    {
                $this->approvalStatus = $approvalStatus;

                return $this;
    }

    /**
     * Get the value of updated
     */ 
    public function getUpdated()
    {
                return $this->updated;
    }

    /**
     * Set the value of updated
     *
     * @return  self
     */ 
    public function setUpdated($updated)
    {
                $this->updated = $updated;

                return $this;
    }

    /**
     * Get the value of nationalIdImage
     */ 
    public function getNationalIdImage()
    {
                return $this->nationalIdImage;
    }

    /**
     * Set the value of nationalIdImage
     *
     * @return  self
     */ 
    public function setNationalIdImage($nationalIdImage)
    {
                $this->nationalIdImage = $nationalIdImage;

                return $this;
    }

    /**
     * Get the value of updatedDocumentOn
     */ 
    public function getUpdatedDocumentOn()
    {
                return $this->updatedDocumentOn;
    }

    /**
     * Set the value of updatedDocumentOn
     *
     * @return  self
     */ 
    public function setUpdatedDocumentOn($updatedDocumentOn)
    {
                $this->updatedDocumentOn = $updatedDocumentOn;

                return $this;
    }

    /**
     * Get the value of driverState
     */ 
    public function getDriverState()
    {
                return $this->driverState;
    }

    /**
     * Set the value of driverState
     * This should be `Driver::OPEN`, `Driver::ENROUTE` or `Driver::ONTRIP`
     * @return  self
     */ 
    public function setDriverState($driverState)
    {
                $dbManager = new DbManager();
                if(!empty($this->id) && $dbManager->update(Driver::DRIVER_TABLE, "driver_state = ?", [$driverState], Driver::DRIVER_ID ." = ?", [$this->id])){
                    $this->driverState = $driverState;
                    return true;
                }

                return false;
    }
 }

?>