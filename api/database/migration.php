<?php
/**
 * Migrates the database
 */

 require(__DIR__."/../master.inc.php");


 class Migrator{
     const SCHEMA = __DIR__."/schema.sql";

     public static function migrate(){
        $query = file_get_contents(Migrator::SCHEMA);
        $query = preg_replace("/\[DATABASE_NAME\]/", Utility::getEnv()->dbName, $query);
        $query = preg_replace("/\r\n/", " ", $query);
        $dbManager = new DbManager();
        if($dbManager->makeDatabase($query)){
            echo "\nCreated the database\n";
        }
        else{
            echo "\nDidn't create the database\n";
        }
     }

 }

 Migrator::migrate();

?>