<?php

    require("master.inc.php");

    //updates the driver locations
    use Kreait\Firebase\Factory;
    
    
    $factory = (new Factory())->withServiceAccount(__DIR__."/../../ot_server/src/includes/". LETO_FB_JSON);
    
    $factory = $factory->withDatabaseUri(LETO_NOSQL_URI);

    $firebaseDb = $factory->createDatabase();
    
    while(true){
        $dbManager = new DbManager();
        $dbManager->setFetchAll(true);
        $allDriversInfo = $dbManager->query(Driver::DRIVER_LOC_TABLE. " inner join ". Driver::DRIVER_TABLE. " on ". Driver::DRIVER_ID. " = ". Driver::DRIVER_LOC_ID, [Driver::DRIVER_LOC_TABLE.".*"], "1 and online_status > ?", [0], false);

        if($allDriversInfo === false || count($allDriversInfo) < 1){
            echo "No driver to update";
            sleep(600);//sleep for 10 minutes
            continue;
        }

        foreach($allDriversInfo as $driverInfo){
            $fbInfo = $firebaseDb->getReference("drivers/did-".$driverInfo["driverId"]."/cLocation")->getValue();

            $latitude = $fbInfo["latitude"];
            $longitude = $fbInfo["longitude"];
            $updatedAt = $fbInfo["updatedAt"];

            $lastUpdated = ($updatedAt/1000) - strtotime($driverInfo["updated_on"]);
            echo $driverInfo['driverId']." last updated on $lastUpdated";

            //ten minutes offline changes your status to offline
            if($lastUpdated > 600000){
                $dbManager->update(Driver::DRIVER_TABLE, "online_status = false", [] ,Driver::DRIVER_ID . "= ?", [$driverInfo["driverId"]]);
                continue;
            }

            Driver::updateLocation($latitude, $longitude, $driverInfo["driverId"], $updatedAt);
        }

        echo "Next check after 5 seconds";
        sleep(5);
    }

?>