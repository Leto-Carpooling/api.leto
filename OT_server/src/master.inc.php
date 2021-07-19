<?php
require(__DIR__."/../vendor/autoload.php");
/** 
 * Include classes as needed and interfaces as needed
 */

spl_autoload_register(function($name){
   $classname = strtolower($name);
   $interfacename = strtolower($name);
   $interfacename = preg_replace("/^.*interface$/", "", $interfacename);
   $filename = __DIR__ . "/interfaces/$interfacename.interface.php";
   if(file_exists($filename)){
      include($filename);
   }
   $filename = __DIR__. "/classes/$classname.class.php";
   if(file_exists($filename)){
      include($filename);
   }
});




 $isLoggedIn = false;
 $userId = 0;
 $sessionId;
 //checking if the user is logged in.
 if(isset($_REQUEST["auth"])){
   $auth = $_REQUEST["auth"];
   $auth = preg_split("/-/", $auth);
   $id = $auth[0];
   $token = $auth[1];

   $dbManager = new DbManager();
   $result = $dbManager->query("session", ["session_id", "userId"], "session_token = ? and userId = ?", [$id, $token]);
   if($result){
      $userId = $result["userId"];
      $sessionId = $result["session_id"];
      $isLoggedIn = true;
   }
 }
?>