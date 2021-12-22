<?php

 class RouteFactory{
    private function __construct(){

    }

    public static function getNewId(){
        $dbManager = new DbManager();
        return $dbManager->insert(Route::ROUTE_TABLE, [Route::ROUTE_TABLE_ID], [null]);
    }
 }

?>