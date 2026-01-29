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
    // méthode login
    public function login(): void {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        
        $errors = User::validate_login($email, $password);
        
        if (empty($errors)) {
            $this->is_validate_login($email);
        } else {
            
            (new View("login"))->show([
                "email" => $email, 
                "errors" => $errors
            ]);
        }
    }  
    // fonction si login valide (évite la répétition de code)
    public function is_validate_login(String $emailUser): void{
        $user = User::getUserByEmail($emailUser);
        $this->log_user($user);
        $this->redirect("item", "index"); 
    }
    //fonction pour log en Debug
    public function logForDebug():void{
        $userEmail = $_GET['param1']."@epfc.eu";
       $this->is_validate_login($userEmail);
    }
    //fonction logout
    public function logout() : void {
      parent::logout();
    } 
}