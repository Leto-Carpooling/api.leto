<?php

    class Response{

        /**
         * Programming Error
         * @return string
         */
        public static function PE(){
            return Response::makeResponse(
                "PE",
                "You made a programming error. Either scripts are not included correctly"
            );}
        /**
         * Unqualified Type Error
         * @return string
         */
        public static function UTE(){
            return Response::makeResponse(
                "UTE",
                "You don't have the privilege to perform this function"
            );
        }
        /**
         * Wrong Email Error
         * @return string 
         */
        public static function WEE(){
            return json_encode([
            "status" => "WEE", 
            "message" => "You entered an invalid credential"
        ]);}

        /**
         * SQL Error
         * @return string
         */
        public static function SQE(){
            return json_encode(
            ["status" => "SQL", 
            "message" => "An internal System error occurred"
        ]);}

        /**
         * Wrong Password Error
         * @return string
         */
        public static function WPE(){
            return json_encode([
            "status" => "WPE", 
            "message" => "You entered an invalid credential"
        ]);}

        /**
         * No Password Error
         * @return string
         */
        public static function NPE(){
            return json_encode([
            "status" => "NPE", 
            "message" => "Password is required"
        ]);}

        /**
         * No Email Error
         * @return string
         */
        public static function NEE(){
            return json_encode([
            "status" => "NEE", 
            "message" => "Email is required"
        ]);}

        /**
         * No ID Error
         * @return string
         */
        public static function NIE(){
            return json_encode([
            "status" => "NIE", 
            "message" => "It is like you are not logged in"
        ]);}

        /**
         * Email Exist Error
         * @return string
         */
        public static function EEE(){
            return json_encode([
            "status" => "EEE", 
            "message" => "The email already exist"
        ]);}

        /**
         * Unqualified Email Error
         * @return string
         */
        public static function UEE(){
            return json_encode([
            "status" => "UEE", 
            "message" => "The email is invalid."
        ]);}

        /**
         * Unqualified Phone number Error
         * @return string
         */
        public static function UQPNE(){
            return json_encode([
            "status" => "UQPNE", 
            "message" => "The phone number you entered is invalid"
        ]);}

        /**
         * Phone Number Exist Error
         * @return string
         */
        public static function PNEE(){
            return json_encode([
            "status" => "PNEE", 
            "message" => "The phone number you entered is already attached to another account"
        ]);}

        /**
         * Uqualified Name Error
         * @return string
         */
        public static function UNE(){
            return Response::makeResponse("UNE", "The name contains unaccepted characters");
        }

        /**
         * Invalid Image Error
         * @return string
         */
        public static function IIE(){
            return Response::makeResponse("IEE", "The image you entered is invalid. Accepted image types are: .jpeg, .png, .gif, .bmp, .webp");
        }

        /**
         * User Not Found Error
         * @return string
         */
        public static function UNFE(){
            return json_encode([
            "status" => "UNFE", 
            "message" => "Could not find user with the given credential"
        ]);}

        /**
         * Uknown Error Occurred
         * @return string
         */
        public static function UEO(){
            return json_encode([
            "status" => "UEO", 
            "message" => "An unknown error occurred"
        ]);}

        /**
         * Cannont Update User Error
         * @return string
         */
        public static function CUUE(){
            return json_encode([
            "status" => "CUUE", 
            "message" => "To update your account, your first name, last name, email, phone number, and profile image must be set"
        ]);}

        /**
         * Change Password Token Not Found Error
         * @return string
         */
        public static function CPTNFE(){
            return json_encode([
            "status" => "CPTNFE", 
            "message" => "You entered the wrong codes"
        ]);}

        /**
         * Change Password Expired Token Error
         * @return string
         */
        public static function CPETE(){
            return json_encode([
            "status" => "CPETE", 
            "message" => "The codes has expired"
        ]);}

        /**
         * General OK
         * @return string
         */
        public static function OK(){
            return json_encode([
            "status" => "OK", 
            "message" => "success"
        ]);}

        /**
         * Already Logged In Error
         * @return string
         */
        public static function ALIE(){
            return json_encode([
            "status" => "ALIE", 
            "message" => "You are already logged in"
        ]);}

        /**
         * Not Logged In Error
         * @return string
         */
        public static function NLIE(){
            return json_encode([
            "status" => "NLIE", 
            "message" => "You are not logged in"
        ]);}

        /**
         * Make a new response
         * @param string $status - Status code
         * @param string $message - The message to send
         * @return string
         */
        public static function makeResponse($status, $message){
            $response = [
                "status" => $status, 
                "message" => $message
            ];
            return json_encode($response);
        }

        /**
         * ----------------------------------
         * Driver responses Below
         * ----------------------------------
         */

        /**
          * Null National ID Error
          * @return string
          */
        public static function NNIE(){
            return self::makeResponse(
                "NNIE",
                "National ID field cannot be empty"
            );
        }

        /**
          * Null National ID Image Error
          * @return string
          */
        public static function NIIIE(){
            return self::makeResponse(
                "NIIIE",
                "National ID Image is invalid. Only ". Utility::$acceptedImages. " are allowed"
            );
        }

        /**
          * Regular License Image Invalid Error
          * @return string
          */
        public static function RLIIE(){
            return self::makeResponse(
                "RLIIE",
                "Driving license image is invalid. Only ". Utility::$acceptedImages. " are allowed"
            );
        }

        /**
          * Public Service Vehicle License Image Invalid Error
          * @return string
          */
        public static function PSVLIIE(){
            return self::makeResponse(
                "PSVLIIE",
                "Public Service Vehicle driving license image is invalid. Only ". Utility::$acceptedImages. " are allowed"
            );
        }

        /**
         * Null Regular License Error
         * @return string
         */
        public static function NRLE(){
            return self::makeResponse(
                "NRLE",
                "Your driving license number is required"
            );
        }

        /**
         * ---------------------------------
         * Vehicle Errors
         * ---------------------------------
         */

         /**
          * Null Vehicle Manufacturer error
          * @return string
          */
        public static function NVMAE(){
            return Response::makeResponse(
                "NMAE",
                "Please add the manufacturer of the vehicle"
            );
        }

        /**
         * Null Vehicle Model Error
         * @return string
         */
        public static function NVME(){
            return Response::makeResponse(
                "NVME",
                "Please add the model of the vehicle you have"
            );
        }

        /**
         * Unqualified Vehicle Capacity Error
         * @return string
         */
        public static function UVCE(){
            return Response::makeResponse(
                "UVCE",
                "Please enter a valid capacity of your vehicle"
            );
        }


        /**
         * Null Vehicle License Error
         * @return string
         */
        public static function NVLE(){
            return Response::makeResponse(
                "NVLE",
                "Please add the License plate number of your vehicle"
            );
        }

        /**
         * Null Vehicle Color Error
         * @return string
         */
        public static function NVCE(){
            return Response::makeResponse(
                "NVCE",
                "You did not write the color of your vehicle"
            );
        }

        
        /**
         * Vehicle Null ID Error
         * @return string
         */
        public static function VNIE(){
            return Response::makeResponse(
                "VNIE",
                "You have not added any vehicle yet"
            );
        }

        /**
         * Vehicle Insurance Image Invalid Error
         * @return string
         */
        public static function VIIIE(){
            return Response::makeResponse(
                "VIIIE",
                "The insurance document image of the vehicle is invalid. Only " . Utility::$acceptedImages. " are allowed"
            );
        }

        
        /**
         * Vehicle Registration Image Invalid Error
         * @return string
         */
        public static function VRIIE(){
            return Response::makeResponse(
                "VRIIE",
                "The registration document image of the vehicle is invalid. Only " . Utility::$acceptedImages. " are allowed"
            );
        }

        
        /**
         * Vehicle Inspection Report Image Invalid Error
         * @return string
         */
        public static function VIRIIE(){
            return Response::makeResponse(
                "VIRIIE",
                "The inspection report document image of the vehicle is invalid. Only " . Utility::$acceptedImages. " are allowed"
            );
        }

        /**
         * --------------------------
         * Rider responses
         * -------------------------
         */

         /**
          * Profile Not complete error.
          */
        public static function PNCE(){
            return Response::makeResponse(
                "PNCE",
                "Please add all the following: full names, email, phone number, and lastly, ensure that your email is confirmed"
            );
        }
    }

?>