<?php
  require("master.inc.php");
  if(!$isLoggedIn){
      exit(Response::NLIE());
  }

  $user = new User($userId);
  var_dump($user);


?>