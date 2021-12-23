<?php

/**
 * This file must only be called by the children master.inc.php in each folder
 */

if (isset($_SERVER['HTTP_ORIGIN'])) {
   // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
   // you want to allow, and if so:
   header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
   header('Access-Control-Allow-Credentials: true');
   header('Access-Control-Max-Age: 86400');    // cache for 1 day
}

// Access-Control headers are received during OPTIONS requests
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
   
   if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
       // may also be using PUT, PATCH, HEAD etc
       header("Access-Control-Allow-Methods: GET, POST, OPTIONS");         
   
   if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
       header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
}

require(__DIR__."/../vendor/autoload.php");
/** 
 * Include classes as needed and interfaces as needed
 */

spl_autoload_register(function($name){
   $classname = strtolower($name);
   $filename = __DIR__. "/classes/$classname.class.php";
   if(file_exists($filename)){
      include($filename);
   }
});




 $isLoggedIn = false;
 $userId = 0;
 $sessionId;
 //checking if the user is logged in.

 if(isset($_SERVER["HTTP_AUTH"])){
   $auth = $_SERVER["HTTP_AUTH"];
   $auth = preg_split("/-/", $auth);
   $id = $auth[0];

   $token = $auth[1];

   $dbManager = new DbManager();
   $result = $dbManager->query("session", ["session_id", "userId"], "session_token = ? and userId = ?", [$token, $id]);
   
   if($result !== false){
      $userId = $result["userId"];
      $sessionId = $result["session_id"];
      $isLoggedIn = true;
   }

   $dbManager->close();
 }
?>