<?php
    require("master.inc.php");

    $driverId = isset($_POST['id'])?(int)$_POST['id']:0;

    if($driverId == 0){
        exit(Response::NIE());
    }

    $action = isset($_POST['action'])?$_POST['action']: "p";

    switch($action){
        case "e":
            {
                if(!$admin->pendDriver($driverId)){
                    exit(Response::SQE());
                }
                exit(Response::OK());
            }
        case "d":
            {
                if(!$admin->approveDriver($driverId)){
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