<?php
    require("master.inc.php");

    //$newDriver is already initialized in the master.inc.php

    $approved = $newDriver->getApprovalStatus() == "approved";

    if(!$approved){
        exit(
            Response::makeResponse(
                "NA",
                "Your application has not yet been approved"
            )
        );
    }

    exit(
        Response::makeResponse(
            "OK",
            "You are now a driver on Leto."
        )
    );

?>