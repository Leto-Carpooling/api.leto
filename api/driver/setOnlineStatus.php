<?php
    require("master.inc.php");

    $online = ($_POST["online"])?(int)$_POST["online"]:exit(
        Response::UEO()
    );

    $newDriver->setOnline($online > 0);
    exit(
            Response::makeResponse(
                "OK",
                json_encode
                (
                ["onlineStatus" => $newDriver->isOnline()]
                )
            )
        );

?>