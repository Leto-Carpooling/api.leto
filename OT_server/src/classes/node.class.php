<?php

 class Node
 {
   /**
    * The Object this route represents
    * @property object $object
    */
   public $object;

   public $left,
          $leftEdge,
          $right,
          $rightEdge;

   public function __construct($object){
      $this->object = $object;
      $this->left = null;
      $this->right = null;
      $this->left = $this->rightEdge = -1;
   }
   
 }
 

?>