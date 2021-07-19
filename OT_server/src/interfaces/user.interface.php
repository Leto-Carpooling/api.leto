<?php

interface UserInterface{
    public function register();
    public function login();
    public function logout();
    public function deleteAccount();
}

?>