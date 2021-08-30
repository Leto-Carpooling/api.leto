<?php


 use Kreait\Firebase\Factory;

 class FirebaseManager{
       private $factory,
                $firebaseDb;

       public function FirebaseManager(){
           $this->factory = (new Factory())->withServiceAccount(__DIR__."/../includes/". LETO_FB_JSON);
            $this->factory = $this->factory->withDatabaseUri(LETO_NOSQL_URI);
            $this->firebaseDb = $this->factory->createDatabase();
          
       }

       public function set($url, $data){
           $this->firebaseDb->getReference($url)->set($data);
       }

       public function remove($url){
        $this->firebaseDb->getReference($url) ->remove();
       }

       public function ref($url){
           return $this->firebaseDb->getReference($url);
       }
 }
?>