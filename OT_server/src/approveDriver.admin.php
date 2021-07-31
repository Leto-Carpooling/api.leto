<?php
    require("master.inc.php");
    require( __DIR__ ."/includes/initAdmin.inc.php");

    $driverId = isset($_POST['id'])?(int)$_POST['id']:0;

    if($driverId == 0){
        exit(Response::NIE());
    }

    $action = isset($_POST['action'])?$_POST['action']: "p";

    switch($action){
        case "p":
            {
                if(!$admin->pendDriver($driverId)){
                    exit(Response::SQE());
                }
                exit(Response::OK());
            }
        case "a":
            {
                if(!$admin->approveDriver($driverId)){
                    exit(Response::SQE());
                }
                exit(Response::OK());
            }
        case "d":
            {
                if(!$admin->declineDriver($driverId)){
                    exit(Response::SQE());
                }
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