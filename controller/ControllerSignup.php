<?php
require_once "framework/Controller.php";

class ControllerSignup extends Controller {
    public function index(): void {
       if ($this->user_logged()) {
            $this->redirect("item", "index");
        } else {
            
            (new View("signup"))->show([]);
        }
    }
}