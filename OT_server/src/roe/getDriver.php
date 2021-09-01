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
    exit(
        Response::makeResponse(
            "OK",
            json_encode(
                [
                    "name" => $driver->getFirstName(). " ". $driver->getLastName(),
                    "vehicle" => [
                        "model" => $driver->getVehicle()->getModel(),
                        "manufacturer" => $driver->getVehicle()->getManufacturer(),
                        "licensePlate" => $driver->getVehicle()->getLicenseNumber(),
                        "capacity" => $driver->getVehicle()->getCapacity(),
                        "color" => $driver->getVehicle()->getColor()
                    ],
                    "totalRides" => "not shown"
                ]
            )
        )
    )

?>