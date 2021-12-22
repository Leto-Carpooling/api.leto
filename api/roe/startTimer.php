<?php

    require("master.inc.php");

    $groupId = (int)$_POST["groupId"];

    if($groupId == 0){
        exit(
            Response::NIE()
        );
    }
    
    $cmd = "php runTimer.php $groupId > timer-$groupId.txt 2>&1  &";
    exec($cmd);

    // if ( substr(php_uname(), 0, 7) == "Windows" )
    // {
    //     //windows
    //     pclose(popen("start /B $cmd", "r"));
    // }
    // else
    // {
    //     //linux
    //     shell_exec($cmd);
    // }

    exit(
        Response::OK()
    );

?>