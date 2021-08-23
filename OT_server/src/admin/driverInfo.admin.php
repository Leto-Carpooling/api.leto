<?php
    require("master.inc.php");

    $driverId = (isset($_POST['id']))?(int)$_POST['id']: 0;

    if($driverId == 0){
        exit(Response::NIE());
    }

    $driver = new Driver($driverId);

    $response = [];

    if(!empty($driver->getNationalIdImage())){
        $response["nationalId"] = json_encode(
            [
                "name" => "National Id",
                "path" => "storage/". Driver::NATIONALID_PATH . "/".  $driver->getNationalIdImage()
            ]
        );
    }

    if(!empty($driver->getRegLicenseImage())){
        $response["drivingLicense"] = json_encode(
            [
                "name" => "Driving License",
                "path" => "storage/". Driver::DLICENSE_PATH . "/". $driver->getRegLicenseImage()
            ]
        );
    }

    if(!empty($driver->getPsvLicenseImage())){
        $response["psvLicense"] = json_encode(
            [
                "name" => "PSV License",
                "path" => "storage/". Driver::PSVLICENSE_PATH ."/". $driver->getPsvLicenseImage()
            ]
        );
    }

    if(!empty($driver->getGoodConductCertImage())){
        $response["goodCondCert"] = json_encode(
            [
                "name" => "Good Conduct",
                "path" => "storage/". Driver::GOODCONDUCT_PATH ."/". $driver->getGoodConductCertImage()
            ]
        );
    }

    if(!empty($driver->getVehicle()->getInsuranceImage())){
        $response["vehicleInsurance"] = json_encode(
            [
                "name" => "Vehicle Insurance",
                "path" => "storage/". Vehicle::INSURANCE_PATH. "/". $driver->getVehicle()->getInsuranceImage()
            ]
        );
    }

    if(!empty($driver->getVehicle()->getRegistrationImage())){
        $response["vehicleRegistration"] = json_encode(
            [
                "name" => "Vehicle Registration",
                "path" => "storage/". Vehicle::REGISTRATION_PATH. "/". $driver->getVehicle()->getRegistrationImage()
            ]
        );
    }

    if(!empty($driver->getVehicle()->getInspReportImage())){
        $response["vehicleRegistration"] = json_encode(
            [
                "name" => "Vehicle Inspection Report",
                "path" => "storage/".Vehicle::IREPORT_PATH ."/". $driver->getVehicle()->getInspReportImage()
            ]
        );
    }

    exit(
      Response::makeResponse(
          "OK",
          json_encode($response)
      )  
    );

?>