<?php

    class Response{
        /**
         * Wrong Email Error
         * @property string $WEE
         */
        public static $WEE = json_encode([
            "status" => "WEE", 
            "message" => "You entered an invalid credential"
        ]);

        /**
         * SQL Error
         * @property string $SQE
         */
        public static $SQE = json_encode(
            ["status" => "SQL", 
            "message" => "An internal System error occurred"
        ]);

        /**
         * Wrong Password Error
         * @property string $WPE
         */
        public static $WPE = json_encode([
            "status" => "WPE", 
            "message" => "You entered an invalid credential"
        ]);

        /**
         * No Password Error
         * @property string $NPE
         */
        public static $NPE = json_encode([
            "status" => "NPE", 
            "message" => "Password is required"
        ]);

        /**
         * No Email Error
         * @property string $NEE
         */
        public static $NEE = json_encode([
            "status" => "NEE", 
            "message" => "Email is required"
        ]);

        /**
         * No ID Error
         * @property string $NIE
         */
        public static $NIE = json_encode([
            "status" => "NIE", 
            "message" => "It is like you are not logged in"
        ]);

        /**
         * Email Exist Error
         * @property string $EEE
         */
        public static $EEE = json_encode([
            "status" => "EEE", 
            "message" => "The email already exist"
        ]);

        /**
         * Unqualified Email Error
         * @property string $UEE
         */
        public static $UEE = json_encode([
            "status" => "UEE", 
            "message" => "The email is invalid."
        ]);

        /**
         * User Not Found Error
         * @property string $UNFE
         */
        public static $UNFE = json_encode([
            "status" => "UNFE", 
            "message" => "Could not find user with the given credential"
        ]);

        /**
         * Uknown Error Occurred
         * @property string $UEO
         */
        public static $UEO = json_encode([
            "status" => "UEO", 
            "message" => "An unknown error occurred"
        ]);

        /**
         * Change Password Token Not Found Error
         * @property string $CPTNFE
         */
        public static $CPTNFE = json_encode([
            "status" => "CPTNFE", 
            "message" => "You entered the wrong codes"
        ]);

        /**
         * Change Password Expired Token Error
         * @property string $CPETE
         */
        public static $CPETE = json_encode([
            "status" => "CPETE", 
            "message" => "The codes has expired"
        ]);

        /**
         * General OK
         * @property string $OK
         */
        public static $OK = json_encode([
            "status" => "OK", 
            "message" => "success"
        ]);

        /**
         * Already Logged In Error
         * @property string $ALIE
         */
        public static $ALIE = json_encode([
            "status" => "ALIE", 
            "message" => "You are already logged in"
        ]);

        /**
         * Not Logged In Error
         * @property string $NLIE
         */
        public static $NLIE = json_encode([
            "status" => "NLIE", 
            "message" => "You are not logged in"
        ]);



        /**
         * Make a new response
         * @param string $status - Status code
         * @param string $message - The message to send
         * @return string
         */
        public static function makeResponse($status, $message){
            return json_encode([
                "status" => $status, 
                "message" => $message
            ]);
        }

    }

?>