<?php

/**
 * Binary tree for sorting nodes
 * From github 
 * @link  https://gist.github.com/thinkphp/1448754/9408efdd433c7c01b7eda17ad808ace53f4c3a38
 */
class BinaryTree {

    public $root;
    public $array;

    const IN_ORDER = 'inorder', PRE_ORDER = 'preorder', POST_ORDER = 'postorder';

    public function  __construct() {
           $this->array = [];
           $this->root = NULL;
    }

    public function push(&$route, $compareBy) {
        
           if($this->root == NULL) {

              $this->root = new RouteNode($route, $compareBy);

           } else {

              $current = $this->root;
              $newNode = new RouteNode($route, $compareBy);

              while(true) {
                    $current->isCloser($newNode);

                    if($compareBy <= $current->compareBy) {
                          
                          if($current->left) {
                             $current = $current->left;
                          } else {
                             $current->left = $newNode;
                             break; 
                          }

                    } else if($compareBy > $current->compareBy){

                          if($current->right) {
                             $current = $current->right;
                          } else {
                             $current->right = $newNode;
                             break; 
                          }

                    } else {

                      break;
                    }
              } 
           }
    }

    /**
     * traverse the tree
     * @param  string $method - 'preorder', 'postorder', 'inorder'
     * @return array
     */

    public function traverse($method) {
        $this->array = [];

           switch($method) {

               case 'inorder':
               $this->_inorder($this->root);
               break;

               case 'postorder':
               $this->_postorder($this->root);
               break;

               case 'preorder':
               $this->_preorder($this->root);
               break;

               default:
               break;
           } 

           return $this->array;

    } 

    /**
     * Converts the binary tree to array
     * @return array
     */
    public function toArray(){
        return $this->traverse(BinaryTree::IN_ORDER);
    }

    private function _inorder(RouteNode &$node) {

                    if($node->left) {
                       $this->_inorder($node->left); 
                    } 

                    $this->array[] = $node;

                    if($node->right) {
                       $this->_inorder($node->right); 
                    } 
    }


    private function _preorder(RouteNode &$node) {

                    $this->array[] = $node;

                    if($node->left) {
                       $this->_preorder($node->left); 
                    } 


                    if($node->right) {
                       $this->_preorder($node->right); 
                    } 
    }


    private function _postorder(RouteNode &$node) {


                    if($node->left) {
                       $this->_postorder($node->left); 
                    } 


                    if($node->right) {
                       $this->_postorder($node->right); 
                    } 

                    $this->array[] = $node;
    }


    public function BFT() {

           $node = $this->root;
           
           $node->level = 1; 

           $queue = array($node);

           $out = array("<br/>");


           $current_level = $node->level;


           while(count($queue) > 0) {

                 $current_node = array_shift($queue);

                 if($current_node->level > $current_level) {
                      $current_level++;
                      array_push($out,"<br/>");  
                 } 

                 array_push($out,$current_node->info. " ");

                 if($current_node->left) {
                    $current_node->left->level = $current_level + 1;
                    array_push($queue,$current_node->left); 
                 }    

                 if($current_node->right) {
                    $current_node->right->level = $current_level + 1;
                    array_push($queue,$current_node->right); 
                 }    
           }

          
          return join($out,""); 
    }
} 


?>