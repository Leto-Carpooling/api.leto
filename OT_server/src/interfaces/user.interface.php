<?php

interface UserInterface{
    const DEFAULT_AVATAR = "./../assets/img/profile.svg";
    public function register();
    public function login();
    public function logout();
    public function deleteAccount();
}

?>