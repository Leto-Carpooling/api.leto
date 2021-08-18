<?php
    require("master.inc.php");

    $driverId = (isset($_GET['id']))?(int)$_GET['id']: 0;

    if($driverId == 0){
        exit(Response::NIE());
    }

    $driver = new Driver($driverId);

    $response = [
        "nationalId" => "storage/". Driver::NATIONALID_PATH . "/".  $driver->getNationalIdImage(),
        "drivingLicense" => "storage/". Driver::DLICENSE_PATH . "/". $driver->getRegLicenseImage(),
        "psvLicense" => "storage/". Driver::PSVLICENSE_PATH ."/". $driver->getPsvLicenseImage(),
        "goodCondCert" => "storage/". Driver::GOODCONDUCT_PATH ."/". $driver->getGoodConductCertImage(),
        "vehicleInsurance" => "storage/". Vehicle::INSURANCE_PATH. "/". $driver->getVehicle()->getInsuranceImage(),
        "vehicleRegistration" => "storage/". Vehicle::REGISTRATION_PATH. "/". $driver->getVehicle()->getRegistrationImage(),
        "vehicleInspection" => "storage/".Vehicle::IREPORT_PATH ."/". $driver->getVehicle()->getInspReportImage()
    ];

    exit(
      Response::makeResponse(
          "OK",
          json_encode($response)
      )  
    );

?>