<?php

 class Driver extends User{
    private $nationalId,
            $regLicense,
            $nationalIdImage = "pending",
            $regLicenseImage = "pending",
            $psvLicenseImage = "pending",
            $goodConductCertImage = "pending",
            $approvalStatus = "pending",
            $updated,
            $vehicle;

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
        $driverInfo = $dbManager->query(DbManager::DRIVER_INFO_TABLE, ["*"], DbManager::DRIVER_INFO_ID + " = ?", [$id]);

        if($driverInfo === false){
            return false;
        }

        $this->setNationalId($driverInfo["national_id"]);
        $this->setRegLicense($driverInfo['regular_license']);
        $this->setApprovalStatus($driverInfo["approval_status"]);
        $this->setUpdated($driverInfo["updated_on"]);

        $driverDoc = $dbManager->query(DbManager::DRIVER_DOC_TABLE, ["*"], DbManager::DRIVER_DOC_ID + " = ?", [$id]);

        if($driverDoc === false){
            return false;
        }

        $this->setNationalIdImage($driverDoc["national_id_image"]);
        $this->setRegLicenseImage($driverDoc["regular_license_image"]);
        $this->setPsvLicenseImage($driverDoc["psv_license_image"]);
        $this->setGoodConductCertImage($driverDoc["good_conduct_cert_image"]);

        $vehicle = new Vehicle();
        if($vehicle->loadVehicle($this->id) === false){
            return false;
        }

        $this->setVehicle($vehicle);
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
 }

?>