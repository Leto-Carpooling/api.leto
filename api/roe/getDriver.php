<?php

    require("master.inc.php");

    $driverId = (int)$_POST["driverId"];

    if($driverId == 0){
        exit(
            Response::NIE()
        );
    }

    $driver = new Driver($driverId);
    if(!$driver){
        exit(
            Response::UEO()
        );
    }

    $vehicle = $driver->getVehicle();

    exit(
        Response::makeResponse(
            "OK",
            json_encode(
                [
                    "name" => $driver->getFirstName(). " ". $driver->getLastName(),
                    "firstName" => $driver->getFirstName(),
                    "lastName" => $driver->getLastName(),
                    "phoneNumber" => $driver->getPhone(),
                    "status" => $driver->getApprovalStatus(),
                    "email" => $driver->getEmail(),
                    "profileImage" => User::PROFILE_IMG_PATH."/".$driver->getProfileImage(),
                    "vehicle" => [
                        "model" => $vehicle->getModel(),
                        "manufacturer" => $vehicle->getManufacturer(),
                        "licensePlate" => $vehicle->getLicenseNumber(),
                        "capacity" => $vehicle->getCapacity(),
                        "color" => $vehicle->getColor()
                    ],
                    "totalRides" => $driver->getTotalRides()
                ]
            )
        )
    )

?>