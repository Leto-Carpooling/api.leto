<?php
    require("master.inc.php");

    $driverId = (isset($_POST['id']))?(int)$_POST['id']: 0;

    if($driverId == 0){
        exit(Response::NIE());
    }

    $driver = new Driver($driverId);

    $response = [];

    if(!empty($driver->getNationalIdImage())){
        $response[] = json_encode(
            [
                "name" => "National ID",
                "path" => "storage/". Driver::NATIONALID_PATH . "/".  $driver->getNationalIdImage()
            ]
        );
    }

    if(!empty($driver->getRegLicenseImage())){
        $response[] = json_encode(
            [
                "name" => "Driving License",
                "path" => "storage/". Driver::DLICENSE_PATH . "/". $driver->getRegLicenseImage()
            ]
        );
    }

    if(!empty($driver->getPsvLicenseImage())){
        $response[] = json_encode(
            [
                "name" => "PSV License",
                "path" => "storage/". Driver::PSVLICENSE_PATH ."/". $driver->getPsvLicenseImage()
            ]
        );
    }

    if(!empty($driver->getGoodConductCertImage())){
        $response[] = json_encode(
            [
                "name" => "Certificate of Good Conduct",
                "path" => "storage/". Driver::GOODCONDUCT_PATH ."/". $driver->getGoodConductCertImage()
            ]
        );
    }

    if(!empty($driver->getVehicle()->getInsuranceImage())){
        $response[] = json_encode(
            [
                "name" => "Vehicle Insurance",
                "path" => "storage/". Vehicle::INSURANCE_PATH. "/". $driver->getVehicle()->getInsuranceImage()
            ]
        );
    }

    if(!empty($driver->getVehicle()->getRegistrationImage())){
        $response[] = json_encode(
            [
                "name" => "Vehicle Registration(Logbook)",
                "path" => "storage/". Vehicle::REGISTRATION_PATH. "/". $driver->getVehicle()->getRegistrationImage()
            ]
        );
    }

    if(!empty($driver->getVehicle()->getInspReportImage())){
        $response[] = json_encode(
            [
                "name" => "NTSA Vehicle Inspection Report",
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