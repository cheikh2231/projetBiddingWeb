<?php
require_once 'framework/View.php';
require_once "framework/Controller.php";
require_once "model/User.php";

class ControllerProfile extends Controller{
    public function index(): void
    {
        $userConnected = $this->get_user_or_redirect();
        (new View("profile"))->show(["user"=>$userConnected]);
    }


}