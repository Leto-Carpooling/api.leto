<?php

interface UserInterface{
    static const DEFAULT_AVATAR = "assets/images/profile.svg";
    public function register();
    public function login();
    public function logout();
    public function deleteAccount();
}

?>