<?php
require_once 'framework/View.php';
require_once "framework/Controller.php";
require_once "model/User.php";

class ControllerLogin extends Controller {

    public function index() : void {
        
        if ($this->user_logged()) {
            $this->redirect("item", "index");
        } else {
            
            (new View("login"))->show(["errors" => []]);
        }
    }
    
    public function login(): void {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        
        $errors = User::validate_login($email, $password);
        
        if (empty($errors)) {
            
            $user = User::getUserByEmail($email);
            $this->log_user($user); 

           
            $this->redirect("item", "index"); 
        } else {
            
            (new View("login"))->show([
                "email" => $email, 
                "errors" => $errors
            ]);
        }
    }

    public function logout() : void {
      parent::logout();
    }
}