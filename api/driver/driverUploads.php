<?php
    require("master.inc.php");

    $updateSqlStr = "";
    $values = [];

    function updateSqlStr(){
        if(count($GLOBALS['values']) > 0){
            $GLOBALS['updateSqlStr'] .= ", ";
        }
    }

    if(isset($_FILES['nid-image'])){
        $nationalIdImage = $_FILES['nid-image'];
        if(!Utility::isImage($nationalIdImage['tmp_name'])){
            exit(Response::NIIIE());
        }
        $saveName = "nid-$userId";
        $update = false;
        $lastSavedAs = "";
        if(!empty($newDriver->getNationalIdImage())){
            $update = true;
            $lastSavedAs = $newDriver->getNationalIdImage();
        }

        $nidImageName = Utility::uploadImage($nationalIdImage, $saveName, Driver::NATIONALID_PATH, $update, $lastSavedAs);

        if($nidImageName !== false){
            $updateSqlStr .= "national_id_image = ?";
            $values[] = $nidImageName;
        }
    }

    if(isset($_FILES['reg-li-image'])){
        $regularLicenseImage = $_FILES['reg-li-image'];

        if(!Utility::isImage($regularLicenseImage['tmp_name'])){
            exit(Response::RLIIE());
        }

        $saveName = "dl-$userId";
        $update = false;
        $lastSavedAs = "";
        if(!empty($newDriver->getRegLicenseImage())){
            $update = true;
            $lastSavedAs = $newDriver->getRegLicenseImage();
        }

        $dLicenseImageName = Utility::uploadImage($regularLicenseImage, $saveName, Driver::DLICENSE_PATH, $update, $lastSavedAs);

        if($dLicenseImageName !== false){
            updateSqlStr();
            $updateSqlStr .= "regular_license_image = ?";
            $values[] = $dLicenseImageName;
        }
    }

    if(isset($_FILES['psv-li-image'])){
        $psvLicense = $_FILES['psv-li-image'];

        if(!Utility::isImage($psvLicense['tmp_name'])){
            exit(Response::PSVLIIE());
        }

        $saveName = "dl-$userId";
        $update = false;
        $lastSavedAs = "";
        if(!empty($newDriver->getPsvLicenseImage())){
            $update = true;
            $lastSavedAs = $newDriver->getPsvLicenseImage();
        }

        $psvImageName = Utility::uploadImage($psvLicense, $saveName, Driver::PSVLICENSE_PATH, $update, $lastSavedAs);

        if($psvImageName !== false){
            updateSqlStr();
            $updateSqlStr .= "psv_license_image = ?";
            $values[] = $psvImageName;
        }
    }

    if(isset($_FILES['good-conduct-image'])){
        $goodConductImage = $_FILES['good-conduct-image'];

        if(!Utility::isImage($goodConductImage['tmp_name'])){
            exit(Response::PSVLIIE());
        }

        $saveName = "dl-$userId";
        $update = false;
        $lastSavedAs = "";
        
        if(!empty($newDriver->getGoodConductCertImage())){
            $update = true;
            $lastSavedAs = $newDriver->getGoodConductCertImage();
        }

        $goodConductImageName = Utility::uploadImage($goodConductImage, $saveName, Driver::GOODCONDUCT_PATH, $update, $lastSavedAs);

        if($goodConductImageName !== false){
            updateSqlStr();
            $updateSqlStr .= "good_conduct_cert_image = ?";
            $values[] = $goodConductImageName;
        }
    }

    if($updateSqlStr == ""){
        exit(Response::UEO());
    }

    $dbManager = new DbManager();
    if(!$dbManager->update(DbManager::DRIVER_DOC_TABLE, $updateSqlStr, $values, DbManager::DRIVER_DOC_ID. " = ?", [$userId])){
        exit(Response::SQE());
    }

    exit(Response::OK());
?>