<?php
    require("master.inc.php");

    //$newDriver is already initialized in the master.inc.php

    exit(
        Response::makeResponse(
            "OK",
            $newDriver->getApprovalStatus()
        )
    );

?>