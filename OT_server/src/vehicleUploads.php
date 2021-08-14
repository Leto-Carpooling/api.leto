<?php

    require("master.inc.php");
    require(__DIR__."/includes/initDriver.inc.php");

    $updateSqlStr= "";
    $values = [];

    function updateSqlStr(){
        if(count($GLOBALS['values']) > 0){
            $GLOBALS['updateSqlStr'] .= ", ";
        }
    }

    $vehicle = $newDriver->getVehicle();
    if(empty($vehicle->getId())){
        exit(Response::VNIE());
    }

    if(isset($_FILES['v-ins-image'])){
        $insuranceImage = $_FILES['v-ins-image']; //insurance report image

        $lastSavedAs = "";
        $update = false;

        if(!Utility::isImage($insuranceImage['tmp_name'])){
            exit(Response::VIIIE());
        }

        if(!empty($vehicle->getInsuranceImage())){
            $update = true;
            $lastSavedAs = $vehicle->getInsuranceImage();
        }

        $saveName = "ins-". $vehicle->getId();
        $insuranceImageName = Utility::uploadImage($insuranceImage, $saveName, Vehicle::INSURANCE_PATH, $update, $lastSavedAs);

        if($insuranceImageName !== false){
            updateSqlStr();
            $updateSqlStr .= "v_insurance_image = ?";
            $values[] = $insuranceImageName;
        }
        
    }

    if(isset($_FILES['v-reg-image'])){  //vehicle registration
        $regImage = $_FILES['v-reg-image'];

        $lastSavedAs = "";
        $update = false;

        if(!Utility::isImage($regImage['tmp_name'])){
            exit(Response::VRIIE());
        }

        if(!empty($vehicle->getRegistrationImage())){
            $update = true;
            $lastSavedAs = $vehicle->getRegistrationImage();
        }

        $saveName = "reg-". $vehicle->getId();
        $regImageName = Utility::uploadImage($regImage, $saveName, Vehicle::REGISTRATION_PATH, $update, $lastSavedAs);

        if($regImageName !== false){
            updateSqlStr();
            $updateSqlStr .= "v_registration_image = ?";
            $values[] = $regImageName;
        }
        
    }

    if(isset($_FILES['v-ir-image'])){ //vehicle inspection report image
        $inspReportImage = $_FILES['v-ir-image'];

        $lastSavedAs = "";
        $update = false;

        if(!Utility::isImage($inspReportImage['tmp_name'])){
            exit(Response::VIRIIE());
        }

        if(!empty($vehicle->getInspReportImage())){
            $update = true;
            $lastSavedAs = $vehicle->getInspReportImage();
        }

        $saveName = "ir-". $vehicle->getId();
        $inspReportImageName = Utility::uploadImage($inspReportImage, $saveName, Vehicle::IREPORT_PATH, $update, $lastSavedAs);

        if($inspReportImageName !== false){
            updateSqlStr();
            $updateSqlStr .= "v_inspection_report_image = ?";
            $values[] = $inspReportImageName;
        }
        
    }


    if($updateSqlStr == ""){
        exit(Response::UEO());
    }

    $dbManager = new DbManager();
    if(!$dbManager->update(DbManager::VEHICLE_DOC_TABLE, $updateSqlStr, $values, DbManager::VEHICLE_DOC_ID. " = ?", [$vehicle->getId()])){
        exit(Response::SQE());
    }

    exit(Response::OK());
?>
