<?php
    require_once(__DIR__."/passwords.inc.php");
    use Twilio\Rest\Client;

    

    function sendSms($phone_number, $msg){
        $sid = T_SID;
        $token = T_AUTH_TOKEN;
        $twilio = new Client($sid, $token);

        $twilio->messages
        ->create($phone_number, 
                [
                    "body" => $msg, 
                    "from" => T_NUMBER
                ]
        );
        return true;
    }
    

?>