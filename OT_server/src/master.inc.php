<?php
require(__DIR__."../vendor/autoload.php");
/** 
 * Include classes as needed and interfaces as needed
 */
 spl_autoload_register(function($name){
    $classname = strtolower($name);
    include(__DIR__. "/classes/$classname.class.php");
 });

 spl_autoload_register(function($name){
    $interfacename = strtolower($name);
    $interfacename = preg_replace("/^.*interface$/", "", $interfacename);
    include(__DIR__ . "/interfaces/$interfacename.interface.php");
 });

?>