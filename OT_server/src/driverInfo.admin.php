<?php
    require("master.inc.php");
    require( __DIR__ ."/includes/initAdmin.inc.php");

    $driverId = (isset($_GET['id']))?(int)$_GET['id']: 0;

    if($driverId == 0){
        exit(Response::NIE());
    }

    $driver = new Driver($driverId);

    $response = [
        "national_id" => "storage/". Driver::NATIONALID_PATH . "/".  $driver->getNationalIdImage(),
        "driving_license" => "storage/". Driver::DLICENSE_PATH . "/". $driver->getRegLicenseImage(),
        "psv_license" => "storage/". Driver::PSVLICENSE_PATH ."/". $driver->getPsvLicenseImage(),
        "good_cond_cert" => "storage/". Driver::GOODCONDUCT_PATH ."/". $driver->getGoodConductCertImage(),
        "vehicle_insurance" => "storage/". Vehicle::INSURANCE_PATH. "/". $driver->getVehicle()->getInsuranceImage(),
        "vehicle_registration" => "storage/". Vehicle::REGISTRATION_PATH. "/". $driver->getVehicle()->getRegistrationImage(),
        "vehicle_inspection" => "storage/".Vehicle::IREPORT_PATH ."/". $driver->getVehicle()->getInspReportImage()
    ];

    exit(
      Response::makeResponse(
          "OK",
          json_encode($response)
      )  
    );

?>