<?php

    require("master.inc.php");
    require( __DIR__ ."/includes/initAdmin.inc.php");

    $count = 10;
    $start = 0;
    $status = "pending";

    if(isset($_POST['count'])){
        $count = (int)$_POST['count'];
    }

    if(isset($_POST['start'])){
        $start = (int)$_POST['start'];
    }

    if(isset($_POST['status']))
    {
        $s = filter_var($_POST['status'], FILTER_SANITIZE_STRING);
        switch($s){
            case "d": 
                {
                $status = "declined";
                break;
                }
            case "a":
                {
                    $status = "approved";
                    break;
                }
        }
    }

    $response = [];

    $dbManager = new DbManager();
    $dbManager->connect();
    
    //get vehicle information
    $sql = "SELECT * from ". Vehicle::VEHICLE_TABLE . " LIMIT $start, $count";
    $stmt1 = $dbManager->getDbConnection()->prepare($sql);

    //get driver information
    $sql = "SELECT * from ". Driver::DRIVER_TABLE. " WHERE ". Driver::DRIVER_ID. " = ? ";
    $stmt2 = $dbManager->getDbConnection()->prepare($sql);

    //get number of uploaded document
    $sql = "SELECT * from ". Vehicle::VEHICLE_DOC_TABLE. " WHERE ". Vehicle::VEHICLE_DOC_ID. "= ?";
    $stmt3 = $dbManager->getDbConnection()->prepare($sql);

    $sql = "SELECT * from ". Driver::DRIVER_DOC_TABLE. " WHERE ". Driver::DRIVER_DOC_ID. "= ?";
    $stmt4 = $dbManager->getDbConnection()->prepare($sql);

    //get user information
    $sql = "SELECT * from ". User::USER_TABLE. " WHERE ". User::USER_ID ."= ?";
    $stmt5 = $dbManager->getDbConnection()->prepare($sql);

    if(!$stmt1->execute()){
        exit(Response::SQE());
    }

    $allVehicles = $stmt1->fetchAll();

    if(count($allVehicles) < 1){
        exit(Response::makeResponse(
            "NRF",
            "No driver information was found"
        ));
    }
    
    class ResultHolder{
        public $vehicle = [],
               $driverInfo = [],
               $userInfo = [];

    }

    foreach($allVehicles as $vehicle){
        $resultHolder = new ResultHolder();
        $resultHolder->vehicle = $vehicle;

        if(!$stmt2->execute([$vehicle["driverId"]])){
            continue;
        }

        //get the driver info
        $resultHolder->driverInfo = $stmt2->fetch();

        if(!$stmt3->execute([$vehicle["vehicle_id"]])){
            continue;
        }

        $vehicleDoc = array_filter($stmt3->fetch(), function($value){
            return !empty($value);
        });

        if(!$stmt4->execute([$resultHolder->driverInfo["driverId"]])){
            continue;
        }

        $driverDoc = array_filter($stmt4->fetch(), function($value){
            return !empty($value);
        });

        $resultHolder->driverInfo['uploads'] = count($driverDoc) + count($vehicleDoc);

        if(!$stmt5->execute([$resultHolder->driverInfo["driverId"]])){
            continue;
        }

        $resultHolder->userInfo = $stmt5->fetch();

        $response[] = $resultHolder;
    }

    if(count($response) < 1){
        exit(Response::makeResponse(
            "NRF",
            "No driver information was found"
        ));
    }

    exit(
        Response::makeResponse(
            "OK",
            json_encode($response)
        )
     );
    

?>