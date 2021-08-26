<?php
    require("master.inc.php");

    require(__DIR__."/../includes/phpmailer.inc.php");

    $driverId = isset($_POST['id'])?(int)$_POST['id']:0;

    if($driverId == 0){
        exit(Response::NIE());
    }

    $action = isset($_POST['action'])?$_POST['action']: "p";
    $driver = new Rider($driverId); //just for the basic info

    switch($action){
        case "p":
            {
                if(!$admin->pendDriver($driverId)){
                    exit(Response::SQE());
                }
                sendEmail(
                    $driver->getFirstName(). " ". $driver->getLastName(),
                    $driver->getEmail(),
                    "Leto driver application status",
                    "Your application to become a Leto driver is currently pending approval"
                );
                exit(Response::OK());
            }
        case "a":
            {
                if(!$admin->approveDriver($driverId)){
                    exit(Response::SQE());
                }
                sendEmail(
                    $driver->getFirstName(). " ". $driver->getLastName(),
                    $driver->getEmail(),
                    "Leto driver application approval",
                    "Your application to become a Leto driver has been approved. You are now a Leto driver."
                );
                exit(Response::OK());
            }
        case "d":
            {
                if(!$admin->declineDriver($driverId)){
                    exit(Response::SQE());
                }
                sendEmail(
                    $driver->getFirstName(). " ". $driver->getLastName(),
                    $driver->getEmail(),
                    "Leto driver application was declined",
                    "Your application to become a Leto driver has been declined. Please ensure that you uploaded the right authentic documents"
                );
                exit(Response::OK());
            }
        default:
        {
            exit(
                Response::makeResponse(
                "NAE",
                "No action was specified"
            ));
        }
    }


?>