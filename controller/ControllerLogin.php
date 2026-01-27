<?php
require_once "framework/Controller.php";

class ControllerLogin extends Controller {
    public function index(): void {
        (new View("login"))->show([""]);
    }

    
}   