<?php
require_once "framework/Controller.php";

class ControllerHomepage extends Controller {
    public function index(): void {
        (new View("homepage"))->show([""]);
    }
}   