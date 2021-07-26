<?php
    require("master.inc.php");
    require(__DIR__. "/includes/initDriver.inc.php");

    $action = "personal";

    if(isset($_POST["action"])){
        $action = $_POST["action"];
    }

    if($action == "personal"){
        $newDriver->setNationalId($_POST["national-id"]);
        $newDriver->setRegLicense($_POST["regular-license"]);

        if(empty($newDriver->getNationalId())){
            exit(Response::NNIE());
        }

        if(empty($newDriver->getRegLicense())){
            exit(Response::NRLE());
        }

        if(!$newDriver->saveDriver()){
            exit(Response::SQE());
        }
        exit(Response::OK());
    }

    if($action == "vehicle"){
        $vehicle = new Vehicle($userId);
        $vehicle->setManufacturer($_POST['manufacturer']);
        $vehicle->setModel($_POST["model"]);
        $vehicle->setCapacity($_POST['capacity']);
        $vehicle->setColor($_POST["color"]);

        exit($vehicle->save());
    }

?>