<?php
require_once 'framework/View.php';
require_once "framework/Controller.php";
require_once "model/User.php";

class ControllerLogin extends Controller {
 
    public function index() : void {
        if ($this->user_logged()) {
            $this->redirect("test", "index"); // vers page cheikh
        } else {
             (new View("login"))->show(["errors" => []]);
        }
    }
    
    public function login():void{
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        
        $errors = User::validate_login($email, $password);

        if (empty($errors)) {
            $this->log_user(User::getUserByEmail($email));
        } else {
            (new View("login"))->show(["email" => $email, "password" => $password, "errors" => $errors]);
        }
    }
    

}   